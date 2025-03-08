<?php

namespace App\Controllers;

use App\Models\MaintenanceRequest;
use App\Models\WorkOrder;
use App\Models\User;
use App\Models\Asset;
use App\Models\SLA;

class MaintenanceRequestController extends Controller
{
    private $maintenanceRequest;
    private $workOrder;
    private $user;
    private $asset;
    private $sla;

    public function __construct()
    {
        parent::__construct();
        $this->maintenanceRequest = new MaintenanceRequest();
        $this->workOrder = new WorkOrder();
        $this->user = new User();
        $this->asset = new Asset();
        $this->sla = new SLA();
    }

    public function index()
    {
        $this->authorize('view_maintenance_requests');
        
        $requests = $this->maintenanceRequest->getAllWithDetails();
        $statistics = $this->maintenanceRequest->getStatistics();
        
        return $this->view('maintenance-requests/index', [
            'requests' => $requests,
            'statistics' => $statistics
        ]);
    }

    public function dashboard()
    {
        $this->authorize('view_maintenance_requests');
        
        $statistics = $this->maintenanceRequest->getStatistics();
        $recentRequests = $this->maintenanceRequest->getRecent();
        $pendingApprovals = $this->maintenanceRequest->getPendingApprovals();
        $urgentRequests = $this->maintenanceRequest->getUrgentRequests();
        
        return $this->view('maintenance-requests/dashboard', [
            'statistics' => $statistics,
            'recentRequests' => $recentRequests,
            'pendingApprovals' => $pendingApprovals,
            'urgentRequests' => $urgentRequests
        ]);
    }

    public function create()
    {
        $this->authorize('create_maintenance_request');
        
        $assets = $this->asset->getAll();
        $priorities = $this->maintenanceRequest->getPriorityLevels();
        $categories = $this->maintenanceRequest->getCategories();
        
        return $this->view('maintenance-requests/create', [
            'assets' => $assets,
            'priorities' => $priorities,
            'categories' => $categories
        ]);
    }

    public function store()
    {
        $this->authorize('create_maintenance_request');
        
        $data = $this->validate($_POST, [
            'asset_id' => 'required|exists:assets,id',
            'title' => 'required|max:255',
            'description' => 'required',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required',
            'requested_completion_date' => 'required|date|after:today'
        ]);

        $data['requested_by'] = $this->auth->id;
        $data['status'] = 'pending';

        // Apply SLA based on priority and category
        $sla = $this->sla->getByPriorityAndCategory($data['priority'], $data['category']);
        if ($sla) {
            $data['sla_id'] = $sla['id'];
            $data['due_date'] = date('Y-m-d H:i:s', strtotime("+{$sla['response_time']} hours"));
        }

        $requestId = $this->maintenanceRequest->create($data);

        if ($requestId) {
            $this->flash('success', 'Maintenance request created successfully.');
            return $this->redirect("maintenance-requests/{$requestId}");
        }

        $this->flash('error', 'Failed to create maintenance request.');
        return $this->redirect('maintenance-requests/create');
    }

    public function show($id)
    {
        $this->authorize('view_maintenance_requests');
        
        $request = $this->maintenanceRequest->getById($id);
        if (!$request) {
            $this->flash('error', 'Maintenance request not found.');
            return $this->redirect('maintenance-requests');
        }

        return $this->view('maintenance-requests/show', [
            'request' => $request
        ]);
    }

    public function edit($id)
    {
        $this->authorize('edit_maintenance_request');
        
        $request = $this->maintenanceRequest->getById($id);
        if (!$request) {
            $this->flash('error', 'Maintenance request not found.');
            return $this->redirect('maintenance-requests');
        }

        $assets = $this->asset->getAll();
        $priorities = $this->maintenanceRequest->getPriorityLevels();
        $categories = $this->maintenanceRequest->getCategories();

        return $this->view('maintenance-requests/edit', [
            'request' => $request,
            'assets' => $assets,
            'priorities' => $priorities,
            'categories' => $categories
        ]);
    }

    public function update($id)
    {
        $this->authorize('edit_maintenance_request');
        
        $request = $this->maintenanceRequest->getById($id);
        if (!$request) {
            $this->flash('error', 'Maintenance request not found.');
            return $this->redirect('maintenance-requests');
        }

        $data = $this->validate($_POST, [
            'asset_id' => 'required|exists:assets,id',
            'title' => 'required|max:255',
            'description' => 'required',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required',
            'requested_completion_date' => 'required|date'
        ]);

        if ($this->maintenanceRequest->update($id, $data)) {
            $this->flash('success', 'Maintenance request updated successfully.');
            return $this->redirect("maintenance-requests/{$id}");
        }

        $this->flash('error', 'Failed to update maintenance request.');
        return $this->redirect("maintenance-requests/{$id}/edit");
    }

    public function delete($id)
    {
        $this->authorize('delete_maintenance_request');
        
        if ($this->maintenanceRequest->delete($id)) {
            $this->flash('success', 'Maintenance request deleted successfully.');
        } else {
            $this->flash('error', 'Failed to delete maintenance request.');
        }

        return $this->redirect('maintenance-requests');
    }

    public function approve($id)
    {
        $this->authorize('approve_maintenance_request');
        
        $request = $this->maintenanceRequest->getById($id);
        if (!$request || $request['status'] !== 'pending') {
            $this->flash('error', 'Invalid maintenance request or already processed.');
            return $this->redirect('maintenance-requests');
        }

        if ($this->maintenanceRequest->approve($id, $this->auth->id)) {
            $this->flash('success', 'Maintenance request approved successfully.');
        } else {
            $this->flash('error', 'Failed to approve maintenance request.');
        }

        return $this->redirect("maintenance-requests/{$id}");
    }

    public function reject($id)
    {
        $this->authorize('reject_maintenance_request');
        
        $data = $this->validate($_POST, [
            'rejection_reason' => 'required|max:500'
        ]);

        if ($this->maintenanceRequest->reject($id, $this->auth->id, $data['rejection_reason'])) {
            $this->flash('success', 'Maintenance request rejected successfully.');
        } else {
            $this->flash('error', 'Failed to reject maintenance request.');
        }

        return $this->redirect("maintenance-requests/{$id}");
    }

    public function convertToWorkOrder($id)
    {
        $this->authorize('convert_maintenance_request');
        
        $request = $this->maintenanceRequest->getById($id);
        if (!$request || $request['status'] !== 'approved') {
            $this->flash('error', 'Maintenance request must be approved before converting to work order.');
            return $this->redirect("maintenance-requests/{$id}");
        }

        $workOrderData = [
            'title' => $request['title'],
            'description' => $request['description'],
            'asset_id' => $request['asset_id'],
            'priority' => $request['priority'],
            'due_date' => $request['requested_completion_date'],
            'status' => 'open',
            'maintenance_request_id' => $id
        ];

        $workOrderId = $this->workOrder->create($workOrderData);
        if ($workOrderId) {
            $this->maintenanceRequest->update($id, ['status' => 'converted']);
            $this->flash('success', 'Maintenance request converted to work order successfully.');
            return $this->redirect("work-orders/{$workOrderId}");
        }

        $this->flash('error', 'Failed to convert maintenance request to work order.');
        return $this->redirect("maintenance-requests/{$id}");
    }

    public function getUserRequests()
    {
        $requests = $this->maintenanceRequest->getByUser($this->auth->id);
        return $this->view('maintenance-requests/my-requests', [
            'requests' => $requests
        ]);
    }

    public function search()
    {
        $this->authorize('view_maintenance_requests');
        
        $query = $_GET['query'] ?? '';
        $filters = [
            'status' => $_GET['status'] ?? null,
            'priority' => $_GET['priority'] ?? null,
            'category' => $_GET['category'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null
        ];

        $results = $this->maintenanceRequest->search($query, $filters);
        
        if (isset($_GET['ajax'])) {
            return $this->json($results);
        }

        return $this->view('maintenance-requests/search', [
            'results' => $results,
            'query' => $query,
            'filters' => $filters
        ]);
    }

    public function submitFeedback($id)
    {
        $data = $this->validate($_POST, [
            'rating' => 'required|integer|between:1,5',
            'comments' => 'required|max:500'
        ]);

        if ($this->maintenanceRequest->submitFeedback($id, $this->auth->id, $data)) {
            $this->flash('success', 'Feedback submitted successfully.');
        } else {
            $this->flash('error', 'Failed to submit feedback.');
        }

        return $this->redirect("maintenance-requests/{$id}");
    }
} 