<?php

namespace App\Models;

use App\Core\Model;

class Supplier extends Model
{
    protected $table = 'suppliers';

    public function getWithDetails($id)
    {
        $sql = "SELECT s.*, 
                COUNT(DISTINCT i.id) as inventory_count,
                COUNT(DISTINCT po.id) as purchase_order_count
                FROM {$this->table} s
                LEFT JOIN inventory i ON s.id = i.supplier_id
                LEFT JOIN purchase_orders po ON s.id = po.supplier_id
                WHERE s.id = ?
                GROUP BY s.id";

        return $this->db->query($sql, [$id])->fetch();
    }

    public function getAllWithDetails($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT s.*, 
                COUNT(DISTINCT i.id) as inventory_count,
                COUNT(DISTINCT po.id) as purchase_order_count
                FROM {$this->table} s
                LEFT JOIN inventory i ON s.id = i.supplier_id
                LEFT JOIN purchase_orders po ON s.id = po.supplier_id
                GROUP BY s.id
                ORDER BY s.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$perPage, $offset])->fetchAll();
    }

    public function getInventoryItems($supplierId, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT i.*, 
                ac.name as category_name,
                l.name as location_name
                FROM inventory i
                LEFT JOIN asset_categories ac ON i.category_id = ac.id
                LEFT JOIN locations l ON i.location_id = l.id
                WHERE i.supplier_id = ?
                ORDER BY i.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$supplierId, $perPage, $offset])->fetchAll();
    }

    public function getPurchaseOrders($supplierId, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT po.*, 
                u.name as created_by_name,
                a.name as approved_by_name
                FROM purchase_orders po
                LEFT JOIN users u ON po.created_by = u.id
                LEFT JOIN users a ON po.approved_by = a.id
                WHERE po.supplier_id = ?
                ORDER BY po.created_at DESC
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$supplierId, $perPage, $offset])->fetchAll();
    }

    public function search($query, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $searchTerm = "%{$query}%";
        
        $sql = "SELECT s.*, 
                COUNT(DISTINCT i.id) as inventory_count,
                COUNT(DISTINCT po.id) as purchase_order_count
                FROM {$this->table} s
                LEFT JOIN inventory i ON s.id = i.supplier_id
                LEFT JOIN purchase_orders po ON s.id = po.supplier_id
                WHERE s.name LIKE ? OR s.contact_person LIKE ? OR s.email LIKE ?
                GROUP BY s.id
                ORDER BY s.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$searchTerm, $searchTerm, $searchTerm, $perPage, $offset])->fetchAll();
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (
                    name, contact_person, email, phone, address
                ) VALUES (?, ?, ?, ?, ?)";

        return $this->db->query($sql, [
            $data['name'],
            $data['contact_person'],
            $data['email'],
            $data['phone'],
            $data['address']
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET
                    name = ?, contact_person = ?, email = ?,
                    phone = ?, address = ?
                WHERE id = ?";

        return $this->db->query($sql, [
            $data['name'],
            $data['contact_person'],
            $data['email'],
            $data['phone'],
            $data['address'],
            $id
        ]);
    }

    public function delete($id)
    {
        // Check if supplier has any inventory items or purchase orders
        $sql = "SELECT 
                (SELECT COUNT(*) FROM inventory WHERE supplier_id = ?) as inventory_count,
                (SELECT COUNT(*) FROM purchase_orders WHERE supplier_id = ?) as purchase_order_count";
        
        $counts = $this->db->query($sql, [$id, $id])->fetch();

        if ($counts['inventory_count'] > 0 || $counts['purchase_order_count'] > 0) {
            return false; // Cannot delete supplier with related records
        }

        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        return $this->db->query($sql, [$id]);
    }

    public function getSupplierStats()
    {
        $sql = "SELECT 
                COUNT(*) as total_suppliers,
                COUNT(DISTINCT i.id) as total_inventory_items,
                COUNT(DISTINCT po.id) as total_purchase_orders,
                SUM(po.total_amount) as total_purchase_amount
                FROM {$this->table} s
                LEFT JOIN inventory i ON s.id = i.supplier_id
                LEFT JOIN purchase_orders po ON s.id = po.supplier_id";

        return $this->db->query($sql)->fetch();
    }
} 