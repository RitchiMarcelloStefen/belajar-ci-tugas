<?php 
 
namespace App\Database\Seeds; 
 
use CodeIgniter\Database\Seeder; 
 
class ProductCategory extends Seeder 
{ 
    public function run() 
    { 
        //Membuat Data 
        $data = [ 
            [ 
                'name' => 'Elektronik', 
                'description' => 'Peralatan elektronik seperti TV, HP, Laptop', 
                'created_at' => date('Y-m-d H:i:s'), 
            ], 
            [ 
                'name' => 'Pakaian', 
                'description' => 'Berbagai jenis pakaian pria dan wanita', 
                'created_at' => date('Y-m-d H:i:s'), 
            ], 
            [ 
                'name' => 'Makanan & Minuman', 
                'description' => 'Produk makanan instan dan minuman kemasan', 
                'created_at' => date('Y-m-d H:i:s'), 
            ], 
        ]; 
 
        foreach ($data as $item) { 
            // insert semua data ke tabel 
            $this->db->table('product_category')->insert($item); 
        } 
 
    } 
}