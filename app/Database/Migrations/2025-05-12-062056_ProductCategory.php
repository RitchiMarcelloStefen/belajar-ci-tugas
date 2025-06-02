<?php 
namespace App\Database\Migrations; 
use CodeIgniter\Database\Migration; 
class ProductCategory extends Migration 
{ 
public function up() 
{ 
$this->forge->addField([ 
            'id' => [ 
                'type'           => 'INT', 
                'unsigned'       => TRUE, 
                'auto_increment' => TRUE, 
            ], 
            'name' => [ 
                'type'       => 'VARCHAR', 
                'constraint' => 100, 
                'null'       => FALSE, 
            ], 
            'description' => [ 
                'type'       => 'TEXT', 
                'null'       => TRUE, 
            ], 
            'created_at' => [ 
                'type' => 'DATETIME', 
                'null' => TRUE, 
            ], 
            'updated_at' => [ 
                'type' => 'DATETIME', 
                'null' => TRUE, 
            ], 
        ]); 
 
        $this->forge->addKey('id', TRUE); 
        $this->forge->createTable('product_category'); 
    } 
 
    //-------------------------------------------------------------------- 
 
    public function down() 
    { 
        $this->forge->dropTable('product_category'); 
    } 
} 
