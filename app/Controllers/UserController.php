<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\BranchModel;
use App\Models\SupplierModel;
use App\Models\ActivityLogModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $branchModel;
    protected $supplierModel;
    protected $activityLogModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->branchModel = new BranchModel();
        $this->supplierModel = new SupplierModel();
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }

        $builder = $this->userModel->select('users.*, branches.name as branch_name')
            ->join('branches', 'branches.id = users.branch_id', 'left')
            ->orderBy('users.created_at', 'DESC');

        // Search functionality
        $search = $this->request->getGet('search');
        if ($search) {
            $builder->groupStart()
                ->like('users.username', $search)
                ->orLike('users.full_name', $search)
                ->orLike('users.email', $search)
                ->groupEnd();
        }

        // Filter by role
        $filterRole = $this->request->getGet('role');
        if ($filterRole) {
            $builder->where('users.role', $filterRole);
        }

        // Filter by status
        $filterStatus = $this->request->getGet('status');
        if ($filterStatus) {
            $builder->where('users.status', $filterStatus);
        }

        $data['users'] = $builder->findAll();
        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['roles'] = [
            'central_admin' => 'Central Admin',
            'branch_manager' => 'Branch Manager',
            'inventory_staff' => 'Inventory Staff',
            'supplier' => 'Supplier',
            'logistics_coordinator' => 'Logistics Coordinator',
            'franchise_manager' => 'Franchise Manager',
            'driver' => 'Driver'
        ];
        $data['search'] = $search;
        $data['filterRole'] = $filterRole;
        $data['filterStatus'] = $filterStatus;

        return view('users/index', $data);
    }

    public function create()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }

        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['roles'] = [
            'central_admin' => 'Central Admin',
            'branch_manager' => 'Branch Manager',
            'inventory_staff' => 'Inventory Staff',
            'supplier' => 'Supplier',
            'logistics_coordinator' => 'Logistics Coordinator',
            'franchise_manager' => 'Franchise Manager',
            'driver' => 'Driver'
        ];

        return view('users/create', $data);
    }

    public function store()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $sessionRole = $session->get('role');
        if ($sessionRole !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }

        $role = $this->request->getPost('role');
        $branchId = $this->request->getPost('branch_id');
        
        $rules = [
            'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'full_name' => 'required|min_length[2]|max_length[150]',
            'password' => 'required|min_length[6]',
            'role' => 'required|in_list[central_admin,branch_manager,inventory_staff,supplier,logistics_coordinator,franchise_manager,driver]',
            'status' => 'required|in_list[active,inactive]'
        ];
        
        // Branch is required for branch_manager and inventory_staff
        if (in_array($role, ['branch_manager', 'inventory_staff'])) {
            $rules['branch_id'] = 'required|integer';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
        
        // Validate branch exists if provided
        if ($branchId) {
            $branch = $this->branchModel->find($branchId);
            if (!$branch) {
                return redirect()->back()->withInput()->with('errors', ['branch_id' => 'Selected branch does not exist']);
            }
        }

        $supplierId = null;
        $autoCreatedBranch = false;
        $autoCreatedSupplier = false;

        // Auto-create Supplier if role is 'supplier'
        if ($role === 'supplier') {
            $supplierData = [
                'name' => $this->request->getPost('full_name') . ' Supplies',
                'code' => 'SUP-' . strtoupper(substr(md5(time()), 0, 6)),
                'contact_person' => $this->request->getPost('full_name'),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'status' => 'active',
            ];
            $this->supplierModel->insert($supplierData);
            $supplierId = $this->supplierModel->getInsertID();
            $autoCreatedSupplier = true;
        }

        // Auto-create Branch if role is 'branch_manager' or 'franchise_manager' and no branch selected
        if (in_array($role, ['branch_manager', 'franchise_manager']) && empty($branchId)) {
            $branchData = [
                'name' => $this->request->getPost('full_name') . "'s Branch",
                'code' => 'BR-' . strtoupper(substr(md5(time()), 0, 6)),
                'email' => $this->request->getPost('email'),
                'phone' => $this->request->getPost('phone'),
                'status' => 'active',
                'is_franchise' => ($role === 'franchise_manager') ? 1 : 0,
            ];
            $this->branchModel->insert($branchData);
            $branchId = $this->branchModel->getInsertID();
            $autoCreatedBranch = true;
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'full_name' => $this->request->getPost('full_name'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'phone' => $this->request->getPost('phone'),
            'role' => $role,
            'branch_id' => $branchId ?: null,
            'supplier_id' => $supplierId,
            'status' => $this->request->getPost('status'),
        ];

        $this->userModel->insert($userData);
        $userId = $this->userModel->getInsertID();

        // Update branch manager_id if auto-created
        if ($autoCreatedBranch && $branchId) {
            $this->branchModel->update($branchId, ['manager_id' => $userId]);
        }

        // Build success message
        $successMsg = "User created successfully";
        if ($autoCreatedSupplier) {
            $successMsg .= ". Supplier account auto-created.";
        }
        if ($autoCreatedBranch) {
            $successMsg .= ". Branch auto-created.";
        }

        $this->activityLogModel->logActivity($session->get('user_id'), 'create', 'user', "Created user: {$userData['username']}");

        // Check if redirect_to is specified (from modal)
        $redirectTo = $this->request->getPost('redirect_to');
        if ($redirectTo) {
            return redirect()->to($redirectTo)->with('success', $successMsg);
        }

        return redirect()->to('/users')->with('success', $successMsg);
    }

    public function edit($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found');
        }

        $data['user'] = $user;
        $data['branches'] = $this->branchModel->where('status', 'active')->findAll();
        $data['roles'] = [
            'central_admin' => 'Central Admin',
            'branch_manager' => 'Branch Manager',
            'inventory_staff' => 'Inventory Staff',
            'supplier' => 'Supplier',
            'logistics_coordinator' => 'Logistics Coordinator',
            'franchise_manager' => 'Franchise Manager',
            'driver' => 'Driver'
        ];

        return view('users/edit', $data);
    }

    public function update($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found');
        }

        $rules = [
            'username' => "required|min_length[3]|max_length[100]|is_unique[users.username,id,{$id}]",
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'full_name' => 'required|min_length[2]|max_length[150]',
            'role' => 'required|in_list[central_admin,branch_manager,inventory_staff,supplier,logistics_coordinator,franchise_manager,driver]',
            'status' => 'required|in_list[active,inactive]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'full_name' => $this->request->getPost('full_name'),
            'phone' => $this->request->getPost('phone'),
            'role' => $this->request->getPost('role'),
            'branch_id' => $this->request->getPost('branch_id') ?: null,
            'status' => $this->request->getPost('status'),
        ];

        // Update password only if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $userData['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        $this->userModel->update($id, $userData);

        $this->activityLogModel->logActivity($session->get('user_id'), 'update', 'user', "Updated user ID: $id");

        // Check if redirect_to is specified (from modal)
        $redirectTo = $this->request->getPost('redirect_to');
        if ($redirectTo) {
            return redirect()->to($redirectTo)->with('success', 'User updated successfully');
        }

        return redirect()->to('/users')->with('success', 'User updated successfully');
    }

    public function delete($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'central_admin') {
            return redirect()->to('/dashboard')->with('error', 'Unauthorized access');
        }

        if ($id == $session->get('user_id')) {
            return redirect()->back()->with('error', 'Cannot delete your own account');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            return redirect()->to('/users')->with('error', 'User not found');
        }

        $this->userModel->delete($id);

        $this->activityLogModel->logActivity($session->get('user_id'), 'delete', 'user', "Deleted user ID: $id");

        // Check if redirect_to is specified (from modal)
        $redirectTo = $this->request->getGet('redirect_to');
        if ($redirectTo) {
            return redirect()->to($redirectTo)->with('success', 'User deleted successfully');
        }

        return redirect()->to('/users')->with('success', 'User deleted successfully');
    }
}

