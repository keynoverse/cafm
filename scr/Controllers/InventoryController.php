<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Inventory;
use App\Models\AssetCategory;
use App\Models\Location;
use App\Models\Supplier;

class InventoryController extends Controller
{
    private $inventory;
    private $category;
    private $location;
    private $supplier;

    public function __construct()
    {
        parent::__construct();
        $this->inventory = new Inventory();
        $this->category = new AssetCategory();
        $this->location = new Location();
        $this->supplier = new Supplier();
    }

    public function index()
    {
        $this->requireAuth();

        $page = $_GET['page'] ?? 1;
        $categoryId = $_GET['category_id'] ?? null;
        $locationId = $_GET['location_id'] ?? null;

        if ($categoryId) {
            $items = $this->inventory->getByCategory($categoryId, $page);
        } elseif ($locationId) {
            $items = $this->inventory->getByLocation($locationId, $page);
        } else {
            $items = $this->inventory->getAllWithDetails($page);
        }

        $categories = $this->category->getAll();
        $locations = $this->location->getAll();
        $suppliers = $this->supplier->getAll();
        $lowStockItems = $this->inventory->getLowStockItems();

        return $this->view('inventory/index', [
            'items' => $items,
            'categories' => $categories,
            'locations' => $locations,
            'suppliers' => $suppliers,
            'lowStockItems' => $lowStockItems,
            'currentPage' => $page,
            'filters' => [
                'category_id' => $categoryId,
                'location_id' => $locationId
            ]
        ]);
    }

    public function show($id)
    {
        $this->requireAuth();

        $item = $this->inventory->getWithDetails($id);
        
        if (!$item) {
            $this->redirect('inventory');
        }

        return $this->view('inventory/show', [
            'item' => $item
        ]);
    }

    public function create()
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = $this->validator->make($_POST, [
                'name' => 'required|min:3',
                'sku' => 'required|unique:inventory,sku',
                'category_id' => 'exists:asset_categories,id',
                'quantity' => 'required|numeric|min:0',
                'min_quantity' => 'required|numeric|min:0',
                'unit' => 'required',
                'price' => 'required|numeric|min:0',
                'supplier_id' => 'exists:suppliers,id',
                'location_id' => 'exists:locations,id',
                'description' => 'required|min:10'
            ]);

            if ($validator->fails()) {
                return $this->view('inventory/create', [
                    'errors' => $validator->errors(),
                    'old' => $_POST,
                    'categories' => $this->category->getAll(),
                    'locations' => $this->location->getAll(),
                    'suppliers' => $this->supplier->getAll()
                ]);
            }

            $data = $validator->validated();
            $data['created_by'] = $_SESSION['user_id'];

            if ($this->inventory->create($data)) {
                $this->flash('success', 'Inventory item created successfully.');
                $this->redirect('inventory');
            }

            $this->flash('error', 'Failed to create inventory item.');
        }

        return $this->view('inventory/create', [
            'categories' => $this->category->getAll(),
            'locations' => $this->location->getAll(),
            'suppliers' => $this->supplier->getAll()
        ]);
    }

    public function edit($id)
    {
        $this->requireAuth();

        $item = $this->inventory->getWithDetails($id);
        
        if (!$item) {
            $this->redirect('inventory');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validator = $this->validator->make($_POST, [
                'name' => 'required|min:3',
                'sku' => "required|unique:inventory,sku,{$id}",
                'category_id' => 'exists:asset_categories,id',
                'quantity' => 'required|numeric|min:0',
                'min_quantity' => 'required|numeric|min:0',
                'unit' => 'required',
                'price' => 'required|numeric|min:0',
                'supplier_id' => 'exists:suppliers,id',
                'location_id' => 'exists:locations,id',
                'description' => 'required|min:10'
            ]);

            if ($validator->fails()) {
                return $this->view('inventory/edit', [
                    'item' => $item,
                    'errors' => $validator->errors(),
                    'old' => $_POST,
                    'categories' => $this->category->getAll(),
                    'locations' => $this->location->getAll(),
                    'suppliers' => $this->supplier->getAll()
                ]);
            }

            $data = $validator->validated();

            if ($this->inventory->update($id, $data)) {
                $this->flash('success', 'Inventory item updated successfully.');
                $this->redirect("inventory/{$id}");
            }

            $this->flash('error', 'Failed to update inventory item.');
        }

        return $this->view('inventory/edit', [
            'item' => $item,
            'categories' => $this->category->getAll(),
            'locations' => $this->location->getAll(),
            'suppliers' => $this->supplier->getAll()
        ]);
    }

    public function delete($id)
    {
        $this->requireAuth();
        $this->requireAdmin();

        if ($this->inventory->delete($id)) {
            $this->flash('success', 'Inventory item deleted successfully.');
        } else {
            $this->flash('error', 'Failed to delete inventory item.');
        }

        $this->redirect('inventory');
    }

    public function updateQuantity($id)
    {
        $this->requireAuth();

        $validator = $this->validator->make($_POST, [
            'quantity' => 'required|numeric|min:1',
            'type' => 'required|in:add,remove'
        ]);

        if ($validator->fails()) {
            return $this->json([
                'success' => false,
                'message' => 'Invalid input data'
            ]);
        }

        $data = $validator->validated();

        if ($this->inventory->updateQuantity($id, $data['quantity'], $data['type'])) {
            return $this->json(['success' => true]);
        }

        return $this->json([
            'success' => false,
            'message' => 'Failed to update quantity'
        ]);
    }

    public function search()
    {
        $this->requireAuth();

        $query = $_GET['query'] ?? '';
        $page = $_GET['page'] ?? 1;

        $items = $this->inventory->search($query, $page);

        return $this->json([
            'success' => true,
            'data' => $items
        ]);
    }

    public function getLowStockItems()
    {
        $this->requireAuth();

        $items = $this->inventory->getLowStockItems();

        return $this->json([
            'success' => true,
            'data' => $items
        ]);
    }
} 