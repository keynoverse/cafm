<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Supplier;

class SupplierController extends Controller
{
    private $supplier;

    public function __construct()
    {
        parent::__construct();
        $this->supplier = new Supplier();
    }

    public function index()
    {
        $this->requireAuth();

        $page = $_GET['page'] ?? 1;
        $suppliers = $this->supplier->getAllWithDetails($page);
        $stats = $this->supplier->getSupplierStats();

        return $this->view('suppliers/index', [
            'suppliers' => $suppliers,
            'stats' => $stats,
            'currentPage' => $page
        ]);
    }

    public function show($id)
    {
        $this->requireAuth();

        $supplier = $this->supplier->getWithDetails($id);
        
        if (!$supplier) {
            $this->redirect('suppliers');
        }

        $page = $_GET['page'] ?? 1;
        $inventoryItems = $this->supplier->getInventoryItems($id, $page);
        $purchaseOrders = $this->supplier->getPurchaseOrders($id, $page);

        return $this->view('suppliers/show', [
            'supplier' => $supplier,
            'inventoryItems' => $inventoryItems,
            'purchaseOrders' => $purchaseOrders,
            'currentPage' => $page
        ]);
    }

    public function create()
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = $this->validator->make($_POST, [
                'name' => 'required|min:3',
                'contact_person' => 'required|min:3',
                'email' => 'required|email',
                'phone' => 'required',
                'address' => 'required|min:10'
            ]);

            if ($validator->fails()) {
                return $this->view('suppliers/create', [
                    'errors' => $validator->errors(),
                    'old' => $_POST
                ]);
            }

            if ($this->supplier->create($validator->validated())) {
                $this->flash('success', 'Supplier created successfully.');
                $this->redirect('suppliers');
            }

            $this->flash('error', 'Failed to create supplier.');
        }

        return $this->view('suppliers/create');
    }

    public function edit($id)
    {
        $this->requireAuth();

        $supplier = $this->supplier->get($id);
        
        if (!$supplier) {
            $this->redirect('suppliers');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = $this->validator->make($_POST, [
                'name' => 'required|min:3',
                'contact_person' => 'required|min:3',
                'email' => 'required|email',
                'phone' => 'required',
                'address' => 'required|min:10'
            ]);

            if ($validator->fails()) {
                return $this->view('suppliers/edit', [
                    'supplier' => $supplier,
                    'errors' => $validator->errors(),
                    'old' => $_POST
                ]);
            }

            if ($this->supplier->update($id, $validator->validated())) {
                $this->flash('success', 'Supplier updated successfully.');
                $this->redirect("suppliers/{$id}");
            }

            $this->flash('error', 'Failed to update supplier.');
        }

        return $this->view('suppliers/edit', [
            'supplier' => $supplier
        ]);
    }

    public function delete($id)
    {
        $this->requireAuth();
        $this->requireAdmin();

        if ($this->supplier->delete($id)) {
            $this->flash('success', 'Supplier deleted successfully.');
        } else {
            $this->flash('error', 'Cannot delete supplier with associated inventory items or purchase orders.');
        }

        $this->redirect('suppliers');
    }

    public function search()
    {
        $this->requireAuth();

        $query = $_GET['query'] ?? '';
        $page = $_GET['page'] ?? 1;

        $suppliers = $this->supplier->search($query, $page);

        return $this->json([
            'success' => true,
            'data' => $suppliers
        ]);
    }
} 