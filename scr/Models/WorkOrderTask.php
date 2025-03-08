<?php

namespace App\Models;

use App\Core\Model;

class WorkOrderTask extends Model
{
    protected $table = 'work_order_tasks';

    public function getWithDetails($id)
    {
        $sql = "SELECT t.*, 
                u.first_name as assigned_to_first_name,
                u.last_name as assigned_to_last_name,
                u.id as assigned_to_id
                FROM {$this->table} t
                LEFT JOIN users u ON u.id = t.assigned_to
                WHERE t.id = ?";

        return $this->db->query($sql, [$id])->fetch();
    }

    public function getTasksByWorkOrder($workOrderId)
    {
        $sql = "SELECT t.*, 
                u.first_name as assigned_to_first_name,
                u.last_name as assigned_to_last_name,
                u.id as assigned_to_id
                FROM {$this->table} t
                LEFT JOIN users u ON u.id = t.assigned_to
                WHERE t.work_order_id = ?
                ORDER BY t.created_at DESC";

        return $this->db->query($sql, [$workOrderId])->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (
                    work_order_id, description, priority, status,
                    assigned_to, due_date
                ) VALUES (?, ?, ?, ?, ?, ?)";

        return $this->db->query($sql, [
            $data['work_order_id'],
            $data['description'],
            $data['priority'] ?? null,
            $data['status'] ?? 'pending',
            $data['assigned_to'] ?? null,
            $data['due_date'] ?? null
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET
                    description = ?, priority = ?, status = ?,
                    assigned_to = ?, due_date = ?
                WHERE id = ?";

        return $this->db->query($sql, [
            $data['description'],
            $data['priority'] ?? null,
            $data['status'],
            $data['assigned_to'] ?? null,
            $data['due_date'] ?? null,
            $id
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }

    public function complete($id)
    {
        $sql = "UPDATE {$this->table} SET
                    status = 'completed',
                    completed_at = CURRENT_TIMESTAMP
                WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }

    public function getTaskNotes($taskId)
    {
        $sql = "SELECT n.*, u.first_name, u.last_name
                FROM work_order_task_notes n
                LEFT JOIN users u ON u.id = n.user_id
                WHERE n.task_id = ?
                ORDER BY n.created_at DESC";
        return $this->db->query($sql, [$taskId])->fetchAll();
    }

    public function addTaskNote($taskId, $userId, $notes)
    {
        $sql = "INSERT INTO work_order_task_notes (task_id, user_id, notes)
                VALUES (?, ?, ?)";
        return $this->db->query($sql, [$taskId, $userId, $notes]);
    }

    public function getTaskDependencies($taskId)
    {
        $sql = "SELECT d.*, t.description as dependent_task_description
                FROM work_order_task_dependencies d
                LEFT JOIN {$this->table} t ON t.id = d.dependent_task_id
                WHERE d.task_id = ?";
        return $this->db->query($sql, [$taskId])->fetchAll();
    }

    public function addTaskDependency($taskId, $dependentTaskId)
    {
        $sql = "INSERT INTO work_order_task_dependencies (task_id, dependent_task_id)
                VALUES (?, ?)";
        return $this->db->query($sql, [$taskId, $dependentTaskId]);
    }

    public function removeTaskDependency($taskId, $dependentTaskId)
    {
        $sql = "DELETE FROM work_order_task_dependencies
                WHERE task_id = ? AND dependent_task_id = ?";
        return $this->db->query($sql, [$taskId, $dependentTaskId]);
    }

    public function startTimeTracking($taskId, $userId)
    {
        $sql = "INSERT INTO work_order_task_time (task_id, user_id, start_time)
                VALUES (?, ?, CURRENT_TIMESTAMP)";
        return $this->db->query($sql, [$taskId, $userId]);
    }

    public function stopTimeTracking($taskId, $userId, $notes = null)
    {
        $sql = "UPDATE work_order_task_time 
                SET end_time = CURRENT_TIMESTAMP,
                    duration = TIMESTAMPDIFF(SECOND, start_time, CURRENT_TIMESTAMP),
                    notes = ?
                WHERE task_id = ? AND user_id = ? AND end_time IS NULL";
        return $this->db->query($sql, [$notes, $taskId, $userId]);
    }

    public function getTaskTimeHistory($taskId)
    {
        $sql = "SELECT t.*, u.first_name, u.last_name
                FROM work_order_task_time t
                LEFT JOIN users u ON u.id = t.user_id
                WHERE t.task_id = ?
                ORDER BY t.start_time DESC";
        return $this->db->query($sql, [$taskId])->fetchAll();
    }

    public function getTotalTaskTime($taskId)
    {
        $sql = "SELECT SUM(duration) as total_duration
                FROM work_order_task_time
                WHERE task_id = ?";
        return $this->db->query($sql, [$taskId])->fetch()['total_duration'] ?? 0;
    }
} 