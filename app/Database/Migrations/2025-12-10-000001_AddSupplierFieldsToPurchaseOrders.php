<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSupplierFieldsToPurchaseOrders extends Migration
{
    public function up()
    {
        // Check if table exists first
        if (!$this->db->tableExists('purchase_orders')) {
            return;
        }

        // Check if columns already exist
        $existingFields = $this->db->getFieldNames('purchase_orders');
        
        $fieldsToAdd = [];
        
        if (!in_array('delivery_status', $existingFields)) {
            $fieldsToAdd['delivery_status'] = [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
            ];
        }
        
        if (!in_array('tracking_number', $existingFields)) {
            $fieldsToAdd['tracking_number'] = [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ];
        }
        
        if (!in_array('delivery_notes', $existingFields)) {
            $fieldsToAdd['delivery_notes'] = [
                'type' => 'TEXT',
                'null' => true,
            ];
        }
        
        if (!in_array('delivery_status_updated_at', $existingFields)) {
            $fieldsToAdd['delivery_status_updated_at'] = [
                'type' => 'DATETIME',
                'null' => true,
            ];
        }
        
        if (!in_array('delivery_status_updated_by', $existingFields)) {
            $fieldsToAdd['delivery_status_updated_by'] = [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ];
        }
        
        if (!in_array('invoice_number', $existingFields)) {
            $fieldsToAdd['invoice_number'] = [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
            ];
        }
        
        if (!in_array('invoice_date', $existingFields)) {
            $fieldsToAdd['invoice_date'] = [
                'type' => 'DATE',
                'null' => true,
            ];
        }
        
        if (!in_array('invoice_amount', $existingFields)) {
            $fieldsToAdd['invoice_amount'] = [
                'type' => 'DECIMAL',
                'constraint' => '12,2',
                'null' => true,
            ];
        }
        
        if (!in_array('invoice_notes', $existingFields)) {
            $fieldsToAdd['invoice_notes'] = [
                'type' => 'TEXT',
                'null' => true,
            ];
        }
        
        if (!in_array('invoice_submitted_at', $existingFields)) {
            $fieldsToAdd['invoice_submitted_at'] = [
                'type' => 'DATETIME',
                'null' => true,
            ];
        }
        
        if (!in_array('invoice_submitted_by', $existingFields)) {
            $fieldsToAdd['invoice_submitted_by'] = [
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
            ];
        }
        
        if (!in_array('completed_at', $existingFields)) {
            $fieldsToAdd['completed_at'] = [
                'type' => 'DATETIME',
                'null' => true,
            ];
        }

        if (!empty($fieldsToAdd)) {
            $this->forge->addColumn('purchase_orders', $fieldsToAdd);
        }
    }

    public function down()
    {
        if (!$this->db->tableExists('purchase_orders')) {
            return;
        }

        $existingFields = $this->db->getFieldNames('purchase_orders');
        $fieldsToDrop = [];
        
        $columnsToCheck = [
            'delivery_status',
            'tracking_number',
            'delivery_notes',
            'delivery_status_updated_at',
            'delivery_status_updated_by',
            'invoice_number',
            'invoice_date',
            'invoice_amount',
            'invoice_notes',
            'invoice_submitted_at',
            'invoice_submitted_by',
            'completed_at',
        ];
        
        foreach ($columnsToCheck as $column) {
            if (in_array($column, $existingFields)) {
                $fieldsToDrop[] = $column;
            }
        }
        
        if (!empty($fieldsToDrop)) {
            $this->forge->dropColumn('purchase_orders', $fieldsToDrop);
        }
    }
}
