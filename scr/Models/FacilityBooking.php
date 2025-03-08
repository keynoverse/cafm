<?php

namespace App\Models;

use App\Core\Model;

class FacilityBooking extends Model
{
    protected $table = 'facility_bookings';

    public function getWithDetails($id)
    {
        $sql = "SELECT fb.*, 
                l.name as facility_name,
                l.building,
                l.floor,
                l.room,
                u.name as booked_by_name,
                a.name as approved_by_name
                FROM {$this->table} fb
                LEFT JOIN locations l ON fb.facility_id = l.id
                LEFT JOIN users u ON fb.booked_by = u.id
                LEFT JOIN users a ON fb.approved_by = a.id
                WHERE fb.id = ?";

        return $this->db->query($sql, [$id])->fetch();
    }

    public function getAllWithDetails($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT fb.*, 
                l.name as facility_name,
                l.building,
                l.floor,
                l.room,
                u.name as booked_by_name,
                a.name as approved_by_name
                FROM {$this->table} fb
                LEFT JOIN locations l ON fb.facility_id = l.id
                LEFT JOIN users u ON fb.booked_by = u.id
                LEFT JOIN users a ON fb.approved_by = a.id
                ORDER BY fb.start_time DESC
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$perPage, $offset])->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (
                    facility_id, booked_by, start_time, end_time,
                    purpose, status, approved_by
                ) VALUES (?, ?, ?, ?, ?, ?, ?)";

        return $this->db->query($sql, [
            $data['facility_id'],
            $data['booked_by'],
            $data['start_time'],
            $data['end_time'],
            $data['purpose'],
            $data['status'] ?? 'pending',
            $data['approved_by'] ?? null
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET
                    facility_id = ?, start_time = ?, end_time = ?,
                    purpose = ?, status = ?, approved_by = ?
                WHERE id = ?";

        return $this->db->query($sql, [
            $data['facility_id'],
            $data['start_time'],
            $data['end_time'],
            $data['purpose'],
            $data['status'],
            $data['approved_by'],
            $id
        ]);
    }

    public function updateStatus($id, $status, $approvedBy = null)
    {
        $sql = "UPDATE {$this->table} SET
                    status = ?, approved_by = ?
                WHERE id = ?";

        return $this->db->query($sql, [$status, $approvedBy, $id]);
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
        
        $sql = "SELECT fb.*, 
                l.name as facility_name,
                l.building,
                l.floor,
                l.room,
                u.name as booked_by_name,
                a.name as approved_by_name
                FROM {$this->table} fb
                LEFT JOIN locations l ON fb.facility_id = l.id
                LEFT JOIN users u ON fb.booked_by = u.id
                LEFT JOIN users a ON fb.approved_by = a.id
                WHERE l.name LIKE ? OR u.name LIKE ? OR fb.purpose LIKE ?
                ORDER BY fb.start_time DESC
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$searchTerm, $searchTerm, $searchTerm, $perPage, $offset])->fetchAll();
    }

    public function getUpcomingBookings($limit = 5)
    {
        $sql = "SELECT fb.*, 
                l.name as facility_name,
                l.building,
                l.floor,
                l.room,
                u.name as booked_by_name
                FROM {$this->table} fb
                LEFT JOIN locations l ON fb.facility_id = l.id
                LEFT JOIN users u ON fb.booked_by = u.id
                WHERE fb.start_time >= NOW()
                AND fb.status = 'approved'
                ORDER BY fb.start_time ASC
                LIMIT ?";

        return $this->db->query($sql, [$limit])->fetchAll();
    }

    public function getBookingStats()
    {
        $sql = "SELECT 
                COUNT(*) as total_bookings,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_bookings,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_bookings,
                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected_bookings,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_bookings
                FROM {$this->table}";

        return $this->db->query($sql)->fetch();
    }

    public function checkAvailability($facilityId, $startTime, $endTime, $excludeId = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}
                WHERE facility_id = ?
                AND status = 'approved'
                AND (
                    (start_time BETWEEN ? AND ?)
                    OR (end_time BETWEEN ? AND ?)
                    OR (start_time <= ? AND end_time >= ?)
                )";

        $params = [$facilityId, $startTime, $endTime, $startTime, $endTime, $startTime, $endTime];

        if ($excludeId) {
            $sql .= " AND id != ?";
            $params[] = $excludeId;
        }

        $result = $this->db->query($sql, $params)->fetch();
        return $result['count'] === 0;
    }
} 