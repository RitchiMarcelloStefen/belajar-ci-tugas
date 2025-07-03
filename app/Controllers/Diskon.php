<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DiskonModel;

class Diskon extends BaseController
{
    protected $diskonModel;

    public function __construct()
    {
        $this->diskonModel = new DiskonModel();

        // ✅ Cek apakah user yang login adalah admin
        // Ubah pengecekan role agar hanya membatasi method tertentu jika perlu
        // atau hapus pengecekan ini jika semua user boleh akses
        /*
        if (session()->get('role') !== 'admin') {
            exit('Akses ditolak');
        }
        */
    }

    public function setDiskonSession()
    {
        $session = session();
        $today = date('Y-m-d');
        $diskonHariIni = $this->diskonModel->where('tanggal', $today)->first();

        if ($diskonHariIni) {
            $session->set('diskon', [$diskonHariIni]);
        } else {
            $session->remove('diskon');
        }
    }

    public function index()
    {
        $data['diskonList'] = $this->diskonModel->findAll();
        return view('diskon/index', $data); // ubah ke view yang benar
    }

    public function create()
    {
        $data['diskonList'] = $this->diskonModel->findAll();
        $data['validation'] = \Config\Services::validation();
        return view('diskon/index', $data); // mode tambah
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $rules = [
            'tanggal' => 'required|is_unique[diskon.tanggal]',
            'nominal' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            $data['diskonList'] = $this->diskonModel->findAll();
            $data['validation'] = $this->validator;
            return view('diskon/index', $data);
        }

        $this->diskonModel->insert([
            'tanggal' => $this->request->getPost('tanggal'),
            'nominal' => $this->request->getPost('nominal'),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/diskon')->with('success', 'Diskon berhasil ditambah.');
    }

    public function edit($id)
    {
        $data['diskon'] = $this->diskonModel->find($id);
        $data['diskonList'] = $this->diskonModel->findAll();
        $data['validation'] = \Config\Services::validation();
        return view('diskon/index', $data); // mode edit
    }

public function update($id)
{
    $rules = [
        'nominal' => 'required|numeric'
    ];

    if (!$this->validate($rules)) {
        return view('diskon/edit', [
            'diskon' => $this->diskonModel->find($id),
            'validation' => $this->validator
        ]);
    }

    $this->diskonModel->update($id, [
        'nominal' => $this->request->getPost('nominal'),
        'updated_at' => date('Y-m-d H:i:s')
    ]);

    return redirect()->to('/diskon')->with('success', 'Diskon berhasil diupdate.');
}

public function delete($id)
{
    $this->diskonModel->delete($id);
    return redirect()->to('/diskon')->with('success', 'Diskon berhasil dihapus.');
}
}