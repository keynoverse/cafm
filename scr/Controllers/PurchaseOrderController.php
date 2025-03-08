<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Inventory;

class PurchaseOrderController extends Controller
{
    private $purchaseOrder;
    private $supplier;
    private $inventory;

    public function __construct()
    {
        parent::__construct();
        $this->purchaseOrder = new PurchaseOrder();
        $this->supplier = new Supplier();
        $this->inventory = new Inventory();
    }

    public function index()
    {
        $this->requireAuth();

        $page = $_GET['page'] ?? 1;
        $orders = $this->purchaseOrder->getAllWithDetails($page);
        $stats = $this->purchaseOrder->getPurchaseOrderStats();

        return $this->view('purchase-orders/index', [
            'orders' => $orders,
            'stats' => $stats,
            'currentPage' => $page
        ]);
    }

    public function show($id)
    {
        $this->requireAuth();

        $order = $this->purchaseOrder->getWithDetails($id);
        
        if (!$order) {
            $this->redirect('purchase-orders');
        }

        return $this->view('purchase-orders/show', [
            'order' => $order
        ]);
    }

    public function create()
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = $this->validator->make($_POST, [
                'supplier_id' => 'required|exists:suppliers,id',
                'items' => 'required|array',
                'items.*.inventory_id' => 'required|exists:inventory,id',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
                'notes' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return $this->view('purchase-orders/create', [
                    'errors' => $validator->errors(),
                    'old' => $_POST,
                    'suppliers' => $this->supplier->getAll(),
                    'inventory' => $this->inventory->getAll()
                ]);
            }

            $data = $validator->validated();
            $data['po_number'] = 'PO-' . date('Ymd') . '-' . rand(1000, 9999);
            $data['created_by'] = $this->user['id'];
            $data['total_amount'] = $this->calculateTotalAmount($data['items']);

            if ($this->purchaseOrder->create($data)) {
                $this->flash('success', 'Purchase order created successfully.');
                $this->redirect('purchase-orders');
            }

            $this->flash('error', 'Failed to create purchase order.');
        }

        return $this->view('purchase-orders/create', [
            'suppliers' => $this->supplier->getAll(),
            'inventory' => $this->inventory->getAll()
        ]);
    }

    public function edit($id)
    {
        $this->requireAuth();

        $order = $this->purchaseOrder->getWithDetails($id);
        
        if (!$order) {
            $this->redirect('purchase-orders');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = $this->validator->make($_POST, [
                'supplier_id' => 'required|exists:suppliers,id',
                'items' => 'required|array',
                'items.*.inventory_id' => 'required|exists:inventory,id',
                'items.*.quantity' => 'required|numeric|min:1',
                'items.*.unit_price' => 'required|numeric|min:0',
                'notes' => 'nullable|string',
                'status' => 'required|in:draft,pending,approved,ordered,received,cancelled'
            ]);

            if ($validator->fails()) {
                return $this->view('purchase-orders/edit', [
                    'order' => $order,
                    'errors' => $validator->errors(),
                    'old' => $_POST,
                    'suppliers' => $this->supplier->getAll(),
                    'inventory' => $this->inventory->getAll()
                ]);
            }

            $data = $validator->validated();
            $data['total_amount'] = $this->calculateTotalAmount($data['items']);

            if ($this->purchaseOrder->update($id, $data)) {
                $this->flash('success', 'Purchase order updated successfully.');
                $this->redirect("purchase-orders/{$id}");
            }

            $this->flash('error', 'Failed to update purchase order.');
        }

        return $this->view('purchase-orders/edit', [
            'order' => $order,
            'suppliers' => $this->supplier->getAll(),
            'inventory' => $this->inventory->getAll()
        ]);
    }

    public function delete($id)
    {
        $this->requireAuth();
        $this->requireAdmin();

        if ($this->purchaseOrder->delete($id)) {
            $this->flash('success', 'Purchase order deleted successfully.');
        } else {
            $this->flash('error', 'Cannot delete purchase order. Only draft or cancelled orders can be deleted.');
        }

        $this->redirect('purchase-orders');
    }

    public function updateStatus($id)
    {
        $this->requireAuth();

        $validator = $this->validator->make($_POST, [
            'status' => 'required|in:draft,pending,approved,ordered,received,cancelled'
        ]);

        if ($validator->fails()) {
            $this->flash('error', 'Invalid status.');
            $this->redirect("purchase-orders/{$id}");
        }

        $data = $validator->validated();
        $data['approved_by'] = $data['status'] === 'approved' ? $this->user['id'] : null;

        if ($this->purchaseOrder->updateStatus($id, $data['status'], $data['approved_by'])) {
            $this->flash('success', 'Purchase order status updated successfully.');
        } else {
            $this->flash('error', 'Failed to update purchase order status.');
        }

        $this->redirect("purchase-orders/{$id}");
    }

    public function search()
    {
        $this->requireAuth();

        $query = $_GET['query'] ?? '';
        $page = $_GET['page'] ?? 1;

        $orders = $this->purchaseOrder->search($query, $page);

        return $this->json([
            'success' => true,
            'data' => $orders
        ]);
    }

    private function calculateTotalAmount($items)
    {
        $total = 0;
        foreach ($items as $item) {
            $total += $item['quantity'] * $item['unit_price'];
        }
        return $total;
    }
} 