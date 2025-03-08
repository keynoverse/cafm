<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class PreventiveMaintenance extends Model
{
    protected $table = 'preventive_maintenance_schedules';

    /**
     * Get all preventive maintenance schedules with details
     */
    public function getAllWithDetails()
    {
        $sql = "SELECT pms.*, 
                a.name as asset_name, a.asset_tag,
                u1.first_name as assigned_first_name, u1.last_name as assigned_last_name,
                u2.first_name as created_first_name, u2.last_name as created_last_name
                FROM {$this->table} pms
                LEFT JOIN assets a ON a.id = pms.asset_id
                LEFT JOIN users u1 ON u1.id = pms.assigned_to
                LEFT JOIN users u2 ON u2.id = pms.created_by
                ORDER BY pms.next_due_date ASC";

        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Get overdue maintenance schedules
     */
    public function getOverdue()
    {
        $sql = "SELECT pms.*, 
                a.name as asset_name, a.asset_tag,
                u1.first_name as assigned_first_name, u1.last_name as assigned_last_name
                FROM {$this->table} pms
                LEFT JOIN assets a ON a.id = pms.asset_id
                LEFT JOIN users u1 ON u1.id = pms.assigned_to
                WHERE pms.next_due_date < CURRENT_DATE
                AND pms.status = 'active'
                ORDER BY pms.next_due_date ASC";

        return $this->db->query($sql)->fetchAll();
    }

    /**
     * Get upcoming maintenance schedules
     */
    public function getUpcoming($days = 7)
    {
        $sql = "SELECT pms.*, 
                a.name as asset_name, a.asset_tag,
                u1.first_name as assigned_first_name, u1.last_name as assigned_last_name
                FROM {$this->table} pms
                LEFT JOIN assets a ON a.id = pms.asset_id
                LEFT JOIN users u1 ON u1.id = pms.assigned_to
                WHERE pms.next_due_date BETWEEN CURRENT_DATE AND DATE_ADD(CURRENT_DATE, INTERVAL ? DAY)
                AND pms.status = 'active'
                ORDER BY pms.next_due_date ASC";

        return $this->db->query($sql, [$days])->fetchAll();
    }

    /**
     * Get maintenance schedule by ID with details
     */
    public function getById($id)
    {
        $sql = "SELECT pms.*, 
                a.name as asset_name, a.asset_tag,
                u1.first_name as assigned_first_name, u1.last_name as assigned_last_name,
                u2.first_name as created_first_name, u2.last_name as created_last_name
                FROM {$this->table} pms
                LEFT JOIN assets a ON a.id = pms.asset_id
                LEFT JOIN users u1 ON u1.id = pms.assigned_to
                LEFT JOIN users u2 ON u2.id = pms.created_by
                WHERE pms.id = ?";

        return $this->db->query($sql, [$id])->fetch();
    }

    /**
     * Create a new maintenance schedule
     */
    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (
                    asset_id, title, description, frequency, 
                    next_due_date, assigned_to, status, created_by
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->db->query($sql, [
            $data['asset_id'],
            $data['title'],
            $data['description'],
            $data['frequency'],
            $data['next_due_date'],
            $data['assigned_to'] ?? null,
            $data['status'] ?? 'active',
            $data['created_by']
        ]);
    }

    /**
     * Update a maintenance schedule
     */
    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET
                    asset_id = ?,
                    title = ?,
                    description = ?,
                    frequency = ?,
                    next_due_date = ?,
                    assigned_to = ?,
                    status = ?
                WHERE id = ?";

        return $this->db->query($sql, [
            $data['asset_id'],
            $data['title'],
            $data['description'],
            $data['frequency'],
            $data['next_due_date'],
            $data['assigned_to'] ?? null,
            $data['status'],
            $id
        ]);
    }

    /**
     * Complete a maintenance schedule
     */
    public function complete($id, $completedBy)
    {
        // Start transaction
        $this->db->beginTransaction();

        try {
            // Update the current schedule
            $sql = "UPDATE {$this->table} SET
                    last_completed_date = CURRENT_DATE,
                    status = 'completed'
                    WHERE id = ?";
            $this->db->query($sql, [$id]);

            // Get the schedule details
            $schedule = $this->getById($id);

            // Calculate next due date based on frequency
            $nextDueDate = $this->calculateNextDueDate($schedule['frequency']);

            // Create new schedule for the next cycle
            $sql = "INSERT INTO {$this->table} (
                        asset_id, title, description, frequency,
                        next_due_date, assigned_to, status, created_by
                    ) VALUES (?, ?, ?, ?, ?, ?, 'active', ?)";

            $this->db->query($sql, [
                $schedule['asset_id'],
                $schedule['title'],
                $schedule['description'],
                $schedule['frequency'],
                $nextDueDate,
                $schedule['assigned_to'],
                $completedBy
            ]);

            // Add to maintenance history
            $sql = "INSERT INTO maintenance_history (
                        asset_id, maintenance_type, reference_id,
                        reference_type, performed_by, performed_date,
                        description
                    ) VALUES (?, 'preventive', ?, ?, ?, CURRENT_DATE, ?)";

            $this->db->query($sql, [
                $schedule['asset_id'],
                $id,
                'preventive_maintenance_schedules',
                $completedBy,
                "Completed preventive maintenance: {$schedule['title']}"
            ]);

            // Commit transaction
            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            // Rollback transaction on error
            $this->db->rollBack();
            throw $e;
        }
    }

    /**
     * Calculate next due date based on frequency
     */
    private function calculateNextDueDate($frequency)
    {
        $intervals = [
            'daily' => 'P1D',
            'weekly' => 'P1W',
            'bi_weekly' => 'P2W',
            'monthly' => 'P1M',
            'quarterly' => 'P3M',
            'semi_annual' => 'P6M',
            'annual' => 'P1Y'
        ];

        $date = new \DateTime();
        $date->add(new \DateInterval($intervals[$frequency]));
        return $date->format('Y-m-d');
    }

    /**
     * Get maintenance schedules by asset
     */
    public function getByAsset($assetId)
    {
        $sql = "SELECT pms.*, 
                u1.first_name as assigned_first_name, u1.last_name as assigned_last_name
                FROM {$this->table} pms
                LEFT JOIN users u1 ON u1.id = pms.assigned_to
                WHERE pms.asset_id = ?
                ORDER BY pms.next_due_date ASC";

        return $this->db->query($sql, [$assetId])->fetchAll();
    }

    /**
     * Get maintenance schedules by assigned user
     */
    public function getByAssignedUser($userId)
    {
        $sql = "SELECT pms.*, 
                a.name as asset_name, a.asset_tag
                FROM {$this->table} pms
                LEFT JOIN assets a ON a.id = pms.asset_id
                WHERE pms.assigned_to = ?
                AND pms.status = 'active'
                ORDER BY pms.next_due_date ASC";

        return $this->db->query($sql, [$userId])->fetchAll();
    }

    /**
     * Get maintenance schedule statistics
     */
    public function getStatistics()
    {
        $sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN next_due_date < CURRENT_DATE AND status = 'active' THEN 1 ELSE 0 END) as overdue
                FROM {$this->table}";

        return $this->db->query($sql)->fetch();
    }

    /**
     * Search maintenance schedules
     */
    public function search($query, $filters = [])
    {
        $sql = "SELECT pms.*, 
                a.name as asset_name, a.asset_tag,
                u1.first_name as assigned_first_name, u1.last_name as assigned_last_name
                FROM {$this->table} pms
                LEFT JOIN assets a ON a.id = pms.asset_id
                LEFT JOIN users u1 ON u1.id = pms.assigned_to
                WHERE (pms.title LIKE ? OR pms.description LIKE ? OR a.name LIKE ?)";

        $params = ["%$query%", "%$query%", "%$query%"];

        if (!empty($filters['status'])) {
            $sql .= " AND pms.status = ?";
            $params[] = $filters['status'];
        }

        if (!empty($filters['frequency'])) {
            $sql .= " AND pms.frequency = ?";
            $params[] = $filters['frequency'];
        }

        if (!empty($filters['asset_id'])) {
            $sql .= " AND pms.asset_id = ?";
            $params[] = $filters['asset_id'];
        }

        $sql .= " ORDER BY pms.next_due_date ASC";

        return $this->db->query($sql, $params)->fetchAll();
    }
} 