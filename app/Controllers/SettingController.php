<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\ActivityLogModel;
use App\Models\DriverModel;
use App\Libraries\PayPalService;

class SettingController extends BaseController
{
    protected $settingModel;
    protected $activityLogModel;
    protected $driverModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
        $this->activityLogModel = new ActivityLogModel();
        $this->driverModel = new DriverModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Only central administrators can access settings');
        }

        $data['settings'] = $this->settingModel->getAllSettings();
        
        // Add PayPal configuration status
        $paypalService = new PayPalService();
        $data['paypal_configured'] = $paypalService->isConfigured();
        $data['paypal_mode'] = $paypalService->getMode();
        
        // Load users data for the Users tab
        $userModel = new \App\Models\UserModel();
        $branchModel = new \App\Models\BranchModel();
        
        $users = $userModel->findAll();
        $branches = $branchModel->findAll();
        $branchMap = [];
        foreach ($branches as $branch) {
            $branchMap[$branch['id']] = $branch['name'];
        }
        
        foreach ($users as &$user) {
            $user['branch_name'] = $branchMap[$user['branch_id']] ?? null;
        }
        
        $data['users'] = $users;
        $data['branches'] = $branches;
        $data['roles'] = [
            'central_admin' => 'Central Admin',
            'branch_manager' => 'Branch Manager',
            'inventory_staff' => 'Inventory Staff',
            'supplier' => 'Supplier',
            'logistics_coordinator' => 'Logistics Coordinator',
            'franchise_manager' => 'Franchise Manager',
            'driver' => 'Driver'
        ];
        
        // Load drivers data for the Drivers tab
        $data['drivers'] = $this->driverModel->orderBy('name', 'ASC')->findAll();
        
        // Check which tab to show
        $data['activeTab'] = $this->request->getGet('tab') ?? 'settings';
        
        return view('settings/index', $data);
    }

    public function update()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized');
        }

        $settings = $this->request->getPost('settings');
        if (!$settings || !is_array($settings)) {
            return redirect()->back()->with('error', 'Invalid settings data');
        }

        $updated = 0;
        foreach ($settings as $key => $value) {
            if ($this->settingModel->setSetting($key, $value)) {
                $updated++;
            }
        }

        $this->activityLogModel->logActivity(
            $session->get('user_id'),
            'update',
            'settings',
            "Updated {$updated} system settings"
        );

        return redirect()->to('/settings')->with('success', "Successfully updated {$updated} settings");
    }

    /**
     * Store a new driver
     */
    public function storeDriver()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized');
        }

        $driverData = [
            'name' => $this->request->getPost('name'),
            'vehicle_number' => $this->request->getPost('vehicle_number'),
            'phone' => $this->request->getPost('phone'),
            'license_number' => $this->request->getPost('license_number'),
            'status' => $this->request->getPost('status') ?? 'active',
        ];

        if ($this->driverModel->insert($driverData)) {
            $this->activityLogModel->logActivity(
                $session->get('user_id'),
                'create',
                'driver',
                "Created driver: {$driverData['name']}"
            );
            return redirect()->to('/settings?tab=drivers')->with('success', 'Driver added successfully');
        }

        return redirect()->to('/settings?tab=drivers')->with('error', 'Failed to add driver');
    }

    /**
     * Update an existing driver
     */
    public function updateDriver($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized');
        }

        $driver = $this->driverModel->find($id);
        if (!$driver) {
            return redirect()->to('/settings?tab=drivers')->with('error', 'Driver not found');
        }

        $driverData = [
            'name' => $this->request->getPost('name'),
            'vehicle_number' => $this->request->getPost('vehicle_number'),
            'phone' => $this->request->getPost('phone'),
            'license_number' => $this->request->getPost('license_number'),
            'status' => $this->request->getPost('status') ?? 'active',
        ];

        if ($this->driverModel->update($id, $driverData)) {
            $this->activityLogModel->logActivity(
                $session->get('user_id'),
                'update',
                'driver',
                "Updated driver ID: {$id}"
            );
            return redirect()->to('/settings?tab=drivers')->with('success', 'Driver updated successfully');
        }

        return redirect()->to('/settings?tab=drivers')->with('error', 'Failed to update driver');
    }

    /**
     * Delete a driver
     */
    public function deleteDriver($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized');
        }

        $driver = $this->driverModel->find($id);
        if (!$driver) {
            return redirect()->to('/settings?tab=drivers')->with('error', 'Driver not found');
        }

        if ($this->driverModel->delete($id)) {
            $this->activityLogModel->logActivity(
                $session->get('user_id'),
                'delete',
                'driver',
                "Deleted driver: {$driver['name']}"
            );
            return redirect()->to('/settings?tab=drivers')->with('success', 'Driver deleted successfully');
        }

        return redirect()->to('/settings?tab=drivers')->with('error', 'Failed to delete driver');
    }
}

