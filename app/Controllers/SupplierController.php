<?php

namespace App\Controllers;

use App\Models\SupplierModel;
use App\Models\UserModel;
use App\Models\ActivityLogModel;
use App\Models\SupplierProductModel;
use App\Models\ProductModel;

class SupplierController extends BaseController
{
    protected $supplierModel;
    protected $userModel;
    protected $activityLogModel;
    protected $supplierProductModel;
    protected $productModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
        $this->userModel = new UserModel();
        $this->activityLogModel = new ActivityLogModel();
        $this->supplierProductModel = new SupplierProductModel();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Allow central_admin, central_admin, supplier, and franchise_manager roles to access suppliers management
        if (!$this->checkRoleAccess(['central_admin', 'central_admin', 'supplier', 'franchise_manager'])) {
            return $this->unauthorized('Only administrators, suppliers, and franchise managers can access supplier management');
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

        $suppliers = $builder->orderBy('created_at', 'DESC')->findAll();
        
        // Get user accounts linked to suppliers
        $supplierUsers = $this->userModel->where('role', 'supplier')->where('supplier_id IS NOT NULL')->findAll();
        $supplierUserMap = [];
        foreach ($supplierUsers as $user) {
            $supplierUserMap[$user['supplier_id']] = $user;
        }
        
        // Add user account info to each supplier
        foreach ($suppliers as &$supplier) {
            $supplier['has_account'] = isset($supplierUserMap[$supplier['id']]);
            $supplier['user_account'] = $supplierUserMap[$supplier['id']] ?? null;
        }
        
        $data['suppliers'] = $suppliers;
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

        // Allow central_admin, central_admin, supplier, and franchise_manager roles to create suppliers
        if (!$this->checkRoleAccess(['central_admin', 'central_admin', 'supplier', 'franchise_manager'])) {
            return $this->unauthorized('Only administrators, suppliers, and franchise managers can create suppliers');
        }

        return view('suppliers/create');
    }

    public function store()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Allow central_admin, central_admin, supplier, and franchise_manager roles to store suppliers
        if (!$this->checkRoleAccess(['central_admin', 'central_admin', 'supplier', 'franchise_manager'])) {
            return $this->unauthorized('Only administrators, suppliers, and franchise managers can create suppliers');
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

        $supplierId = $this->supplierModel->insert($data);
        
        if ($supplierId) {
            // Check if user account should be created
            $createAccount = $this->request->getPost('create_account');
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            
            if ($createAccount && $username && $password) {
                // Check if username already exists
                $existingUser = $this->userModel->where('username', $username)->first();
                if ($existingUser) {
                    return redirect()->to('/suppliers')->with('warning', 'Supplier created but username already exists. Please create user account manually.');
                }
                
                // Create supplier user account
                $userData = [
                    'username' => $username,
                    'email' => $data['email'],
                    'password' => password_hash($password, PASSWORD_DEFAULT),
                    'full_name' => $data['contact_person'] ?: $data['name'],
                    'role' => 'supplier',
                    'supplier_id' => $supplierId,
                    'status' => 'active',
                ];
                
                $this->userModel->insert($userData);
                $this->activityLogModel->logActivity($session->get('user_id'), 'create', 'supplier', 'Created supplier: ' . $data['name'] . ' with user account: ' . $username);
                return redirect()->to('/suppliers')->with('success', 'Supplier and user account created successfully');
            }
            
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

        // Allow central_admin, central_admin, supplier, and franchise_manager roles to edit suppliers
        if (!$this->checkRoleAccess(['central_admin', 'central_admin', 'supplier', 'franchise_manager'])) {
            return $this->unauthorized('Only administrators, suppliers, and franchise managers can edit suppliers');
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

        // Allow central_admin, central_admin, supplier, and franchise_manager roles to update suppliers
        if (!$this->checkRoleAccess(['central_admin', 'central_admin', 'supplier', 'franchise_manager'])) {
            return $this->unauthorized('Only administrators, suppliers, and franchise managers can update suppliers');
        }

        // Check if supplier exists
        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            return redirect()->to('/suppliers')->with('error', 'Supplier not found');
        }

        // Check if code is unique (excluding current supplier)
        $code = $this->request->getPost('code');
        $existingSupplier = $this->supplierModel->where('code', $code)->where('id !=', $id)->first();
        if ($existingSupplier) {
            return redirect()->to('/suppliers')->with('error', 'Supplier code already exists');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'code' => $code,
            'contact_person' => $this->request->getPost('contact_person'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'payment_terms' => $this->request->getPost('payment_terms'),
            'delivery_terms' => $this->request->getPost('delivery_terms'),
            'status' => $this->request->getPost('status'),
        ];

        try {
            if ($this->supplierModel->update($id, $data)) {
                $this->activityLogModel->logActivity($session->get('user_id'), 'update', 'supplier', 'Updated supplier ID: ' . $id);
                return redirect()->to('/suppliers')->with('success', 'Supplier updated successfully');
            } else {
                $errors = $this->supplierModel->errors();
                $errorMsg = !empty($errors) ? implode(', ', $errors) : 'Failed to update supplier';
                return redirect()->to('/suppliers')->with('error', $errorMsg);
            }
        } catch (\Exception $e) {
            log_message('error', 'Supplier update error: ' . $e->getMessage());
            return redirect()->to('/suppliers')->with('error', 'Failed to update supplier: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Only central_admin can delete suppliers
        if (!$this->checkRoleAccess(['central_admin'])) {
            return $this->unauthorized('Only administrators can delete suppliers');
        }

        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            return redirect()->to('/suppliers')->with('error', 'Supplier not found');
        }

        if ($this->supplierModel->delete($id)) {
            $this->activityLogModel->logActivity($session->get('user_id'), 'delete', 'supplier', 'Deleted supplier: ' . $supplier['name']);
            return redirect()->to('/suppliers')->with('success', 'Supplier deleted successfully');
        } else {
            return redirect()->back()->with('error', 'Failed to delete supplier');
        }
    }

    public function createAccount()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // Only central_admin can create supplier accounts
        if (!$this->checkRoleAccess(['central_admin'])) {
            return $this->unauthorized('Only administrators can create supplier accounts');
        }

        $supplierId = $this->request->getPost('supplier_id');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $email = $this->request->getPost('email');
        $fullName = $this->request->getPost('full_name');

        // Validate supplier exists
        $supplier = $this->supplierModel->find($supplierId);
        if (!$supplier) {
            return redirect()->to('/suppliers')->with('error', 'Supplier not found');
        }

        // Check if supplier already has an account
        $existingAccount = $this->userModel->where('supplier_id', $supplierId)->first();
        if ($existingAccount) {
            return redirect()->to('/suppliers')->with('error', 'Supplier already has a login account');
        }

        // Check if username already exists
        $existingUser = $this->userModel->where('username', $username)->first();
        if ($existingUser) {
            return redirect()->to('/suppliers')->with('error', 'Username already exists. Please choose a different username.');
        }

        // Create user account
        $userData = [
            'username' => $username,
            'email' => $email ?: $supplier['email'],
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'full_name' => $fullName ?: $supplier['contact_person'] ?: $supplier['name'],
            'role' => 'supplier',
            'supplier_id' => $supplierId,
            'status' => 'active',
        ];

        if ($this->userModel->insert($userData)) {
            $this->activityLogModel->logActivity($session->get('user_id'), 'create', 'user', 'Created supplier account for: ' . $supplier['name'] . ' (username: ' . $username . ')');
            return redirect()->to('/suppliers')->with('success', 'Login account created successfully for ' . $supplier['name']);
        } else {
            return redirect()->to('/suppliers')->with('error', 'Failed to create login account');
        }
    }

    /**
     * View supplier products
     */
    public function products($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        
        // Suppliers can only view their own products
        if ($role === 'supplier') {
            $supplierId = $session->get('supplier_id');
            if (!$supplierId) {
                $user = $this->userModel->find($session->get('user_id'));
                $supplierId = $user['supplier_id'] ?? null;
            }
            if ($supplierId != $id) {
                return redirect()->to('/suppliers')->with('error', 'Unauthorized access');
            }
        }

        $supplier = $this->supplierModel->find($id);
        if (!$supplier) {
            return redirect()->to('/suppliers')->with('error', 'Supplier not found');
        }

        $data['supplier'] = $supplier;
        // Get ALL products for this supplier (both admin-created and supplier-created)
        $data['products'] = $this->supplierProductModel
            ->where('supplier_id', $id)
            ->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();
        $data['available_products'] = $this->supplierProductModel->getUnassignedProducts($id);
        $data['role'] = $role;
        
        // Get categories for dropdown
        $categoryModel = new \App\Models\CategoryModel();
        $data['categories'] = $categoryModel->getActiveCategories();

        return view('suppliers/products', $data);
    }

    /**
     * Add product to supplier
     */
    public function addProduct()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (!$this->checkRoleAccess(['central_admin', 'supplier'])) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $supplierId = $this->request->getPost('supplier_id');
        $productId = $this->request->getPost('product_id');
        $supplierPrice = $this->request->getPost('supplier_price');
        $minOrderQty = $this->request->getPost('min_order_qty') ?: 1;
        $leadTimeDays = $this->request->getPost('lead_time_days');

        // Suppliers can only add to their own catalog
        $role = $session->get('role');
        if ($role === 'supplier') {
            $userSupplierId = $session->get('supplier_id');
            if (!$userSupplierId) {
                $user = $this->userModel->find($session->get('user_id'));
                $userSupplierId = $user['supplier_id'] ?? null;
            }
            if ($userSupplierId != $supplierId) {
                return redirect()->back()->with('error', 'Unauthorized');
            }
        }

        $result = $this->supplierProductModel->addProductToSupplier($supplierId, $productId, [
            'supplier_price' => $supplierPrice ?: null,
            'min_order_qty' => $minOrderQty,
            'lead_time_days' => $leadTimeDays ?: null,
        ]);

        if ($result) {
            $this->activityLogModel->logActivity($session->get('user_id'), 'create', 'supplier_product', "Added product ID: $productId to supplier ID: $supplierId");
            return redirect()->back()->with('success', 'Product added to supplier catalog');
        }

        return redirect()->back()->with('error', 'Failed to add product');
    }

    /**
     * Update supplier product details
     */
    public function updateProduct($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (!$this->checkRoleAccess(['central_admin', 'supplier'])) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $supplierProduct = $this->supplierProductModel->find($id);
        if (!$supplierProduct) {
            return redirect()->back()->with('error', 'Record not found');
        }

        // Suppliers can only update their own products
        $role = $session->get('role');
        if ($role === 'supplier') {
            $userSupplierId = $session->get('supplier_id');
            if (!$userSupplierId) {
                $user = $this->userModel->find($session->get('user_id'));
                $userSupplierId = $user['supplier_id'] ?? null;
            }
            if ($userSupplierId != $supplierProduct['supplier_id']) {
                return redirect()->back()->with('error', 'Unauthorized');
            }
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'sku' => $this->request->getPost('sku'),
            'category' => $this->request->getPost('category'),
            'unit' => $this->request->getPost('unit') ?: 'pcs',
            'supplier_price' => $this->request->getPost('supplier_price') ?: null,
            'stock_quantity' => $this->request->getPost('stock_quantity') ?: 0,
            'min_order_qty' => $this->request->getPost('min_order_qty') ?: 1,
            'lead_time_days' => $this->request->getPost('lead_time_days') ?: null,
        ];

        // Check if SKU already exists for this supplier (excluding current product)
        $sku = $this->request->getPost('sku');
        if (!empty($sku) && $this->supplierProductModel->supplierHasProductSku($supplierProduct['supplier_id'], $sku, $id)) {
            return redirect()->back()->withInput()->with('error', 'SKU already exists for this supplier');
        }

        if ($this->supplierProductModel->update($id, $data)) {
            $this->activityLogModel->logActivity($session->get('user_id'), 'update', 'supplier_product', "Updated supplier product ID: $id");
            return redirect()->back()->with('success', 'Product details updated');
        }

        return redirect()->back()->with('error', 'Failed to update product');
    }

    /**
     * Remove product from supplier
     */
    public function removeProduct($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (!$this->checkRoleAccess(['central_admin', 'supplier'])) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $supplierProduct = $this->supplierProductModel->find($id);
        if (!$supplierProduct) {
            return redirect()->back()->with('error', 'Record not found');
        }

        // Suppliers can only remove their own products
        $role = $session->get('role');
        if ($role === 'supplier') {
            $userSupplierId = $session->get('supplier_id');
            if (!$userSupplierId) {
                $user = $this->userModel->find($session->get('user_id'));
                $userSupplierId = $user['supplier_id'] ?? null;
            }
            if ($userSupplierId != $supplierProduct['supplier_id']) {
                return redirect()->back()->with('error', 'Unauthorized');
            }
        }

        if ($this->supplierProductModel->delete($id)) {
            $this->activityLogModel->logActivity($session->get('user_id'), 'delete', 'supplier_product', "Removed product from supplier ID: {$supplierProduct['supplier_id']}");
            return redirect()->back()->with('success', 'Product removed from supplier catalog');
        }

        return redirect()->back()->with('error', 'Failed to remove product');
    }

    /**
     * API: Get products by supplier (for AJAX calls)
     * Returns supplier's own products for PO creation
     * Now filters by user_id to get products created by specific supplier user
     */
    public function getProductsJson($supplierId)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        // Get the user_id for this supplier to filter their products
        // Find the supplier user account linked to this supplier_id
        $supplierUser = $this->userModel->where('supplier_id', $supplierId)
            ->where('role', 'supplier')
            ->where('status', 'active')
            ->first();
        
        $userId = $supplierUser ? $supplierUser['id'] : null;
        
        // Get products created by this specific supplier user
        if ($userId) {
            $products = $this->supplierProductModel->getProductsByUser($userId);
        } else {
            // Fallback to supplier-wide products if no user found
            $products = $this->supplierProductModel->getProductsBySupplier($supplierId);
        }
        
        // Format products for PO creation - use supplier_product.id as the product identifier
        $formattedProducts = [];
        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product['id'], // supplier_product id
                'name' => $product['name'],
                'sku' => $product['sku'] ?? '',
                'unit' => $product['unit'] ?? 'pcs',
                'price' => $product['supplier_price'] ?? 0,
            ];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'products' => $formattedProducts
        ]);
    }

    /**
     * API: Get products by user ID (for AJAX calls)
     * Returns products created by a specific supplier user
     */
    public function getProductsByUserJson($userId)
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return $this->response->setJSON(['error' => 'Unauthorized'])->setStatusCode(401);
        }

        // Verify the user exists and is a supplier
        $supplierUser = $this->userModel->find($userId);
        if (!$supplierUser || $supplierUser['role'] !== 'supplier') {
            return $this->response->setJSON(['error' => 'Invalid supplier user'])->setStatusCode(400);
        }

        // Get products created by this specific user
        $products = $this->supplierProductModel->getProductsByUser($userId);
        
        // Format products for selection with stock quantity
        $formattedProducts = [];
        foreach ($products as $product) {
            $formattedProducts[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'sku' => $product['sku'] ?? '',
                'unit' => $product['unit'] ?? 'pcs',
                'price' => $product['supplier_price'] ?? 0,
                'stock' => $product['stock_quantity'] ?? 0,
            ];
        }
        
        return $this->response->setJSON([
            'success' => true,
            'products' => $formattedProducts
        ]);
    }

    /**
     * My Products - For logged-in suppliers to manage their own product catalog
     */
    public function myProducts()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        $role = $session->get('role');
        if ($role !== 'supplier') {
            return redirect()->to('/dashboard')->with('error', 'Access denied');
        }

        // Get supplier_id from session or user record
        $supplierId = $session->get('supplier_id');
        $userId = $session->get('user_id');
        
        if (!$supplierId) {
            $user = $this->userModel->find($userId);
            $supplierId = $user['supplier_id'] ?? null;
            if ($supplierId) {
                $session->set('supplier_id', $supplierId);
            }
        }

        if (!$supplierId) {
            return redirect()->to('/dashboard')->with('error', 'Supplier account not properly configured');
        }

        $supplier = $this->supplierModel->find($supplierId);
        if (!$supplier) {
            return redirect()->to('/dashboard')->with('error', 'Supplier not found');
        }

        $data['supplier'] = $supplier;
        // Get ALL products for this supplier (both admin-created and supplier-created)
        $data['products'] = $this->supplierProductModel
            ->where('supplier_id', $supplierId)
            ->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();
        $data['role'] = $role;
        $data['is_own_catalog'] = true; // Flag to indicate this is supplier's own view
        
        // Get categories for dropdown
        $categoryModel = new \App\Models\CategoryModel();
        $data['categories'] = $categoryModel->getActiveCategories();

        return view('suppliers/my_products', $data);
    }

    /**
     * Store new product for supplier
     */
    public function storeMyProduct()
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'supplier') {
            return redirect()->to('/login');
        }

        $supplierId = $this->getSupplierIdFromSession($session);
        $userId = $session->get('user_id');
        
        if (!$supplierId) {
            return redirect()->to('/dashboard')->with('error', 'Supplier account not configured');
        }

        // Auto-generate SKU if not provided
        $sku = $this->request->getPost('sku');
        if (empty($sku)) {
            $sku = $this->supplierProductModel->generateSku($supplierId);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'sku' => $sku,
            'description' => $this->request->getPost('description'),
            'category' => $this->request->getPost('category'),
            'unit' => $this->request->getPost('unit') ?: 'pcs',
            'supplier_price' => $this->request->getPost('supplier_price'),
            'stock_quantity' => $this->request->getPost('stock_quantity') ?: 0,
            'min_order_qty' => $this->request->getPost('min_order_qty') ?: 1,
            'lead_time_days' => $this->request->getPost('lead_time_days'),
        ];

        // Check if SKU already exists - regenerate if duplicate
        if ($this->supplierProductModel->supplierHasProductSku($supplierId, $data['sku'], null, $userId)) {
            $data['sku'] = $this->supplierProductModel->generateSku($supplierId);
        }

        // Pass userId to track who created the product
        if ($this->supplierProductModel->createSupplierProduct($supplierId, $data, $userId)) {
            $this->activityLogModel->logActivity($userId, 'create', 'supplier_product', 'Added product: ' . $data['name']);
            return redirect()->to('/supplier/my-products')->with('success', 'Product added successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to add product');
    }

    /**
     * Update supplier's product
     */
    public function updateMyProduct($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'supplier') {
            return redirect()->to('/login');
        }

        $supplierId = $this->getSupplierIdFromSession($session);
        $userId = $session->get('user_id');
        
        if (!$supplierId) {
            return redirect()->to('/dashboard')->with('error', 'Supplier account not configured');
        }

        // Verify product belongs to this supplier (check supplier_id, and created_by if set)
        $product = $this->supplierProductModel->find($id);
        if (!$product || $product['supplier_id'] != $supplierId) {
            return redirect()->to('/supplier/my-products')->with('error', 'Product not found');
        }
        
        // If created_by is set, verify it matches current user
        if (!empty($product['created_by']) && $product['created_by'] != $userId) {
            return redirect()->to('/supplier/my-products')->with('error', 'You can only edit your own products');
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'sku' => $this->request->getPost('sku'),
            'description' => $this->request->getPost('description'),
            'category' => $this->request->getPost('category'),
            'unit' => $this->request->getPost('unit') ?: 'pcs',
            'supplier_price' => $this->request->getPost('supplier_price'),
            'stock_quantity' => $this->request->getPost('stock_quantity') ?: 0,
            'min_order_qty' => $this->request->getPost('min_order_qty') ?: 1,
            'lead_time_days' => $this->request->getPost('lead_time_days'),
        ];

        // Check if SKU already exists for this user (excluding current product)
        if ($this->supplierProductModel->supplierHasProductSku($supplierId, $data['sku'], $id, $userId)) {
            return redirect()->back()->withInput()->with('error', 'SKU already exists in your catalog');
        }

        if ($this->supplierProductModel->updateSupplierProduct($id, $data)) {
            $this->activityLogModel->logActivity($userId, 'update', 'supplier_product', 'Updated product: ' . $data['name']);
            return redirect()->to('/supplier/my-products')->with('success', 'Product updated successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to update product');
    }

    /**
     * Delete supplier's product
     */
    public function deleteMyProduct($id)
    {
        $session = session();
        if (!$session->get('isLoggedIn') || $session->get('role') !== 'supplier') {
            return redirect()->to('/login');
        }

        $supplierId = $this->getSupplierIdFromSession($session);
        $userId = $session->get('user_id');
        
        if (!$supplierId) {
            return redirect()->to('/dashboard')->with('error', 'Supplier account not configured');
        }

        // Verify product belongs to this supplier (check supplier_id, and created_by if set)
        $product = $this->supplierProductModel->find($id);
        if (!$product || $product['supplier_id'] != $supplierId) {
            return redirect()->to('/supplier/my-products')->with('error', 'Product not found');
        }
        
        // If created_by is set, verify it matches current user
        if (!empty($product['created_by']) && $product['created_by'] != $userId) {
            return redirect()->to('/supplier/my-products')->with('error', 'You can only delete your own products');
        }

        if ($this->supplierProductModel->removeProduct($id)) {
            $this->activityLogModel->logActivity($userId, 'delete', 'supplier_product', 'Deleted product: ' . $product['name']);
            return redirect()->to('/supplier/my-products')->with('success', 'Product deleted successfully');
        }

        return redirect()->back()->with('error', 'Failed to delete product');
    }

    /**
     * Store new product for supplier (admin view)
     */
    public function storeProduct()
    {
        $session = session();
        if (!$session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        if (!$this->checkRoleAccess(['central_admin', 'supplier'])) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        $supplierId = $this->request->getPost('supplier_id');
        $userId = $session->get('user_id');
        $role = $session->get('role');
        
        // Suppliers can only add to their own catalog
        if ($role === 'supplier') {
            $userSupplierId = $session->get('supplier_id');
            if (!$userSupplierId) {
                $user = $this->userModel->find($userId);
                $userSupplierId = $user['supplier_id'] ?? null;
            }
            if ($userSupplierId != $supplierId) {
                return redirect()->back()->with('error', 'Unauthorized');
            }
        }

        // Auto-generate SKU if not provided
        $sku = $this->request->getPost('sku');
        if (empty($sku)) {
            $sku = $this->supplierProductModel->generateSku($supplierId);
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'sku' => $sku,
            'description' => $this->request->getPost('description'),
            'category' => $this->request->getPost('category'),
            'unit' => $this->request->getPost('unit') ?: 'pcs',
            'supplier_price' => $this->request->getPost('supplier_price'),
            'stock_quantity' => $this->request->getPost('stock_quantity') ?: 0,
            'min_order_qty' => $this->request->getPost('min_order_qty') ?: 1,
            'lead_time_days' => $this->request->getPost('lead_time_days'),
        ];

        // Check if SKU already exists for this supplier
        if ($this->supplierProductModel->supplierHasProductSku($supplierId, $data['sku'])) {
            // Regenerate SKU if duplicate
            $data['sku'] = $this->supplierProductModel->generateSku($supplierId);
        }

        // Admin creates products with NULL created_by so all supplier users can see them
        // Supplier users create with their own user_id
        $createdBy = ($role === 'central_admin') ? null : $userId;

        if ($this->supplierProductModel->createSupplierProduct($supplierId, $data, $createdBy)) {
            $this->activityLogModel->logActivity($userId, 'create', 'supplier_product', 'Added product: ' . $data['name'] . ' to supplier ID: ' . $supplierId);
            return redirect()->back()->with('success', 'Product added successfully');
        }

        return redirect()->back()->withInput()->with('error', 'Failed to add product');
    }

    /**
     * Helper to get supplier_id from session
     */
    private function getSupplierIdFromSession($session)
    {
        $supplierId = $session->get('supplier_id');
        if (!$supplierId) {
            $user = $this->userModel->find($session->get('user_id'));
            $supplierId = $user['supplier_id'] ?? null;
            if ($supplierId) {
                $session->set('supplier_id', $supplierId);
            }
        }
        return $supplierId;
    }
}

