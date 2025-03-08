<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\WorkOrder;
use App\Models\Asset;
use App\Models\Location;
use App\Models\User;

class WorkOrderController extends Controller
{
    private WorkOrder $workOrderModel;
    private Asset $assetModel;
    private Location $locationModel;
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->workOrderModel = new WorkOrder();
        $this->assetModel = new Asset();
        $this->locationModel = new Location();
        $this->userModel = new User();
    }

    public function index()
    {
        $this->requireAuth();
        
        $page = $_GET['page'] ?? 1;
        $query = $_GET['query'] ?? '';
        $perPage = 10;

        if ($query) {
            $workOrders = $this->workOrderModel->search($query, $page, $perPage);
        } else {
            $workOrders = $this->workOrderModel->getAllWithDetails($page, $perPage);
        }

        $stats = $this->workOrderModel->getWorkOrderStats();

        return $this->view('work-orders/index', [
            'workOrders' => $workOrders,
            'stats' => $stats,
            'currentPage' => $page,
            'query' => $query
        ]);
    }

    public function show($id)
    {
        $this->requireAuth();
        
        $workOrder = $this->workOrderModel->getWithDetails($id);
        if (!$workOrder) {
            return $this->redirect('/work-orders');
        }

        return $this->view('work-orders/show', [
            'workOrder' => $workOrder
        ]);
    }

    public function create()
    {
        $this->requireAuth();
        $this->requireRole(['admin', 'technician']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'priority' => $_POST['priority'],
                'status' => $_POST['status'],
                'asset_id' => $_POST['asset_id'] ?? null,
                'location_id' => $_POST['location_id'] ?? null,
                'assigned_to' => $_POST['assigned_to'] ?? null,
                'due_date' => $_POST['due_date'] ?? null,
                'estimated_cost' => $_POST['estimated_cost'] ?? null,
                'actual_cost' => $_POST['actual_cost'] ?? null,
                'created_by' => $this->user['id']
            ];

            // Validate input
            $errors = $this->validateWorkOrder($data);
            if (!empty($errors)) {
                $assets = $this->assetModel->getActiveAssets();
                $locations = $this->locationModel->getActiveLocations();
                $users = $this->userModel->getTechnicians();
                
                return $this->view('work-orders/create', [
                    'assets' => $assets,
                    'locations' => $locations,
                    'users' => $users,
                    'errors' => $errors,
                    'old' => $_POST
                ]);
            }

            if ($this->workOrderModel->create($data)) {
                $this->flash('success', 'Work order created successfully.');
                $this->redirect('/work-orders');
            } else {
                $this->flash('error', 'Failed to create work order.');
            }
        }

        $assets = $this->assetModel->getActiveAssets();
        $locations = $this->locationModel->getActiveLocations();
        $users = $this->userModel->getTechnicians();

        return $this->view('work-orders/create', [
            'assets' => $assets,
            'locations' => $locations,
            'users' => $users
        ]);
    }

    public function edit($id)
    {
        $this->requireAuth();
        $this->requireRole(['admin', 'technician']);

        $workOrder = $this->workOrderModel->getWithDetails($id);
        if (!$workOrder) {
            return $this->redirect('/work-orders');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'],
                'description' => $_POST['description'],
                'priority' => $_POST['priority'],
                'status' => $_POST['status'],
                'asset_id' => $_POST['asset_id'] ?? null,
                'location_id' => $_POST['location_id'] ?? null,
                'assigned_to' => $_POST['assigned_to'] ?? null,
                'due_date' => $_POST['due_date'] ?? null,
                'estimated_cost' => $_POST['estimated_cost'] ?? null,
                'actual_cost' => $_POST['actual_cost'] ?? null
            ];

            // Validate input
            $errors = $this->validateWorkOrder($data);
            if (!empty($errors)) {
                $assets = $this->assetModel->getActiveAssets();
                $locations = $this->locationModel->getActiveLocations();
                $users = $this->userModel->getTechnicians();
                
                return $this->view('work-orders/edit', [
                    'workOrder' => $workOrder,
                    'assets' => $assets,
                    'locations' => $locations,
                    'users' => $users,
                    'errors' => $errors,
                    'old' => $_POST
                ]);
            }

            if ($this->workOrderModel->update($id, $data)) {
                $this->flash('success', 'Work order updated successfully.');
                $this->redirect("/work-orders/$id");
            } else {
                $this->flash('error', 'Failed to update work order.');
            }
        }

        $assets = $this->assetModel->getActiveAssets();
        $locations = $this->locationModel->getActiveLocations();
        $users = $this->userModel->getTechnicians();

        return $this->view('work-orders/edit', [
            'workOrder' => $workOrder,
            'assets' => $assets,
            'locations' => $locations,
            'users' => $users
        ]);
    }

    public function delete($id)
    {
        $this->requireAuth();
        $this->requireRole('admin');

        $workOrder = $this->workOrderModel->getWithDetails($id);
        if (!$workOrder) {
            return $this->redirect('/work-orders');
        }

        if ($this->workOrderModel->delete($id)) {
            $this->flash('success', 'Work order deleted successfully.');
        } else {
            $this->flash('error', 'Failed to delete work order.');
        }

        $this->redirect('/work-orders');
    }

    public function search()
    {
        $this->requireAuth();
        
        $query = $_GET['query'] ?? '';
        $page = $_GET['page'] ?? 1;
        $perPage = 10;

        $workOrders = $this->workOrderModel->search($query, $page, $perPage);

        return $this->json([
            'workOrders' => $workOrders,
            'currentPage' => $page
        ]);
    }

    public function getWorkOrdersByStatus()
    {
        $this->requireAuth();
        
        $status = $_GET['status'] ?? null;
        if (!$status) {
            return $this->json(['error' => 'Status is required'], 400);
        }

        $workOrders = $this->workOrderModel->getWorkOrdersByStatus($status);

        return $this->json(['workOrders' => $workOrders]);
    }

    public function getWorkOrdersByPriority()
    {
        $this->requireAuth();
        
        $priority = $_GET['priority'] ?? null;
        if (!$priority) {
            return $this->json(['error' => 'Priority is required'], 400);
        }

        $workOrders = $this->workOrderModel->getWorkOrdersByPriority($priority);

        return $this->json(['workOrders' => $workOrders]);
    }

    public function getWorkOrdersByAssignee()
    {
        $this->requireAuth();
        
        $userId = $_GET['user_id'] ?? null;
        if (!$userId) {
            return $this->json(['error' => 'User ID is required'], 400);
        }

        $workOrders = $this->workOrderModel->getWorkOrdersByAssignee($userId);

        return $this->json(['workOrders' => $workOrders]);
    }

    public function getWorkOrdersByAsset()
    {
        $this->requireAuth();
        
        $assetId = $_GET['asset_id'] ?? null;
        if (!$assetId) {
            return $this->json(['error' => 'Asset ID is required'], 400);
        }

        $workOrders = $this->workOrderModel->getWorkOrdersByAsset($assetId);

        return $this->json(['workOrders' => $workOrders]);
    }

    public function getWorkOrdersByLocation()
    {
        $this->requireAuth();
        
        $locationId = $_GET['location_id'] ?? null;
        if (!$locationId) {
            return $this->json(['error' => 'Location ID is required'], 400);
        }

        $workOrders = $this->workOrderModel->getWorkOrdersByLocation($locationId);

        return $this->json(['workOrders' => $workOrders]);
    }

    private function validateWorkOrder($data)
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'Title is required.';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'Description is required.';
        }

        if (empty($data['priority'])) {
            $errors['priority'] = 'Priority is required.';
        } elseif (!in_array($data['priority'], ['low', 'medium', 'high', 'urgent'])) {
            $errors['priority'] = 'Invalid priority value.';
        }

        if (empty($data['status'])) {
            $errors['status'] = 'Status is required.';
        } elseif (!in_array($data['status'], ['pending', 'in_progress', 'completed', 'cancelled'])) {
            $errors['status'] = 'Invalid status value.';
        }

        if (!empty($data['due_date']) && !strtotime($data['due_date'])) {
            $errors['due_date'] = 'Invalid due date format.';
        }

        if (!empty($data['estimated_cost']) && !is_numeric($data['estimated_cost'])) {
            $errors['estimated_cost'] = 'Estimated cost must be a number.';
        }

        if (!empty($data['actual_cost']) && !is_numeric($data['actual_cost'])) {
            $errors['actual_cost'] = 'Actual cost must be a number.';
        }

        return $errors;
    }
} 