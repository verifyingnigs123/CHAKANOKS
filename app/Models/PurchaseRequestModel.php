<?php

namespace App\Models;

use CodeIgniter\Model;

class PurchaseRequestModel extends Model
{
    protected $table = 'purchase_requests';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'request_number', 'branch_id', 'requested_by', 'supplier_id', 'status', 'priority', 'notes', 
        'approved_by', 'approved_at', 'rejection_reason', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'request_number' => 'required|is_unique[purchase_requests.request_number,id,{id}]',
        'branch_id' => 'required|integer',
        'requested_by' => 'required|integer',
        'supplier_id' => 'permit_empty|integer',
    ];
    
    public function generateRequestNumber()
    {
        $prefix = 'PR';
        $date = date('Ymd');
        $lastRequest = $this->like('request_number', $prefix . $date)->orderBy('id', 'DESC')->first();
        
        if ($lastRequest) {
            $lastNumber = (int) substr($lastRequest['request_number'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}

