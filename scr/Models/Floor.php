<?php

namespace App\Models;

use App\Core\Model;

class Floor extends Model
{
    protected $table = 'floors';

    public function getWithDetails($id)
    {
        $sql = "SELECT f.*, 
                b.name as building_name,
                COUNT(DISTINCT r.id) as total_rooms,
                COUNT(DISTINCT l.id) as total_locations
                FROM {$this->table} f
                LEFT JOIN buildings b ON b.id = f.building_id
                LEFT JOIN rooms r ON r.floor_id = f.id
                LEFT JOIN locations l ON l.room_id = r.id
                WHERE f.id = ?
                GROUP BY f.id";

        return $this->db->query($sql, [$id])->fetch();
    }

    public function getAllWithDetails($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT f.*, 
                b.name as building_name,
                COUNT(DISTINCT r.id) as total_rooms,
                COUNT(DISTINCT l.id) as total_locations
                FROM {$this->table} f
                LEFT JOIN buildings b ON b.id = f.building_id
                LEFT JOIN rooms r ON r.floor_id = f.id
                LEFT JOIN locations l ON l.room_id = r.id
                GROUP BY f.id
                ORDER BY b.name, f.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$perPage, $offset])->fetchAll();
    }

    public function getFloorsByBuilding($buildingId)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE building_id = ?
                ORDER BY name";

        return $this->db->query($sql, [$buildingId])->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (
                    name, building_id, description, status
                ) VALUES (?, ?, ?, ?)";

        return $this->db->query($sql, [
            $data['name'],
            $data['building_id'],
            $data['description'] ?? null,
            $data['status'] ?? 'active'
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET
                    name = ?, building_id = ?, description = ?, status = ?
                WHERE id = ?";

        return $this->db->query($sql, [
            $data['name'],
            $data['building_id'],
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
        
        $sql = "SELECT f.*, 
                b.name as building_name,
                COUNT(DISTINCT r.id) as total_rooms,
                COUNT(DISTINCT l.id) as total_locations
                FROM {$this->table} f
                LEFT JOIN buildings b ON b.id = f.building_id
                LEFT JOIN rooms r ON r.floor_id = f.id
                LEFT JOIN locations l ON l.room_id = r.id
                WHERE f.name LIKE ? OR b.name LIKE ?
                GROUP BY f.id
                ORDER BY b.name, f.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [
            $searchTerm, $searchTerm, $perPage, $offset
        ])->fetchAll();
    }

    public function getFloorStats()
    {
        $sql = "SELECT 
                COUNT(*) as total_floors,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_floors,
                SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_floors,
                COUNT(DISTINCT building_id) as total_buildings
                FROM {$this->table}";

        return $this->db->query($sql)->fetch();
    }

    public function getFloorHierarchy($id)
    {
        $sql = "SELECT 
                f.*,
                b.name as building_name,
                r.id as room_id,
                r.name as room_name,
                l.id as location_id,
                l.name as location_name,
                l.type as location_type,
                l.status as location_status
                FROM {$this->table} f
                LEFT JOIN buildings b ON b.id = f.building_id
                LEFT JOIN rooms r ON r.floor_id = f.id
                LEFT JOIN locations l ON l.room_id = r.id
                WHERE f.id = ?
                ORDER BY r.name, l.name";

        $results = $this->db->query($sql, [$id])->fetchAll();
        
        $hierarchy = [
            'id' => $results[0]['id'],
            'name' => $results[0]['name'],
            'building_id' => $results[0]['building_id'],
            'building_name' => $results[0]['building_name'],
            'description' => $results[0]['description'],
            'status' => $results[0]['status'],
            'rooms' => []
        ];
        
        foreach ($results as $row) {
            if ($row['room_id'] && !isset($hierarchy['rooms'][$row['room_id']])) {
                $hierarchy['rooms'][$row['room_id']] = [
                    'id' => $row['room_id'],
                    'name' => $row['room_name'],
                    'locations' => []
                ];
            }
            
            if ($row['location_id']) {
                $hierarchy['rooms'][$row['room_id']]['locations'][] = [
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