<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\ActivityLogModel;

class ProductController extends BaseController
{
    protected $productModel;
    protected $activityLogModel;

    public function __construct()
    {
        $this->productModel = new ProductModel();
        $this->activityLogModel = new ActivityLogModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data['products'] = $this->productModel->orderBy('created_at', 'DESC')->findAll();
        $data['role'] = $session->get('role');

        return view('products/index', $data);
    }

    public function create()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        return view('products/create');
    }

    public function store()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[200]',
            'sku' => 'required|min_length[3]|max_length[100]|is_unique[products.sku]',
            'barcode' => 'permit_empty|max_length[100]',
        ];

        if (!$this->validate($rules)) {
            $validation = \Config\Services::validation();
            return redirect()->back()->withInput()->with('error', implode('<br>', $validation->getErrors()));
        }
        
        // Check barcode uniqueness if provided
        $barcode = $this->request->getPost('barcode');
        if (!empty($barcode)) {
            $existingBarcode = $this->productModel->where('barcode', $barcode)->first();
            if ($existingBarcode) {
                return redirect()->back()->withInput()->with('error', 'Barcode already exists. Please use a different barcode.');
            }
        }
        
        // Check SKU uniqueness (double check)
        $sku = $this->request->getPost('sku');
        $existingSku = $this->productModel->where('sku', $sku)->first();
        if ($existingSku) {
            return redirect()->back()->withInput()->with('error', 'SKU already exists. Please use a different SKU.');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'sku' => $this->request->getPost('sku'),
            'barcode' => $this->request->getPost('barcode') ?: null,
            'description' => $this->request->getPost('description') ?: null,
            'category' => $this->request->getPost('category') ?: null,
            'unit' => $this->request->getPost('unit') ?: 'pcs',
            'is_perishable' => $this->request->getPost('is_perishable') ? 1 : 0,
            'shelf_life_days' => $this->request->getPost('shelf_life_days') ?: null,
            'min_stock_level' => $this->request->getPost('min_stock_level') ?: 10,
            'max_stock_level' => $this->request->getPost('max_stock_level') ?: null,
            'cost_price' => $this->request->getPost('cost_price') ?: 0,
            'selling_price' => $this->request->getPost('selling_price') ?: 0,
            'status' => $this->request->getPost('status') ?: 'active',
        ];

        try {
            if ($this->productModel->insert($data)) {
                $this->activityLogModel->logActivity($session->get('user_id'), 'create', 'product', 'Created product: ' . $data['name']);
                return redirect()->to('/products')->with('success', 'Product created successfully');
            } else {
                $errors = $this->productModel->errors();
                return redirect()->back()->withInput()->with('error', 'Failed to create product: ' . implode(', ', $errors));
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

        $data['product'] = $this->productModel->find($id);
        if (!$data['product']) {
            return redirect()->to('/products')->with('error', 'Product not found');
        }

        return view('products/edit', $data);
    }

    public function update($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'sku' => $this->request->getPost('sku'),
            'barcode' => $this->request->getPost('barcode'),
            'description' => $this->request->getPost('description'),
            'category' => $this->request->getPost('category'),
            'unit' => $this->request->getPost('unit'),
            'is_perishable' => $this->request->getPost('is_perishable') ? 1 : 0,
            'shelf_life_days' => $this->request->getPost('shelf_life_days'),
            'min_stock_level' => $this->request->getPost('min_stock_level'),
            'max_stock_level' => $this->request->getPost('max_stock_level'),
            'cost_price' => $this->request->getPost('cost_price'),
            'selling_price' => $this->request->getPost('selling_price'),
            'status' => $this->request->getPost('status'),
        ];

        if ($this->productModel->update($id, $data)) {
            $this->activityLogModel->logActivity($session->get('user_id'), 'update', 'product', 'Updated product ID: ' . $id);
            return redirect()->to('/products')->with('success', 'Product updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update product');
        }
    }

    public function delete($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if ($this->productModel->delete($id)) {
            $this->activityLogModel->logActivity($session->get('user_id'), 'delete', 'product', 'Deleted product ID: ' . $id);
            return redirect()->to('/products')->with('success', 'Product deleted successfully');
        } else {
            return redirect()->to('/products')->with('error', 'Failed to delete product');
        }
    }
}

