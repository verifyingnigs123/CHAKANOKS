<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransferItemsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'transfer_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'product_id' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'quantity' => [
                'type'       => 'INT',
                'unsigned'   => true,
            ],
            'quantity_received' => [
                'type'       => 'INT',
                'unsigned'   => true,
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('transfer_id', 'transfers', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('product_id', 'products', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('transfer_items');
    }

    public function down()
    {
        $this->forge->dropTable('transfer_items');
    }
}

