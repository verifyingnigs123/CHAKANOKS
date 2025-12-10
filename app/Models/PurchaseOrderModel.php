<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseOrderModel extends Model
{
    protected $table = 'purchase_orders';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'po_number', 'purchase_request_id', 'supplier_id', 'branch_id', 'created_by', 'status', 
        'order_date', 'expected_delivery_date', 'subtotal', 'tax', 'total_amount', 'notes', 'created_at', 'updated_at',
        'prepared_at', 'prepared_by', 'sent_at', 'confirmed_at', 'completed_at', 'payment_method',
        // Delivery tracking fields
        'delivery_status', 'tracking_number', 'delivery_notes', 'delivery_status_updated_at', 'delivery_status_updated_by',
        // Invoice fields
        'invoice_number', 'invoice_date', 'invoice_amount', 'invoice_notes', 'invoice_submitted_at', 'invoice_submitted_by'
    ];
    
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'po_number' => 'required|is_unique[purchase_orders.po_number,id,{id}]',
        'supplier_id' => 'required|integer',
        'branch_id' => 'required|integer',
    ];
    
    public function generatePONumber()
    {
        $prefix = 'PO';
        $date = date('Ymd');
        $lastPO = $this->like('po_number', $prefix . $date)->orderBy('id', 'DESC')->first();
        
        if ($lastPO) {
            $lastNumber = (int) substr($lastPO['po_number'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}

