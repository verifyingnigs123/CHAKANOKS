<?php

namespace App\Models;

use CodeIgniter\Model;

class DeliveryModel extends Model
{
    protected $table = 'deliveries';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'delivery_number', 'purchase_order_id', 'supplier_id', 'branch_id', 'status', 
        'scheduled_date', 'delivery_date', 'received_by', 'received_at', 
        'driver_name', 'vehicle_number', 'notes', 'payment_method', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'delivery_number' => 'required|is_unique[deliveries.delivery_number,id,{id}]',
        'purchase_order_id' => 'required|integer',
        'supplier_id' => 'required|integer',
        'branch_id' => 'required|integer',
    ];
    
    public function generateDeliveryNumber()
    {
        $prefix = 'DEL';
        $date = date('Ymd');
        $lastDelivery = $this->like('delivery_number', $prefix . $date)->orderBy('id', 'DESC')->first();
        
        if ($lastDelivery) {
            $lastNumber = (int) substr($lastDelivery['delivery_number'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}

