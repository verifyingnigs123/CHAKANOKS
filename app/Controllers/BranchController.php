<?php

namespace App\Controllers;

use App\Models\BranchModel;
use App\Models\UserModel;
use App\Models\ActivityLogModel;

class BranchController extends BaseController
{
    protected $branchModel;
    protected $userModel;
    protected $activityLogModel;

    public function __construct()
    {
        $this->branchModel = new BranchModel();
        $this->userModel = new UserModel();
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $builder = $this->branchModel->select('branches.*, users.full_name as manager_name')
            ->join('users', 'users.id = branches.manager_id', 'left');

        // Search functionality
        $search = $this->request->getGet('search');
        if ($search) {
            $builder->groupStart()
                ->like('branches.name', $search)
                ->orLike('branches.code', $search)
                ->orLike('branches.city', $search)
                ->groupEnd();
        }

        // Filter by status
        $status = $this->request->getGet('status');
        if ($status) {
            $builder->where('branches.status', $status);
        }

        $data['branches'] = $builder->orderBy('branches.created_at', 'DESC')->findAll();
        $data['search'] = $search;
        $data['status'] = $status;
        $data['role'] = $session->get('role');

        return view('branches/index', $data);
    }

    public function create()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data['managers'] = $this->userModel->whereIn('role', ['branch_manager'])->findAll();

        return view('branches/create', $data);
    }

    public function store()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'manager_id' => $this->request->getPost('manager_id'),
            'status' => $this->request->getPost('status') ?: 'active',
            'is_franchise' => $this->request->getPost('is_franchise') ? 1 : 0,
        ];

        if ($this->branchModel->insert($data)) {
            $this->activityLogModel->logActivity($session->get('user_id'), 'create', 'branch', 'Created branch: ' . $data['name']);
            return redirect()->to('/branches')->with('success', 'Branch created successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create branch');
        }
    }

    public function edit($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data['branch'] = $this->branchModel->find($id);
        if (!$data['branch']) {
            return redirect()->to('/branches')->with('error', 'Branch not found');
        }

        $data['managers'] = $this->userModel->whereIn('role', ['branch_manager'])->findAll();

        return view('branches/edit', $data);
    }

    public function update($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'address' => $this->request->getPost('address'),
            'city' => $this->request->getPost('city'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'manager_id' => $this->request->getPost('manager_id'),
            'status' => $this->request->getPost('status'),
            'is_franchise' => $this->request->getPost('is_franchise') ? 1 : 0,
        ];

        if ($this->branchModel->update($id, $data)) {
            $this->activityLogModel->logActivity($session->get('user_id'), 'update', 'branch', 'Updated branch ID: ' . $id);
            return redirect()->to('/branches')->with('success', 'Branch updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update branch');
        }
    }

    public function delete($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $branch = $this->branchModel->find($id);
        if (!$branch) {
            return redirect()->to('/branches')->with('error', 'Branch not found');
        }

        // Check if there are users assigned to this branch
        $usersWithBranch = $this->userModel->where('branch_id', $id)->countAllResults();
        if ($usersWithBranch > 0) {
            return redirect()->to('/branches')->with('error', 'Cannot delete branch. There are ' . $usersWithBranch . ' user(s) assigned to this branch. Please reassign or remove users first.');
        }

        // Delete the branch (related inventory and purchase orders will be deleted automatically due to CASCADE)
        if ($this->branchModel->delete($id)) {
            $this->activityLogModel->logActivity($session->get('user_id'), 'delete', 'branch', 'Deleted branch: ' . $branch['name']);
            return redirect()->to('/branches')->with('success', 'Branch deleted successfully');
        } else {
            return redirect()->to('/branches')->with('error', 'Failed to delete branch');
        }
    }
}

