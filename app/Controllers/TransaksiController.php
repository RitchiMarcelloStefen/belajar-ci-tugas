<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class TransaksiController extends BaseController
{
    protected $cart;
    protected $client;
    protected $apikey;
    protected $transaction;
    protected $transaction_detail;
    function __construct()
    {
        helper('number');
        helper('form');
        $this->cart = \Config\Services::cart();
        $this->client = new \GuzzleHttp\Client();
        $this->apiKey = env('COST_KEY');
        $this->transaction = new TransactionModel();
        $this->transaction_detail = new TransactionDetailModel  ();
    }

    public function index()
    {
        $data['items'] = $this->cart->contents();
        $data['total'] = $this->cart->total();
        return view('v_keranjang', $data);
    }

    public function cart_add()
    {
        $session = session();
        $diskonData = $session->get('diskon') ?? [];

        if (!is_array($diskonData)) {
            $diskonData = [];
        }

        $hargaAsli = $this->request->getPost('harga');
        $hargaDiskon = $hargaAsli;

        // Ambil diskon khusus untuk tanggal hari ini
        $today = date('Y-m-d');
        $diskonHariIni = null;
        foreach ($diskonData as $diskon) {
            if ($diskon['tanggal'] === $today) {
                $diskonHariIni = $diskon;
                break;
            }
        }

        if ($diskonHariIni) {
            $hargaDiskon = max(0, $hargaAsli - $diskonHariIni['nominal']);
        }

        // Debug logs
        log_message('debug', 'Diskon Hari Ini: ' . print_r($diskonHariIni, true));
        log_message('debug', 'Harga Asli: ' . $hargaAsli);
        log_message('debug', 'Harga Diskon: ' . $hargaDiskon);

        $this->cart->insert(array(
            'id'        => $this->request->getPost('id'),
            'qty'       => 1,
            'price'     => $hargaDiskon,
            'name'      => $this->request->getPost('nama'),
            'options'   => array(
                'foto' => $this->request->getPost('foto'),
                'harga_asli' => $hargaAsli,
                'diskon' => $diskonHariIni ? $diskonHariIni['nominal'] : 0
            )
        ));
        session()->setflashdata('success', 'Produk berhasil ditambahkan ke keranjang. (<a href="' . base_url() . 'keranjang">Lihat</a>)');
        return redirect()->to(base_url('/'));
    }

    public function cart_clear()
    {
        $this->cart->destroy();
        session()->setflashdata('success', 'Keranjang Berhasil Dikosongkan');
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_edit()
    {
        $session = session();
        $diskonData = $session->get('diskon') ?? [];
        if (!is_array($diskonData)) {
            $diskonData = [];
        }
        $today = date('Y-m-d');
        $diskonHariIni = null;
        foreach ($diskonData as $diskon) {
            if ($diskon['tanggal'] === $today) {
                $diskonHariIni = $diskon;
                break;
            }
        }

        $i = 1;
        foreach ($this->cart->contents() as $value) {
            $qty = $this->request->getPost('qty' . $i++);
            $hargaAsli = $value['options']['harga_asli'] ?? $value['price'];
            $hargaDiskon = $hargaAsli;
            $diskonNominal = 0;
            if ($diskonHariIni) {
                $hargaDiskon = max(0, $hargaAsli - $diskonHariIni['nominal']);
                $diskonNominal = $diskonHariIni['nominal'];
            }
            $this->cart->update(array(
                'rowid' => $value['rowid'],
                'qty'   => $qty,
                'price' => $hargaDiskon,
                'options' => array_merge($value['options'], ['diskon' => $diskonNominal])
            ));
        }

        session()->setflashdata('success', 'Keranjang Berhasil Diedit');
        return redirect()->to(base_url('keranjang'));
    }

    public function cart_delete($rowid)
    {
        $this->cart->remove($rowid);
        session()->setflashdata('success', 'Keranjang Berhasil Dihapus');
        return redirect()->to(base_url('keranjang'));
    }

    public function checkout()
    {
         $data['items'] = $this->cart->contents();
         $data['total'] = $this->cart->total();

         return view('v_checkout', $data);
    }

    public function getLocation()
{
		//keyword pencarian yang dikirimkan dari halaman checkout
    $search = $this->request->getGet('search');

    $response = $this->client->request(
        'GET', 
        'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search='.$search.'&limit=50', [
            'headers' => [
                'accept' => 'application/json',
                'key' => $this->apiKey,
            ],
        ]
    );

    $body = json_decode($response->getBody(), true); 
    return $this->response->setJSON($body['data']);
}

public function getCost()
{ 
		//ID lokasi yang dikirimkan dari halaman checkout
    $destination = $this->request->getGet('destination');

		//parameter daerah asal pengiriman, berat produk, dan kurir dibuat statis
    //valuenya => 64999 : PEDURUNGAN TENGAH , 1000 gram, dan JNE
    $response = $this->client->request(
        'POST', 
        'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
            'multipart' => [
                [
                    'name' => 'origin',
                    'contents' => '64999'
                ],
                [
                    'name' => 'destination',
                    'contents' => $destination
                ],
                [
                    'name' => 'weight',
                    'contents' => '1000'
                ],
                [
                    'name' => 'courier',
                    'contents' => 'jne'
                ]
            ],
            'headers' => [
                'accept' => 'application/json',
                'key' => $this->apiKey,
            ],
        ]
    );

    $body = json_decode($response->getBody(), true); 
    return $this->response->setJSON($body['data']);
}

    public function buy()
    {
        if ($this->request->getPost()) { 
            $session = session();
            $diskonData = $session->get('diskon') ?? [];

            $dataForm = [
                'username' => $this->request->getPost('username'),
                'total_harga' => $this->request->getPost('total_harga'),
                'alamat' => $this->request->getPost('alamat'),
                'ongkir' => $this->request->getPost('ongkir'),
                'status' => 0,
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ];

            $this->transaction->insert($dataForm);

            $last_insert_id = $this->transaction->getInsertID();

            foreach ($this->cart->contents() as $value) {
                $hargaAsli = $value['options']['harga_asli'] ?? $value['price'];
                $diskon = $hargaAsli - $value['price'];

                $dataFormDetail = [
                    'transaction_id' => $last_insert_id,
                    'product_id' => $value['id'],
                    'jumlah' => $value['qty'],
                    'diskon' => $diskon,
                    'subtotal_harga' => $value['qty'] * $value['price'],
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s")
                ];

                $this->transaction_detail->insert($dataFormDetail);
            }

            $this->cart->destroy();
     
            return redirect()->to(base_url());
        }
    }

}

