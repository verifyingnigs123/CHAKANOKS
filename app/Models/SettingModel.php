<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingModel extends Model
{
    protected $table = 'settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['key', 'value', 'description', 'type', 'updated_at'];
    
    protected $useTimestamps = false;
    protected $updatedField = 'updated_at';

    public function getSetting($key, $default = null)
    {
        $setting = $this->where('key', $key)->first();
        if (!$setting) {
            return $default;
        }

        // Convert value based on type
        switch ($setting['type']) {
            case 'boolean':
                return (bool)$setting['value'];
            case 'number':
                return is_numeric($setting['value']) ? (float)$setting['value'] : $default;
            default:
                return $setting['value'] ?? $default;
        }
    }

    public function setSetting($key, $value)
    {
        $setting = $this->where('key', $key)->first();
        if ($setting) {
            return $this->update($setting['id'], [
                'value' => $value,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
        return false;
    }

    public function getAllSettings()
    {
        return $this->orderBy('key', 'ASC')->findAll();
    }
}

