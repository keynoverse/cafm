<?php

namespace App\Models;

use App\Core\Model;

class Asset extends Model
{
    protected $table = 'assets';

    public function getWithDetails($id)
    {
        $sql = "SELECT a.*, 
                l.name as location_name,
                l.id as location_id,
                r.name as room_name,
                r.id as room_id,
                f.name as floor_name,
                f.id as floor_id,
                b.name as building_name,
                b.id as building_id
                FROM {$this->table} a
                LEFT JOIN locations l ON l.id = a.location_id
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                WHERE a.id = ?";

        return $this->db->query($sql, [$id])->fetch();
    }

    public function getAllWithDetails($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT a.*, 
                l.name as location_name,
                l.id as location_id,
                r.name as room_name,
                r.id as room_id,
                f.name as floor_name,
                f.id as floor_id,
                b.name as building_name,
                b.id as building_id
                FROM {$this->table} a
                LEFT JOIN locations l ON l.id = a.location_id
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                ORDER BY b.name, f.name, r.name, l.name, a.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$perPage, $offset])->fetchAll();
    }

    public function getAssetsByLocation($locationId)
    {
        $sql = "SELECT * FROM {$this->table}
                WHERE location_id = ?
                ORDER BY name";

        return $this->db->query($sql, [$locationId])->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (
                    name, location_id, type, model, serial_number,
                    purchase_date, purchase_price, warranty_expiry,
                    status, description
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->db->query($sql, [
            $data['name'],
            $data['location_id'],
            $data['type'],
            $data['model'] ?? null,
            $data['serial_number'] ?? null,
            $data['purchase_date'] ?? null,
            $data['purchase_price'] ?? null,
            $data['warranty_expiry'] ?? null,
            $data['status'] ?? 'active',
            $data['description'] ?? null
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET
                    name = ?, location_id = ?, type = ?, model = ?,
                    serial_number = ?, purchase_date = ?, purchase_price = ?,
                    warranty_expiry = ?, status = ?, description = ?
                WHERE id = ?";

        return $this->db->query($sql, [
            $data['name'],
            $data['location_id'],
            $data['type'],
            $data['model'] ?? null,
            $data['serial_number'] ?? null,
            $data['purchase_date'] ?? null,
            $data['purchase_price'] ?? null,
            $data['warranty_expiry'] ?? null,
            $data['status'],
            $data['description'] ?? null,
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
        
        $sql = "SELECT a.*, 
                l.name as location_name,
                l.id as location_id,
                r.name as room_name,
                r.id as room_id,
                f.name as floor_name,
                f.id as floor_id,
                b.name as building_name,
                b.id as building_id
                FROM {$this->table} a
                LEFT JOIN locations l ON l.id = a.location_id
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                WHERE a.name LIKE ? OR a.model LIKE ? OR a.serial_number LIKE ?
                    OR l.name LIKE ? OR r.name LIKE ? OR f.name LIKE ? OR b.name LIKE ?
                ORDER BY b.name, f.name, r.name, l.name, a.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [
            $searchTerm, $searchTerm, $searchTerm,
            $searchTerm, $searchTerm, $searchTerm, $searchTerm,
            $perPage, $offset
        ])->fetchAll();
    }

    public function getAssetStats()
    {
        $sql = "SELECT 
                COUNT(*) as total_assets,
                SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_assets,
                SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_assets,
                SUM(CASE WHEN status = 'maintenance' THEN 1 ELSE 0 END) as maintenance_assets,
                COUNT(DISTINCT location_id) as total_locations,
                COUNT(DISTINCT l.room_id) as total_rooms,
                COUNT(DISTINCT r.floor_id) as total_floors,
                COUNT(DISTINCT f.building_id) as total_buildings
                FROM {$this->table} a
                LEFT JOIN locations l ON l.id = a.location_id
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id";

        return $this->db->query($sql)->fetch();
    }

    public function getAssetHierarchy($id)
    {
        $sql = "SELECT 
                a.*,
                l.name as location_name,
                l.id as location_id,
                r.name as room_name,
                r.id as room_id,
                f.name as floor_name,
                f.id as floor_id,
                b.name as building_name,
                b.id as building_id
                FROM {$this->table} a
                LEFT JOIN locations l ON l.id = a.location_id
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                WHERE a.id = ?";

        return $this->db->query($sql, [$id])->fetch();
    }

    public function getAssetsByType($type)
    {
        $sql = "SELECT a.*, 
                l.name as location_name,
                r.name as room_name,
                f.name as floor_name,
                b.name as building_name
                FROM {$this->table} a
                LEFT JOIN locations l ON l.id = a.location_id
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                WHERE a.type = ?
                ORDER BY b.name, f.name, r.name, l.name, a.name";

        return $this->db->query($sql, [$type])->fetchAll();
    }

    public function getAssetsByStatus($status)
    {
        $sql = "SELECT a.*, 
                l.name as location_name,
                r.name as room_name,
                f.name as floor_name,
                b.name as building_name
                FROM {$this->table} a
                LEFT JOIN locations l ON l.id = a.location_id
                LEFT JOIN rooms r ON r.id = l.room_id
                LEFT JOIN floors f ON f.id = r.floor_id
                LEFT JOIN buildings b ON b.id = f.building_id
                WHERE a.status = ?
                ORDER BY b.name, f.name, r.name, l.name, a.name";

        return $this->db->query($sql, [$status])->fetchAll();
    }
} 