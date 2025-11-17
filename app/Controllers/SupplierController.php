<?php

namespace App\Controllers;

use App\Models\SupplierModel;
use App\Models\ActivityLogModel;

class SupplierController extends BaseController
{
    protected $supplierModel;
    protected $activityLogModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $builder = $this->supplierModel;

        // Search functionality
        $search = $this->request->getGet('search');
        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('code', $search)
                ->orLike('contact_person', $search)
                ->orLike('email', $search)
                ->groupEnd();
        }

        // Filter by status
        $status = $this->request->getGet('status');
        if ($status) {
            $builder->where('status', $status);
        }

        $data['suppliers'] = $builder->orderBy('created_at', 'DESC')->findAll();
        $data['search'] = $search;
        $data['status'] = $status;
        $data['role'] = $session->get('role');

        return view('suppliers/index', $data);
    }

    public function create()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        return view('suppliers/create');
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
            'contact_person' => $this->request->getPost('contact_person'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'payment_terms' => $this->request->getPost('payment_terms'),
            'delivery_terms' => $this->request->getPost('delivery_terms'),
            'status' => $this->request->getPost('status') ?: 'active',
        ];

        if ($this->supplierModel->insert($data)) {
            $this->activityLogModel->logActivity($session->get('user_id'), 'create', 'supplier', 'Created supplier: ' . $data['name']);
            return redirect()->to('/suppliers')->with('success', 'Supplier created successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create supplier');
        }
    }

    public function edit($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data['supplier'] = $this->supplierModel->find($id);
        if (!$data['supplier']) {
            return redirect()->to('/suppliers')->with('error', 'Supplier not found');
        }

        return view('suppliers/edit', $data);
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
            'contact_person' => $this->request->getPost('contact_person'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'payment_terms' => $this->request->getPost('payment_terms'),
            'delivery_terms' => $this->request->getPost('delivery_terms'),
            'status' => $this->request->getPost('status'),
        ];

        if ($this->supplierModel->update($id, $data)) {
            $this->activityLogModel->logActivity($session->get('user_id'), 'update', 'supplier', 'Updated supplier ID: ' . $id);
            return redirect()->to('/suppliers')->with('success', 'Supplier updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update supplier');
        }
    }
}

