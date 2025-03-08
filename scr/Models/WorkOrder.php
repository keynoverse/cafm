<?php

namespace App\Models;

use App\Core\Model;

class WorkOrder extends Model
{
    protected $table = 'work_orders';

    public function getWithDetails($id)
    {
        $sql = "SELECT wo.*, 
                a.name as asset_name,
                a.id as asset_id,
                l.name as location_name,
                l.id as location_id,
                r.name as room_name,
                r.id as room_id,
                f.name as floor_name,
                f.id as floor_id,
                b.name as building_name,
                b.id as building_id,
                u.first_name as assigned_to_first_name,
                u.last_name as assigned_to_last_name,
                u.id as assigned_to_id,
                c.first_name as created_by_first_name,
                c.last_name as created_by_last_name,
                c.id as created_by_id
                FROM {$this->table} wo
                LEFT JOIN assets a ON a.id = wo.asset_id
                LEFT JOIN locations l ON l.id = wo.location_id
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                LEFT JOIN users u ON u.id = wo.assigned_to
                LEFT JOIN users c ON c.id = wo.created_by
                WHERE wo.id = ?";

        return $this->db->query($sql, [$id])->fetch();
    }

    public function getAllWithDetails($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT wo.*, 
                a.name as asset_name,
                a.id as asset_id,
                l.name as location_name,
                l.id as location_id,
                r.name as room_name,
                r.id as room_id,
                f.name as floor_name,
                f.id as floor_id,
                b.name as building_name,
                b.id as building_id,
                u.first_name as assigned_to_first_name,
                u.last_name as assigned_to_last_name,
                u.id as assigned_to_id,
                c.first_name as created_by_first_name,
                c.last_name as created_by_last_name,
                c.id as created_by_id
                FROM {$this->table} wo
                LEFT JOIN assets a ON a.id = wo.asset_id
                LEFT JOIN locations l ON l.id = wo.location_id
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                LEFT JOIN users u ON u.id = wo.assigned_to
                LEFT JOIN users c ON c.id = wo.created_by
                ORDER BY wo.created_at DESC
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$perPage, $offset])->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (
                    title, description, priority, status,
                    asset_id, location_id, assigned_to,
                    due_date, estimated_cost, actual_cost,
                    created_by
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->db->query($sql, [
            $data['title'],
            $data['description'],
            $data['priority'],
            $data['status'] ?? 'pending',
            $data['asset_id'] ?? null,
            $data['location_id'] ?? null,
            $data['assigned_to'] ?? null,
            $data['due_date'] ?? null,
            $data['estimated_cost'] ?? null,
            $data['actual_cost'] ?? null,
            $data['created_by']
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET
                    title = ?, description = ?, priority = ?,
                    status = ?, asset_id = ?, location_id = ?,
                    assigned_to = ?, due_date = ?, estimated_cost = ?,
                    actual_cost = ?
                WHERE id = ?";

        return $this->db->query($sql, [
            $data['title'],
            $data['description'],
            $data['priority'],
            $data['status'],
            $data['asset_id'] ?? null,
            $data['location_id'] ?? null,
            $data['assigned_to'] ?? null,
            $data['due_date'] ?? null,
            $data['estimated_cost'] ?? null,
            $data['actual_cost'] ?? null,
            $id
        ]);
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }

    public function search($query, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $searchTerm = "%{$query}%";
        
        $sql = "SELECT wo.*, 
                a.name as asset_name,
                a.id as asset_id,
                l.name as location_name,
                l.id as location_id,
                r.name as room_name,
                r.id as room_id,
                f.name as floor_name,
                f.id as floor_id,
                b.name as building_name,
                b.id as building_id,
                u.first_name as assigned_to_first_name,
                u.last_name as assigned_to_last_name,
                u.id as assigned_to_id,
                c.first_name as created_by_first_name,
                c.last_name as created_by_last_name,
                c.id as created_by_id
                FROM {$this->table} wo
                LEFT JOIN assets a ON a.id = wo.asset_id
                LEFT JOIN locations l ON l.id = wo.location_id
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                LEFT JOIN users u ON u.id = wo.assigned_to
                LEFT JOIN users c ON c.id = wo.created_by
                WHERE wo.title LIKE ? OR wo.description LIKE ?
                    OR a.name LIKE ? OR l.name LIKE ? OR r.name LIKE ?
                    OR f.name LIKE ? OR b.name LIKE ?
                ORDER BY wo.created_at DESC
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [
            $searchTerm, $searchTerm,
            $searchTerm, $searchTerm, $searchTerm,
            $searchTerm, $searchTerm,
            $perPage, $offset
        ])->fetchAll();
    }

    public function getWorkOrderStats()
    {
        $sql = "SELECT 
                COUNT(*) as total_work_orders,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_work_orders,
                SUM(CASE WHEN status = 'in_progress' THEN 1 ELSE 0 END) as in_progress_work_orders,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_work_orders,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_work_orders,
                COUNT(DISTINCT asset_id) as total_assets,
                COUNT(DISTINCT location_id) as total_locations,
                COUNT(DISTINCT assigned_to) as total_assignees
                FROM {$this->table}";

        return $this->db->query($sql)->fetch();
    }

    public function getWorkOrdersByStatus($status)
    {
        $sql = "SELECT wo.*, 
                a.name as asset_name,
                l.name as location_name,
                r.name as room_name,
                f.name as floor_name,
                b.name as building_name,
                u.first_name as assigned_to_first_name,
                u.last_name as assigned_to_last_name
                FROM {$this->table} wo
                LEFT JOIN assets a ON a.id = wo.asset_id
                LEFT JOIN locations l ON l.id = wo.location_id
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                LEFT JOIN users u ON u.id = wo.assigned_to
                WHERE wo.status = ?
                ORDER BY wo.created_at DESC";

        return $this->db->query($sql, [$status])->fetchAll();
    }

    public function getWorkOrdersByPriority($priority)
    {
        $sql = "SELECT wo.*, 
                a.name as asset_name,
                l.name as location_name,
                r.name as room_name,
                f.name as floor_name,
                b.name as building_name,
                u.first_name as assigned_to_first_name,
                u.last_name as assigned_to_last_name
                FROM {$this->table} wo
                LEFT JOIN assets a ON a.id = wo.asset_id
                LEFT JOIN locations l ON l.id = wo.location_id
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                LEFT JOIN users u ON u.id = wo.assigned_to
                WHERE wo.priority = ?
                ORDER BY wo.created_at DESC";

        return $this->db->query($sql, [$priority])->fetchAll();
    }

    public function getWorkOrdersByAssignee($userId)
    {
        $sql = "SELECT wo.*, 
                a.name as asset_name,
                l.name as location_name,
                r.name as room_name,
                f.name as floor_name,
                b.name as building_name
                FROM {$this->table} wo
                LEFT JOIN assets a ON a.id = wo.asset_id
                LEFT JOIN locations l ON l.id = wo.location_id
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                WHERE wo.assigned_to = ?
                ORDER BY wo.created_at DESC";

        return $this->db->query($sql, [$userId])->fetchAll();
    }

    public function getWorkOrdersByAsset($assetId)
    {
        $sql = "SELECT wo.*, 
                l.name as location_name,
                r.name as room_name,
                f.name as floor_name,
                b.name as building_name,
                u.first_name as assigned_to_first_name,
                u.last_name as assigned_to_last_name
                FROM {$this->table} wo
                LEFT JOIN locations l ON l.id = wo.location_id
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                LEFT JOIN users u ON u.id = wo.assigned_to
                WHERE wo.asset_id = ?
                ORDER BY wo.created_at DESC";

        return $this->db->query($sql, [$assetId])->fetchAll();
    }

    public function getWorkOrdersByLocation($locationId)
    {
        $sql = "SELECT wo.*, 
                a.name as asset_name,
                r.name as room_name,
                f.name as floor_name,
                b.name as building_name,
                u.first_name as assigned_to_first_name,
                u.last_name as assigned_to_last_name
                FROM {$this->table} wo
                LEFT JOIN assets a ON a.id = wo.asset_id
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                LEFT JOIN users u ON u.id = wo.assigned_to
                WHERE wo.location_id = ?
                ORDER BY wo.created_at DESC";

        return $this->db->query($sql, [$locationId])->fetchAll();
    }
} 