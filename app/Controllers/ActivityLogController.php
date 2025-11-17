<?php

namespace App\Controllers;

use App\Models\ActivityLogModel;
use App\Models\UserModel;

class ActivityLogController extends BaseController
{
    protected $activityLogModel;
    protected $userModel;

    public function __construct()
    {
        $this->activityLogModel = new ActivityLogModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'system_admin' && $role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }

        $builder = $this->activityLogModel->select('activity_logs.*, users.username, users.full_name')
            ->join('users', 'users.id = activity_logs.user_id', 'left')
            ->orderBy('activity_logs.created_at', 'DESC');

        // Search functionality
        $search = $this->request->getGet('search');
        if ($search) {
            $builder->groupStart()
                ->like('activity_logs.action', $search)
                ->orLike('activity_logs.module', $search)
                ->orLike('activity_logs.description', $search)
                ->orLike('users.username', $search)
                ->orLike('users.full_name', $search)
                ->groupEnd();
        }

        // Filter by user
        $filterUser = $this->request->getGet('user_id');
        if ($filterUser) {
            $builder->where('activity_logs.user_id', $filterUser);
        }

        // Filter by action type
        $filterAction = $this->request->getGet('action');
        if ($filterAction) {
            $builder->where('activity_logs.action', $filterAction);
        }

        // Filter by module (entity type)
        $filterEntity = $this->request->getGet('entity_type');
        if ($filterEntity) {
            $builder->where('activity_logs.module', $filterEntity);
        }

        // Date range filter
        $dateFrom = $this->request->getGet('date_from');
        $dateTo = $this->request->getGet('date_to');
        if ($dateFrom) {
            $builder->where('DATE(activity_logs.created_at) >=', $dateFrom);
        }
        if ($dateTo) {
            $builder->where('DATE(activity_logs.created_at) <=', $dateTo);
        }

        $data['logs'] = $builder->findAll();
        $data['users'] = $this->userModel->findAll();
        $data['search'] = $search;
        $data['filterUser'] = $filterUser;
        $data['filterAction'] = $filterAction;
        $data['filterEntity'] = $filterEntity;
        $data['dateFrom'] = $dateFrom;
        $data['dateTo'] = $dateTo;

        return view('activity_logs/index', $data);
    }

    public function export()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'system_admin' && $role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }

        $builder = $this->activityLogModel->select('activity_logs.*, users.username, users.full_name')
            ->join('users', 'users.id = activity_logs.user_id', 'left')
            ->orderBy('activity_logs.created_at', 'DESC');

        // Apply same filters as index
        $search = $this->request->getGet('search');
        if ($search) {
            $builder->groupStart()
                ->like('activity_logs.action', $search)
                ->orLike('activity_logs.module', $search)
                ->orLike('activity_logs.description', $search)
                ->groupEnd();
        }

        $logs = $builder->findAll();

        // Generate CSV
        $filename = 'activity_logs_' . date('Y-m-d_His') . '.csv';
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Date', 'User', 'Action', 'Module', 'Description']);

        foreach ($logs as $log) {
            fputcsv($output, [
                date('Y-m-d H:i:s', strtotime($log['created_at'])),
                $log['full_name'] ?? $log['username'] ?? 'N/A',
                $log['action'] ?? 'N/A',
                $log['module'] ?? 'N/A',
                $log['description'] ?? 'N/A'
            ]);
        }

        fclose($output);
        exit;
    }
}

