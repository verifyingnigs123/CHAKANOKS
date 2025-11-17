<?php

namespace App\Controllers;

use App\Models\SettingModel;
use App\Models\ActivityLogModel;

class SettingController extends BaseController
{
    protected $settingModel;
    protected $activityLogModel;

    public function __construct()
    {
        $this->settingModel = new SettingModel();
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'system_admin') {
            return redirect()->to('/dashboard')->with('error', 'Only system administrators can access settings');
        }

        $data['settings'] = $this->settingModel->getAllSettings();
        return view('settings/index', $data);
    }

    public function update()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'system_admin') {
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
}

