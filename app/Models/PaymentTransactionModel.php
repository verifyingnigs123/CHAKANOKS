<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentTransactionModel extends Model
{
    protected $table = 'payment_transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'transaction_number', 'purchase_order_id', 'delivery_id', 'branch_id', 'supplier_id',
        'payment_method', 'amount', 'status', 'paypal_transaction_id', 'paypal_payer_id',
        'payment_date', 'processed_by', 'notes', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'purchase_order_id' => 'required|integer',
        'branch_id' => 'required|integer',
        'supplier_id' => 'required|integer',
        'payment_method' => 'required|in_list[cod,paypal]',
        'amount' => 'required|decimal',
    ];
    
    public function generateTransactionNumber()
    {
        $prefix = 'PAY';
        $date = date('Ymd');
        $lastTransaction = $this->like('transaction_number', $prefix . $date)->orderBy('id', 'DESC')->first();
        
        if ($lastTransaction) {
            $lastNumber = (int) substr($lastTransaction['transaction_number'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
    
    public function getByPurchaseOrder($poId)
    {
        return $this->where('purchase_order_id', $poId)->first();
    }
    
    public function getByDelivery($deliveryId)
    {
        // Use a fresh query to avoid any leftover state
        $db = \Config\Database::connect();
        $builder = $db->table($this->table);
        return $builder->where('delivery_id', $deliveryId)->get()->getRowArray();
    }
}

