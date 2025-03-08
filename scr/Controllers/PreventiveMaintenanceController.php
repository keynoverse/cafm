<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\PreventiveMaintenance;
use App\Models\Asset;
use App\Models\User;

class PreventiveMaintenanceController extends Controller
{
    private PreventiveMaintenance $maintenanceModel;
    private Asset $assetModel;
    private User $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->maintenanceModel = new PreventiveMaintenance();
        $this->assetModel = new Asset();
        $this->userModel = new User();
    }

    /**
     * Display maintenance schedule dashboard
     */
    public function index()
    {
        $this->requirePermission('view_maintenance');

        // Get statistics
        $statistics = $this->maintenanceModel->getStatistics();

        // Get upcoming maintenance tasks
        $upcoming = $this->maintenanceModel->getUpcoming();

        // Get overdue maintenance tasks
        $overdue = $this->maintenanceModel->getOverdue();

        // Get all schedules
        $schedules = $this->maintenanceModel->getAllWithDetails();

        return $this->view('maintenance/index', [
            'statistics' => $statistics,
            'upcoming' => $upcoming,
            'overdue' => $overdue,
            'schedules' => $schedules
        ]);
    }

    /**
     * Display form to create new maintenance schedule
     */
    public function create()
    {
        $this->requirePermission('create_maintenance');

        // Get all assets
        $assets = $this->assetModel->getAll();

        // Get all technicians
        $technicians = $this->userModel->getByRole('technician');

        return $this->view('maintenance/create', [
            'assets' => $assets,
            'technicians' => $technicians
        ]);
    }

    /**
     * Handle creation of new maintenance schedule
     */
    public function store()
    {
        $this->requirePermission('create_maintenance');

        // Validate input
        $this->validate([
            'asset_id' => 'required|numeric',
            'title' => 'required|max:255',
            'frequency' => 'required|in:daily,weekly,bi_weekly,monthly,quarterly,semi_annual,annual',
            'next_due_date' => 'required|date'
        ]);

        // Create maintenance schedule
        try {
            $this->maintenanceModel->create([
                'asset_id' => $_POST['asset_id'],
                'title' => $_POST['title'],
                'description' => $_POST['description'] ?? null,
                'frequency' => $_POST['frequency'],
                'next_due_date' => $_POST['next_due_date'],
                'assigned_to' => $_POST['assigned_to'] ?? null,
                'created_by' => $this->user['id']
            ]);

            $this->flash('success', 'Maintenance schedule created successfully');
            return $this->redirect('maintenance');
        } catch (\Exception $e) {
            $this->flash('error', 'Failed to create maintenance schedule');
            return $this->back();
        }
    }

    /**
     * Display maintenance schedule details
     */
    public function show($id)
    {
        $this->requirePermission('view_maintenance');

        // Get maintenance schedule
        $schedule = $this->maintenanceModel->getById($id);
        if (!$schedule) {
            $this->flash('error', 'Maintenance schedule not found');
            return $this->redirect('maintenance');
        }

        return $this->view('maintenance/show', [
            'schedule' => $schedule
        ]);
    }

    /**
     * Display form to edit maintenance schedule
     */
    public function edit($id)
    {
        $this->requirePermission('edit_maintenance');

        // Get maintenance schedule
        $schedule = $this->maintenanceModel->getById($id);
        if (!$schedule) {
            $this->flash('error', 'Maintenance schedule not found');
            return $this->redirect('maintenance');
        }

        // Get all assets
        $assets = $this->assetModel->getAll();

        // Get all technicians
        $technicians = $this->userModel->getByRole('technician');

        return $this->view('maintenance/edit', [
            'schedule' => $schedule,
            'assets' => $assets,
            'technicians' => $technicians
        ]);
    }

    /**
     * Handle update of maintenance schedule
     */
    public function update($id)
    {
        $this->requirePermission('edit_maintenance');

        // Validate input
        $this->validate([
            'asset_id' => 'required|numeric',
            'title' => 'required|max:255',
            'frequency' => 'required|in:daily,weekly,bi_weekly,monthly,quarterly,semi_annual,annual',
            'next_due_date' => 'required|date'
        ]);

        // Update maintenance schedule
        try {
            $this->maintenanceModel->update($id, [
                'asset_id' => $_POST['asset_id'],
                'title' => $_POST['title'],
                'description' => $_POST['description'] ?? null,
                'frequency' => $_POST['frequency'],
                'next_due_date' => $_POST['next_due_date'],
                'assigned_to' => $_POST['assigned_to'] ?? null,
                'status' => $_POST['status']
            ]);

            $this->flash('success', 'Maintenance schedule updated successfully');
            return $this->redirect("maintenance/{$id}");
        } catch (\Exception $e) {
            $this->flash('error', 'Failed to update maintenance schedule');
            return $this->back();
        }
    }

    /**
     * Handle completion of maintenance schedule
     */
    public function complete($id)
    {
        $this->requirePermission('complete_maintenance');

        try {
            $this->maintenanceModel->complete($id, $this->user['id']);
            $this->flash('success', 'Maintenance schedule completed successfully');
            return $this->json(['success' => true]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Failed to complete maintenance schedule'
            ], 500);
        }
    }

    /**
     * Handle search for maintenance schedules
     */
    public function search()
    {
        $this->requirePermission('view_maintenance');

        $query = $_GET['query'] ?? '';
        $filters = [
            'status' => $_GET['status'] ?? null,
            'frequency' => $_GET['frequency'] ?? null,
            'asset_id' => $_GET['asset_id'] ?? null
        ];

        $results = $this->maintenanceModel->search($query, $filters);

        return $this->json([
            'success' => true,
            'results' => $results
        ]);
    }

    /**
     * Get maintenance schedules for an asset
     */
    public function getByAsset($assetId)
    {
        $this->requirePermission('view_maintenance');

        $schedules = $this->maintenanceModel->getByAsset($assetId);

        return $this->json([
            'success' => true,
            'schedules' => $schedules
        ]);
    }

    /**
     * Get maintenance schedules assigned to a user
     */
    public function getByUser($userId)
    {
        $this->requirePermission('view_maintenance');

        $schedules = $this->maintenanceModel->getByAssignedUser($userId);

        return $this->json([
            'success' => true,
            'schedules' => $schedules
        ]);
    }

    /**
     * Export maintenance schedules
     */
    public function export()
    {
        $this->requirePermission('export_maintenance');

        $schedules = $this->maintenanceModel->getAllWithDetails();

        // Generate CSV
        $csv = "Title,Asset,Frequency,Next Due Date,Status,Assigned To\n";
        foreach ($schedules as $schedule) {
            $csv .= sprintf(
                '"%s","%s","%s","%s","%s","%s"' . "\n",
                $schedule['title'],
                $schedule['asset_name'],
                $schedule['frequency'],
                $schedule['next_due_date'],
                $schedule['status'],
                ($schedule['assigned_first_name'] ? 
                    $schedule['assigned_first_name'] . ' ' . $schedule['assigned_last_name'] : 'Unassigned')
            );
        }

        // Send CSV response
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="maintenance_schedules.csv"');
        echo $csv;
        exit;
    }
} 