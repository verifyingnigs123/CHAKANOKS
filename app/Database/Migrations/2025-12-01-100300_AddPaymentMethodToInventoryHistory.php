<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentMethodToInventoryHistory extends Migration
{
    public function up()
    {
        $fields = [
            'payment_method' => [
                'type'       => 'ENUM',
                'constraint' => ['cod', 'paypal', 'pending', 'other'],
                'default'    => null,
                'null'       => true,
                'after'      => 'transaction_type',
            ],
        ];

        $this->forge->addColumn('inventory_history', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('inventory_history', ['payment_method']);
    }
}


