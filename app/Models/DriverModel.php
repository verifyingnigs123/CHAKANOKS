<?php

namespace App\Models;

use CodeIgniter\Model;

class DriverModel extends Model
{
    protected $table = 'drivers';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'vehicle_number', 'phone', 'license_number', 'status', 'created_at', 'updated_at'
    ];
    
    protected $useTimestamps = true;
    
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[150]',
        'vehicle_number' => 'required|is_unique[drivers.vehicle_number,id,{id}]',
        'status' => 'in_list[active,inactive]',
    ];
    
    /**
     * Get active drivers with their vehicles
     */
    public function getActiveDrivers()
    {
        return $this->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();
    }
    
    /**
     * Get driver by ID with vehicle info
     */
    public function getDriverWithVehicle($driverId)
    {
        return $this->find($driverId);
    }
}

