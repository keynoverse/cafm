<?php

namespace App\Models;

use App\Core\Model;

class Location extends Model
{
    protected $table = 'locations';

    public function getWithDetails($id)
    {
        $sql = "SELECT l.*, 
                r.name as room_name,
                r.id as room_id,
                f.name as floor_name,
                f.id as floor_id,
                b.name as building_name,
                b.id as building_id
                FROM {$this->table} l
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                WHERE l.id = ?";

        return $this->db->query($sql, [$id])->fetch();
    }

    public function getAllWithDetails($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT l.*, 
                r.name as room_name,
                r.id as room_id,
                f.name as floor_name,
                f.id as floor_id,
                b.name as building_name,
                b.id as building_id
                FROM {$this->table} l
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                ORDER BY b.name, f.name, r.name, l.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$perPage, $offset])->fetchAll();
    }

    public function getLocationsByRoom($roomId)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE room_id = ?
                ORDER BY name";

        return $this->db->query($sql, [$roomId])->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (
                    name, room_id, type, description, status
                ) VALUES (?, ?, ?, ?, ?)";

        return $this->db->query($sql, [
            $data['name'],
            $data['room_id'],
            $data['type'],
            $data['description'] ?? null,
            $data['status'] ?? 'active'
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET
                    name = ?, room_id = ?, type = ?, description = ?, status = ?
                WHERE id = ?";

        return $this->db->query($sql, [
            $data['name'],
            $data['room_id'],
            $data['type'],
            $data['description'] ?? null,
            $data['status'],
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
        
        $sql = "SELECT l.*, 
                r.name as room_name,
                r.id as room_id,
                f.name as floor_name,
                f.id as floor_id,
                b.name as building_name,
                b.id as building_id
                FROM {$this->table} l
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                WHERE l.name LIKE ? OR r.name LIKE ? OR f.name LIKE ? OR b.name LIKE ?
                ORDER BY b.name, f.name, r.name, l.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [
            $searchTerm, $searchTerm, $searchTerm, $searchTerm, $perPage, $offset
        ])->fetchAll();
    }

    public function getLocationStats()
    {
        $sql = "SELECT 
                COUNT(*) as total_locations,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_locations,
                SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_locations,
                COUNT(DISTINCT room_id) as total_rooms,
                COUNT(DISTINCT r.floor_id) as total_floors,
                COUNT(DISTINCT f.building_id) as total_buildings
                FROM {$this->table} l
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id";

        return $this->db->query($sql)->fetch();
    }

    public function getLocationHierarchy($id)
    {
        $sql = "SELECT 
                l.*,
                r.name as room_name,
                r.id as room_id,
                f.name as floor_name,
                f.id as floor_id,
                b.name as building_name,
                b.id as building_id
                FROM {$this->table} l
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                WHERE l.id = ?";

        return $this->db->query($sql, [$id])->fetch();
    }
} 