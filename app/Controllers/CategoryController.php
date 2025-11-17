<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ActivityLogModel;

class CategoryController extends BaseController
{
    protected $categoryModel;
    protected $activityLogModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $builder = $this->categoryModel;

        // Search functionality
        $search = $this->request->getGet('search');
        if ($search) {
            $builder->groupStart()
                ->like('name', $search)
                ->orLike('description', $search)
                ->groupEnd();
        }

        // Filter by status
        $status = $this->request->getGet('status');
        if ($status) {
            $builder->where('status', $status);
        }

        $data['categories'] = $builder->orderBy('name', 'ASC')->findAll();
        $data['search'] = $search;
        $data['status'] = $status;
        $data['role'] = $session->get('role');

        return view('categories/index', $data);
    }

    public function create()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        return view('categories/create');
    }

    public function store()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $rules = [
            'name' => 'required|min_length[2]|max_length[100]|is_unique[categories.name]',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description') ?: null,
            'status' => $this->request->getPost('status') ?: 'active',
        ];

        try {
            if ($this->categoryModel->insert($data)) {
                $this->activityLogModel->logActivity($session->get('user_id'), 'create', 'category', 'Created category: ' . $data['name']);
                return redirect()->to('/categories')->with('success', 'Category created successfully');
            } else {
                $errors = $this->categoryModel->errors();
                return redirect()->back()->withInput()->with('error', 'Failed to create category: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data['category'] = $this->categoryModel->find($id);
        if (!$data['category']) {
            return redirect()->to('/categories')->with('error', 'Category not found');
        }

        return view('categories/edit', $data);
    }

    public function update($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $category = $this->categoryModel->find($id);
        if (!$category) {
            return redirect()->to('/categories')->with('error', 'Category not found');
        }

        $rules = [
            'name' => "required|min_length[2]|max_length[100]|is_unique[categories.name,id,$id]",
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description') ?: null,
            'status' => $this->request->getPost('status') ?: 'active',
        ];

        try {
            if ($this->categoryModel->update($id, $data)) {
                $this->activityLogModel->logActivity($session->get('user_id'), 'update', 'category', 'Updated category: ' . $data['name']);
                return redirect()->to('/categories')->with('success', 'Category updated successfully');
            } else {
                $errors = $this->categoryModel->errors();
                return redirect()->back()->withInput()->with('error', 'Failed to update category: ' . implode(', ', $errors));
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $category = $this->categoryModel->find($id);
        if (!$category) {
            return redirect()->to('/categories')->with('error', 'Category not found');
        }

        try {
            $categoryName = $category['name'];
            if ($this->categoryModel->delete($id)) {
                $this->activityLogModel->logActivity($session->get('user_id'), 'delete', 'category', 'Deleted category: ' . $categoryName);
                return redirect()->to('/categories')->with('success', 'Category deleted successfully');
            } else {
                return redirect()->to('/categories')->with('error', 'Failed to delete category');
            }
        } catch (\Exception $e) {
            return redirect()->to('/categories')->with('error', 'Error: ' . $e->getMessage());
        }
    }
}

