<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'key' => [
                'type'       => 'VARCHAR',
                'constraint' => '100',
                'unique'     => true,
            ],
            'value' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'type' => [
                'type'       => 'ENUM',
                'constraint' => ['text', 'number', 'boolean', 'email', 'url'],
                'default'    => 'text',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('settings');

        // Insert default settings
        $defaultSettings = [
            ['key' => 'system_name', 'value' => 'Supply Chain Management System', 'description' => 'System name displayed in header', 'type' => 'text'],
            ['key' => 'company_name', 'value' => 'Your Company', 'description' => 'Company name', 'type' => 'text'],
            ['key' => 'company_email', 'value' => 'admin@company.com', 'description' => 'Company contact email', 'type' => 'email'],
            ['key' => 'company_phone', 'value' => '', 'description' => 'Company contact phone', 'type' => 'text'],
            ['key' => 'tax_rate', 'value' => '12', 'description' => 'Default tax rate percentage', 'type' => 'number'],
            ['key' => 'currency', 'value' => 'PHP', 'description' => 'Currency code', 'type' => 'text'],
            ['key' => 'currency_symbol', 'value' => '₱', 'description' => 'Currency symbol', 'type' => 'text'],
            ['key' => 'low_stock_alert', 'value' => '1', 'description' => 'Enable low stock alerts', 'type' => 'boolean'],
            ['key' => 'auto_approve_purchase_requests', 'value' => '0', 'description' => 'Auto-approve purchase requests', 'type' => 'boolean'],
            ['key' => 'items_per_page', 'value' => '20', 'description' => 'Default items per page in listings', 'type' => 'number'],
        ];

        foreach ($defaultSettings as $setting) {
            $this->db->table('settings')->insert($setting);
        }
    }

    public function down()
    {
        $this->forge->dropTable('settings');
    }
}

