<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentMethodToDeliveries extends Migration
{
    public function up()
    {
        $fields = [
            'payment_method' => [
                'type'       => 'ENUM',
                'constraint' => ['cod', 'paypal', 'pending'],
                'default'    => 'pending',
                'null'       => false,
                'after'      => 'branch_id',
            ],
        ];

        $this->forge->addColumn('deliveries', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('deliveries', ['payment_method']);
    }
}


