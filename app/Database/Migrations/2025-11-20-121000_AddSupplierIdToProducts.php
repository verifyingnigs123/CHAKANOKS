<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSupplierIdToProducts extends Migration
{
    public function up()
    {
        $fields = [
            'supplier_id' => [
                'type' => 'INT',
                'unsigned' => true,
                'null' => true,
            ],
        ];

        $this->forge->addColumn('products', $fields);
        $this->db->query('ALTER TABLE `products` ADD CONSTRAINT `products_supplier_fk` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers`(`id`) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE `products` DROP FOREIGN KEY `products_supplier_fk`');
        $this->forge->dropColumn('products', ['supplier_id']);
    }
}
