<?php

namespace App\Models;

use CodeIgniter\Model;

class ActivityLogModel extends Model
{
    protected $table = 'activity_logs';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id', 'action', 'module', 'description', 'ip_address', 'user_agent', 'created_at'
    ];
    
    protected $useTimestamps = false;
    protected $createdField = 'created_at';
    
    public function logActivity($userId, $action, $module, $description = null)
    {
        $this->insert([
            'user_id' => $userId,
            'action' => $action,
            'module' => $module,
            'description' => $description,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }
}

