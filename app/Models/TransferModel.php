<?php

namespace App\Models;

use CodeIgniter\Model;

class TransferModel extends Model
{
    protected $table = 'transfers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'transfer_number', 'from_branch_id', 'to_branch_id', 'requested_by', 'approved_by', 'status', 
        'request_date', 'approved_at', 'completed_at', 'notes', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'transfer_number' => 'required|is_unique[transfers.transfer_number,id,{id}]',
        'from_branch_id' => 'required|integer',
        'to_branch_id' => 'required|integer',
    ];
    
    public function generateTransferNumber()
    {
        $prefix = 'TRF';
        $date = date('Ymd');
        $lastTransfer = $this->like('transfer_number', $prefix . $date)->orderBy('id', 'DESC')->first();
        
        if ($lastTransfer) {
            $lastNumber = (int) substr($lastTransfer['transfer_number'], -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        return $prefix . $date . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }
}

