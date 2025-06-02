<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ProductCategoryModel;

class ProductCategoryController extends BaseController
{
    protected $productCategoryModel;

    public function __construct()
    {
        $this->productCategoryModel = new ProductCategoryModel();
    }

    public function index()
    {
        $data['categories'] = $this->productCategoryModel->findAll();
        return view('v_product_category', $data);
    }

    public function store()
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        $this->productCategoryModel->insert($data);
        return redirect()->to('/produk-kategori')->with('success', 'Data Berhasil Ditambah');
    }

    public function edit($id)
    {
        $data['category'] = $this->productCategoryModel->find($id);
        if (!$data['category']) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Category with ID $id not found");
        }
        return view('v_product_category_edit', $data);
    }

    public function update($id)
    {
        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
        ];

        $this->productCategoryModel->update($id, $data);
        return redirect()->to('/produk-kategori')->with('success', 'Data Berhasil Diubah');
    }

    public function delete($id)
    {
        $this->productCategoryModel->delete($id);
        return redirect()->to('/produk-kategori')->with('success', 'Data Berhasil Dihapus');
    }
}
