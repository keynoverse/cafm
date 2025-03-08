<?php

namespace App\Models;

use App\Core\Model;

class Room extends Model
{
    protected $table = 'rooms';

    public function getWithDetails($id)
    {
        $sql = "SELECT r.*, 
                f.name as floor_name,
                f.id as floor_id,
                b.name as building_name,
                b.id as building_id,
                COUNT(DISTINCT l.id) as total_locations
                FROM {$this->table} r
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                LEFT JOIN locations l ON l.room_id = r.id
                WHERE r.id = ?
                GROUP BY r.id";

        return $this->db->query($sql, [$id])->fetch();
    }

    public function getAllWithDetails($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT r.*, 
                f.name as floor_name,
                f.id as floor_id,
                b.name as building_name,
                b.id as building_id,
                COUNT(DISTINCT l.id) as total_locations
                FROM {$this->table} r
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                LEFT JOIN locations l ON l.room_id = r.id
                GROUP BY r.id
                ORDER BY b.name, f.name, r.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$perPage, $offset])->fetchAll();
    }

    public function getRoomsByFloor($floorId)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE floor_id = ?
                ORDER BY name";

        return $this->db->query($sql, [$floorId])->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (
                    name, floor_id, description, status
                ) VALUES (?, ?, ?, ?)";

        return $this->db->query($sql, [
            $data['name'],
            $data['floor_id'],
            $data['description'] ?? null,
            $data['status'] ?? 'active'
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET
                    name = ?, floor_id = ?, description = ?, status = ?
                WHERE id = ?";

        return $this->db->query($sql, [
            $data['name'],
            $data['floor_id'],
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
        
        $sql = "SELECT r.*, 
                f.name as floor_name,
                f.id as floor_id,
                b.name as building_name,
                b.id as building_id,
                COUNT(DISTINCT l.id) as total_locations
                FROM {$this->table} r
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                LEFT JOIN locations l ON l.room_id = r.id
                WHERE r.name LIKE ? OR f.name LIKE ? OR b.name LIKE ?
                GROUP BY r.id
                ORDER BY b.name, f.name, r.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [
            $searchTerm, $searchTerm, $searchTerm, $perPage, $offset
        ])->fetchAll();
    }

    public function getRoomStats()
    {
        $sql = "SELECT 
                COUNT(*) as total_rooms,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_rooms,
                SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_rooms,
                COUNT(DISTINCT floor_id) as total_floors,
                COUNT(DISTINCT f.building_id) as total_buildings
                FROM {$this->table} r
                LEFT JOIN floors f ON f.id = r.floor_id";

        return $this->db->query($sql)->fetch();
    }

    public function getRoomHierarchy($id)
    {
        $sql = "SELECT 
                r.*,
                f.name as floor_name,
                f.id as floor_id,
                b.name as building_name,
                b.id as building_id,
                l.id as location_id,
                l.name as location_name,
                l.type as location_type,
                l.status as location_status
                FROM {$this->table} r
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                LEFT JOIN locations l ON l.room_id = r.id
                WHERE r.id = ?
                ORDER BY l.name";

        $results = $this->db->query($sql, [$id])->fetchAll();
        
        $hierarchy = [
            'id' => $results[0]['id'],
            'name' => $results[0]['name'],
            'floor_id' => $results[0]['floor_id'],
            'floor_name' => $results[0]['floor_name'],
            'building_id' => $results[0]['building_id'],
            'building_name' => $results[0]['building_name'],
            'description' => $results[0]['description'],
            'status' => $results[0]['status'],
            'locations' => []
        ];
        
        foreach ($results as $row) {
            if ($row['location_id']) {
                $hierarchy['locations'][] = [
                    'id' => $row['location_id'],
                    'name' => $row['location_name'],
                    'type' => $row['location_type'],
                    'status' => $row['location_status']
                ];
            }
        }
        
        return $hierarchy;
    }
} 