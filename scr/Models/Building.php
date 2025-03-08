<?php

namespace App\Models;

use App\Core\Model;

class Building extends Model
{
    protected $table = 'buildings';

    public function getWithDetails($id)
    {
        $sql = "SELECT b.*, 
                COUNT(DISTINCT f.id) as total_floors,
                COUNT(DISTINCT r.id) as total_rooms,
                COUNT(DISTINCT l.id) as total_locations
                FROM {$this->table} b
                LEFT JOIN floors f ON f.building_id = b.id
                LEFT JOIN rooms r ON r.floor_id = f.id
                LEFT JOIN locations l ON l.room_id = r.id
                WHERE b.id = ?
                GROUP BY b.id";

        return $this->db->query($sql, [$id])->fetch();
    }

    public function getAllWithDetails($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT b.*, 
                COUNT(DISTINCT f.id) as total_floors,
                COUNT(DISTINCT r.id) as total_rooms,
                COUNT(DISTINCT l.id) as total_locations
                FROM {$this->table} b
                LEFT JOIN floors f ON f.building_id = b.id
                LEFT JOIN rooms r ON r.floor_id = f.id
                LEFT JOIN locations l ON l.room_id = r.id
                GROUP BY b.id
                ORDER BY b.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$perPage, $offset])->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (
                    name, address, city, state, country, postal_code,
                    description, status
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->db->query($sql, [
            $data['name'],
            $data['address'],
            $data['city'],
            $data['state'],
            $data['country'],
            $data['postal_code'],
            $data['description'] ?? null,
            $data['status'] ?? 'active'
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET
                    name = ?, address = ?, city = ?, state = ?, 
                    country = ?, postal_code = ?, description = ?, status = ?
                WHERE id = ?";

        return $this->db->query($sql, [
            $data['name'],
            $data['address'],
            $data['city'],
            $data['state'],
            $data['country'],
            $data['postal_code'],
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
        
        $sql = "SELECT b.*, 
                COUNT(DISTINCT f.id) as total_floors,
                COUNT(DISTINCT r.id) as total_rooms,
                COUNT(DISTINCT l.id) as total_locations
                FROM {$this->table} b
                LEFT JOIN floors f ON f.building_id = b.id
                LEFT JOIN rooms r ON r.floor_id = f.id
                LEFT JOIN locations l ON l.room_id = r.id
                WHERE b.name LIKE ? OR b.address LIKE ? OR b.city LIKE ? 
                    OR b.state LIKE ? OR b.country LIKE ? OR b.postal_code LIKE ?
                GROUP BY b.id
                ORDER BY b.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [
            $searchTerm, $searchTerm, $searchTerm, $searchTerm, 
            $searchTerm, $searchTerm, $perPage, $offset
        ])->fetchAll();
    }

    public function getBuildingStats()
    {
        $sql = "SELECT 
                COUNT(*) as total_buildings,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_buildings,
                SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_buildings,
                COUNT(DISTINCT city) as total_cities,
                COUNT(DISTINCT state) as total_states
                FROM {$this->table}";

        return $this->db->query($sql)->fetch();
    }

    public function getActiveBuildings()
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE status = 'active'
                ORDER BY name";

        return $this->db->query($sql)->fetchAll();
    }

    public function getBuildingHierarchy($id)
    {
        $sql = "SELECT 
                b.*,
                f.id as floor_id,
                f.name as floor_name,
                r.id as room_id,
                r.name as room_name,
                l.id as location_id,
                l.name as location_name,
                l.type as location_type,
                l.status as location_status
                FROM {$this->table} b
                LEFT JOIN floors f ON f.building_id = b.id
                LEFT JOIN rooms r ON r.floor_id = f.id
                LEFT JOIN locations l ON l.room_id = r.id
                WHERE b.id = ?
                ORDER BY f.name, r.name, l.name";

        $results = $this->db->query($sql, [$id])->fetchAll();
        
        $hierarchy = [
            'id' => $results[0]['id'],
            'name' => $results[0]['name'],
            'address' => $results[0]['address'],
            'city' => $results[0]['city'],
            'state' => $results[0]['state'],
            'country' => $results[0]['country'],
            'postal_code' => $results[0]['postal_code'],
            'description' => $results[0]['description'],
            'status' => $results[0]['status'],
            'floors' => []
        ];
        
        foreach ($results as $row) {
            if ($row['floor_id'] && !isset($hierarchy['floors'][$row['floor_id']])) {
                $hierarchy['floors'][$row['floor_id']] = [
                    'id' => $row['floor_id'],
                    'name' => $row['floor_name'],
                    'rooms' => []
                ];
            }
            
            if ($row['room_id'] && !isset($hierarchy['floors'][$row['floor_id']]['rooms'][$row['room_id']])) {
                $hierarchy['floors'][$row['floor_id']]['rooms'][$row['room_id']] = [
                    'id' => $row['room_id'],
                    'name' => $row['room_name'],
                    'locations' => []
                ];
            }
            
            if ($row['location_id']) {
                $hierarchy['floors'][$row['floor_id']]['rooms'][$row['room_id']]['locations'][] = [
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