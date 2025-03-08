<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\WorkOrderTask;
use App\Models\WorkOrder;

class WorkOrderTaskController extends Controller
{
    private WorkOrderTask $taskModel;
    private WorkOrder $workOrderModel;

    public function __construct()
    {
        parent::__construct();
        $this->taskModel = new WorkOrderTask();
        $this->workOrderModel = new WorkOrder();
    }

    public function create($workOrderId)
    {
        $this->requireAuth();
        $this->requireRole(['admin', 'technician']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'work_order_id' => $workOrderId,
                'description' => $_POST['description'],
                'priority' => $_POST['priority'] ?? null,
                'assigned_to' => $_POST['assigned_to'] ?? null,
                'due_date' => $_POST['due_date'] ?? null
            ];

            if ($this->taskModel->create($data)) {
                return $this->json([
                    'success' => true,
                    'task' => $this->taskModel->getWithDetails($this->db->lastInsertId())
                ]);
            }
        }

        return $this->json(['success' => false, 'message' => 'Failed to create task'], 400);
    }

    public function update($workOrderId, $taskId)
    {
        $this->requireAuth();
        $this->requireRole(['admin', 'technician']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'description' => $_POST['description'],
                'priority' => $_POST['priority'] ?? null,
                'status' => $_POST['status'],
                'assigned_to' => $_POST['assigned_to'] ?? null,
                'due_date' => $_POST['due_date'] ?? null
            ];

            if ($this->taskModel->update($taskId, $data)) {
                return $this->json([
                    'success' => true,
                    'task' => $this->taskModel->getWithDetails($taskId)
                ]);
            }
        }

        return $this->json(['success' => false, 'message' => 'Failed to update task'], 400);
    }

    public function delete($workOrderId, $taskId)
    {
        $this->requireAuth();
        $this->requireRole(['admin', 'technician']);

        if ($this->taskModel->delete($taskId)) {
            return $this->json(['success' => true]);
        }

        return $this->json(['success' => false, 'message' => 'Failed to delete task'], 400);
    }

    public function complete($workOrderId, $taskId)
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            $completed = $data['completed'] ?? false;

            if ($completed) {
                if ($this->taskModel->complete($taskId)) {
                    return $this->json(['success' => true]);
                }
            } else {
                if ($this->taskModel->update($taskId, ['status' => 'pending', 'completed_at' => null])) {
                    return $this->json(['success' => true]);
                }
            }
        }

        return $this->json(['success' => false, 'message' => 'Failed to update task status'], 400);
    }

    public function getNotes($workOrderId, $taskId)
    {
        $this->requireAuth();

        $notes = $this->taskModel->getTaskNotes($taskId);
        return $this->json(['success' => true, 'notes' => $notes]);
    }

    public function addNote($workOrderId, $taskId)
    {
        $this->requireAuth();
        $this->requireRole(['admin', 'technician']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->taskModel->addTaskNote($taskId, $this->user['id'], $_POST['notes'])) {
                return $this->json(['success' => true]);
            }
        }

        return $this->json(['success' => false, 'message' => 'Failed to add note'], 400);
    }

    public function getDependencies($workOrderId, $taskId)
    {
        $this->requireAuth();

        $dependencies = $this->taskModel->getTaskDependencies($taskId);
        return $this->json(['success' => true, 'dependencies' => $dependencies]);
    }

    public function addDependency($workOrderId, $taskId)
    {
        $this->requireAuth();
        $this->requireRole(['admin', 'technician']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->taskModel->addTaskDependency($taskId, $_POST['dependent_task_id'])) {
                return $this->json(['success' => true]);
            }
        }

        return $this->json(['success' => false, 'message' => 'Failed to add dependency'], 400);
    }

    public function removeDependency($workOrderId, $taskId, $dependentTaskId)
    {
        $this->requireAuth();
        $this->requireRole(['admin', 'technician']);

        if ($this->taskModel->removeTaskDependency($taskId, $dependentTaskId)) {
            return $this->json(['success' => true]);
        }

        return $this->json(['success' => false, 'message' => 'Failed to remove dependency'], 400);
    }

    public function startTimeTracking($workOrderId, $taskId)
    {
        $this->requireAuth();

        if ($this->taskModel->startTimeTracking($taskId, $this->user['id'])) {
            return $this->json(['success' => true]);
        }

        return $this->json(['success' => false, 'message' => 'Failed to start time tracking'], 400);
    }

    public function stopTimeTracking($workOrderId, $taskId)
    {
        $this->requireAuth();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            if ($this->taskModel->stopTimeTracking($taskId, $this->user['id'], $data['notes'] ?? null)) {
                return $this->json(['success' => true]);
            }
        }

        return $this->json(['success' => false, 'message' => 'Failed to stop time tracking'], 400);
    }

    public function getTimeHistory($workOrderId, $taskId)
    {
        $this->requireAuth();

        $history = $this->taskModel->getTaskTimeHistory($taskId);
        $totalTime = $this->taskModel->getTotalTaskTime($taskId);

        return $this->json([
            'success' => true,
            'history' => $history,
            'totalTime' => $totalTime
        ]);
    }
} 