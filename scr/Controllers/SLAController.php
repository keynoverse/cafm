<?php

namespace App\Controllers;

use App\Models\SLA;
use App\Models\MaintenanceRequest;

class SLAController extends Controller
{
    private $sla;
    private $maintenanceRequest;

    public function __construct()
    {
        parent::__construct();
        $this->sla = new SLA();
        $this->maintenanceRequest = new MaintenanceRequest();
    }

    public function index()
    {
        $this->authorize('view_sla');
        
        $slas = $this->sla->getAllWithDetails();
        $statistics = $this->sla->getStatistics();
        
        return $this->view('sla/index', [
            'slas' => $slas,
            'statistics' => $statistics
        ]);
    }

    public function create()
    {
        $this->authorize('create_sla');
        
        $categories = $this->maintenanceRequest->getCategories();
        $priorities = $this->maintenanceRequest->getPriorityLevels();
        
        return $this->view('sla/create', [
            'categories' => $categories,
            'priorities' => $priorities
        ]);
    }

    public function store()
    {
        $this->authorize('create_sla');
        
        $data = $this->validate($_POST, [
            'name' => 'required|max:255',
            'description' => 'required',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required',
            'response_time' => 'required|integer|min:1',
            'resolution_time' => 'required|integer|min:1',
            'escalation_time' => 'required|integer|min:1',
            'business_hours_only' => 'boolean'
        ]);

        if ($this->sla->create($data)) {
            $this->flash('success', 'SLA created successfully.');
            return $this->redirect('sla');
        }

        $this->flash('error', 'Failed to create SLA.');
        return $this->redirect('sla/create');
    }

    public function show($id)
    {
        $this->authorize('view_sla');
        
        $sla = $this->sla->getById($id);
        if (!$sla) {
            $this->flash('error', 'SLA not found.');
            return $this->redirect('sla');
        }

        $performance = $this->sla->getPerformanceById($id);
        $violations = $this->sla->getViolationsById($id);
        
        return $this->view('sla/show', [
            'sla' => $sla,
            'performance' => $performance,
            'violations' => $violations
        ]);
    }

    public function edit($id)
    {
        $this->authorize('edit_sla');
        
        $sla = $this->sla->getById($id);
        if (!$sla) {
            $this->flash('error', 'SLA not found.');
            return $this->redirect('sla');
        }

        $categories = $this->maintenanceRequest->getCategories();
        $priorities = $this->maintenanceRequest->getPriorityLevels();
        
        return $this->view('sla/edit', [
            'sla' => $sla,
            'categories' => $categories,
            'priorities' => $priorities
        ]);
    }

    public function update($id)
    {
        $this->authorize('edit_sla');
        
        $data = $this->validate($_POST, [
            'name' => 'required|max:255',
            'description' => 'required',
            'priority' => 'required|in:low,medium,high,urgent',
            'category' => 'required',
            'response_time' => 'required|integer|min:1',
            'resolution_time' => 'required|integer|min:1',
            'escalation_time' => 'required|integer|min:1',
            'business_hours_only' => 'boolean'
        ]);

        if ($this->sla->update($id, $data)) {
            $this->flash('success', 'SLA updated successfully.');
            return $this->redirect("sla/{$id}");
        }

        $this->flash('error', 'Failed to update SLA.');
        return $this->redirect("sla/{$id}/edit");
    }

    public function delete($id)
    {
        $this->authorize('delete_sla');
        
        if ($this->sla->delete($id)) {
            $this->flash('success', 'SLA deleted successfully.');
        } else {
            $this->flash('error', 'Failed to delete SLA.');
        }

        return $this->redirect('sla');
    }

    public function search()
    {
        $this->authorize('view_sla');
        
        $query = $_GET['query'] ?? '';
        $filters = [
            'priority' => $_GET['priority'] ?? null,
            'category' => $_GET['category'] ?? null
        ];

        $results = $this->sla->search($query, $filters);
        
        if (isset($_GET['ajax'])) {
            return $this->json($results);
        }

        return $this->view('sla/search', [
            'results' => $results,
            'query' => $query,
            'filters' => $filters
        ]);
    }

    public function getPerformanceMetrics()
    {
        $this->authorize('view_sla_performance');
        
        $timeframe = $_GET['timeframe'] ?? 'month';
        $metrics = $this->sla->getPerformanceMetrics($timeframe);
        
        if (isset($_GET['ajax'])) {
            return $this->json($metrics);
        }

        return $this->view('sla/performance', [
            'metrics' => $metrics,
            'timeframe' => $timeframe
        ]);
    }

    public function getViolations()
    {
        $this->authorize('view_sla_violations');
        
        $timeframe = $_GET['timeframe'] ?? 'month';
        $violations = $this->sla->getViolations($timeframe);
        
        if (isset($_GET['ajax'])) {
            return $this->json($violations);
        }

        return $this->view('sla/violations', [
            'violations' => $violations,
            'timeframe' => $timeframe
        ]);
    }

    public function generateReports()
    {
        $this->authorize('generate_sla_reports');
        
        $type = $_GET['type'] ?? 'performance';
        $timeframe = $_GET['timeframe'] ?? 'month';
        $format = $_GET['format'] ?? 'pdf';

        $report = $this->sla->generateReport($type, $timeframe);
        
        if ($format === 'json') {
            return $this->json($report);
        }

        return $this->view('sla/reports', [
            'report' => $report,
            'type' => $type,
            'timeframe' => $timeframe
        ]);
    }

    public function updatePriority($id)
    {
        $this->authorize('edit_sla');
        
        $data = $this->validate($_POST, [
            'priority' => 'required|in:low,medium,high,urgent'
        ]);

        if ($this->sla->updatePriority($id, $data['priority'])) {
            $this->flash('success', 'SLA priority updated successfully.');
        } else {
            $this->flash('error', 'Failed to update SLA priority.');
        }

        return $this->redirect("sla/{$id}");
    }

    public function getCategories()
    {
        $this->authorize('view_sla');
        
        $categories = $this->sla->getCategories();
        return $this->json($categories);
    }

    public function createCategory()
    {
        $this->authorize('create_sla');
        
        $data = $this->validate($_POST, [
            'name' => 'required|max:255',
            'description' => 'required'
        ]);

        if ($categoryId = $this->sla->createCategory($data)) {
            return $this->json([
                'success' => true,
                'category' => $this->sla->getCategoryById($categoryId)
            ]);
        }

        return $this->json([
            'success' => false,
            'message' => 'Failed to create category'
        ], 400);
    }
} 