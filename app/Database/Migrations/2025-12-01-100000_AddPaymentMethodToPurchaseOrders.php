<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentMethodToPurchaseOrders extends Migration
{
    public function up()
    {
        $fields = [
            'payment_method' => [
                'type'       => 'ENUM',
                'constraint' => ['cod', 'paypal', 'pending'],
                'default'    => 'pending',
                'null'       => false,
                'after'      => 'total_amount',
            ],
        ];

        $this->forge->addColumn('purchase_orders', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('purchase_orders', ['payment_method']);
    }
}

