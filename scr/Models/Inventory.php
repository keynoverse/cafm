<?php

namespace App\Models;

use App\Core\Model;

class Inventory extends Model
{
    protected $table = 'inventory';

    public function getWithDetails($id)
    {
        $sql = "SELECT i.*, 
                ac.name as category_name,
                l.name as location_name,
                s.name as supplier_name,
                u.name as creator_name
                FROM {$this->table} i
                LEFT JOIN asset_categories ac ON i.category_id = ac.id
                LEFT JOIN locations l ON i.location_id = l.id
                LEFT JOIN suppliers s ON i.supplier_id = s.id
                LEFT JOIN users u ON i.created_by = u.id
                WHERE i.id = ?";

        $item = $this->db->query($sql, [$id])->fetch();

        if ($item) {
            $item['transactions'] = $this->getTransactions($id);
        }

        return $item;
    }

    public function getAllWithDetails($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT i.*, 
                ac.name as category_name,
                l.name as location_name,
                s.name as supplier_name
                FROM {$this->table} i
                LEFT JOIN asset_categories ac ON i.category_id = ac.id
                LEFT JOIN locations l ON i.location_id = l.id
                LEFT JOIN suppliers s ON i.supplier_id = s.id
                ORDER BY i.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$perPage, $offset])->fetchAll();
    }

    public function getByCategory($categoryId, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT i.*, 
                ac.name as category_name,
                l.name as location_name,
                s.name as supplier_name
                FROM {$this->table} i
                LEFT JOIN asset_categories ac ON i.category_id = ac.id
                LEFT JOIN locations l ON i.location_id = l.id
                LEFT JOIN suppliers s ON i.supplier_id = s.id
                WHERE i.category_id = ?
                ORDER BY i.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$categoryId, $perPage, $offset])->fetchAll();
    }

    public function getByLocation($locationId, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT i.*, 
                ac.name as category_name,
                l.name as location_name,
                s.name as supplier_name
                FROM {$this->table} i
                LEFT JOIN asset_categories ac ON i.category_id = ac.id
                LEFT JOIN locations l ON i.location_id = l.id
                LEFT JOIN suppliers s ON i.supplier_id = s.id
                WHERE i.location_id = ?
                ORDER BY i.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$locationId, $perPage, $offset])->fetchAll();
    }

    public function getLowStockItems()
    {
        $sql = "SELECT i.*, 
                ac.name as category_name,
                l.name as location_name
                FROM {$this->table} i
                LEFT JOIN asset_categories ac ON i.category_id = ac.id
                LEFT JOIN locations l ON i.location_id = l.id
                WHERE i.quantity <= i.min_quantity
                ORDER BY i.quantity ASC
                LIMIT 10";

        return $this->db->query($sql)->fetchAll();
    }

    public function updateQuantity($id, $quantity, $type = 'add')
    {
        $item = $this->get($id);
        
        if (!$item) {
            return false;
        }

        $this->db->beginTransaction();

        try {
            $newQuantity = $type === 'add' 
                ? $item['quantity'] + $quantity 
                : $item['quantity'] - $quantity;

            if ($newQuantity < 0) {
                throw new \Exception('Insufficient stock');
            }

            $sql = "UPDATE {$this->table} SET quantity = ? WHERE id = ?";
            $this->db->query($sql, [$newQuantity, $id]);

            $this->logTransaction($id, $type, $quantity, $newQuantity);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function search($query, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $searchTerm = "%{$query}%";
        
        $sql = "SELECT i.*, 
                ac.name as category_name,
                l.name as location_name,
                s.name as supplier_name
                FROM {$this->table} i
                LEFT JOIN asset_categories ac ON i.category_id = ac.id
                LEFT JOIN locations l ON i.location_id = l.id
                LEFT JOIN suppliers s ON i.supplier_id = s.id
                WHERE i.name LIKE ? OR i.sku LIKE ?
                ORDER BY i.name
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$searchTerm, $searchTerm, $perPage, $offset])->fetchAll();
    }

    private function getTransactions($itemId)
    {
        $sql = "SELECT it.*, u.name as user_name
                FROM inventory_transactions it
                LEFT JOIN users u ON it.user_id = u.id
                WHERE it.item_id = ?
                ORDER BY it.created_at DESC";

        return $this->db->query($sql, [$itemId])->fetchAll();
    }

    private function logTransaction($itemId, $type, $quantity, $newQuantity)
    {
        $sql = "INSERT INTO inventory_transactions (item_id, user_id, type, quantity, new_quantity)
                VALUES (?, ?, ?, ?, ?)";

        return $this->db->query($sql, [
            $itemId,
            $_SESSION['user_id'],
            $type,
            $quantity,
            $newQuantity
        ]);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (
                    name, sku, category_id, location_id, supplier_id,
                    quantity, min_quantity, unit, price, description, created_by
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        return $this->db->query($sql, [
            $data['name'],
            $data['sku'],
            $data['category_id'] ?: null,
            $data['location_id'] ?: null,
            $data['supplier_id'] ?: null,
            $data['quantity'],
            $data['min_quantity'],
            $data['unit'],
            $data['price'],
            $data['description'],
            $data['created_by']
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE {$this->table} SET
                    name = ?, sku = ?, category_id = ?, location_id = ?,
                    supplier_id = ?, quantity = ?, min_quantity = ?, unit = ?,
                    price = ?, description = ?
                WHERE id = ?";

        return $this->db->query($sql, [
            $data['name'],
            $data['sku'],
            $data['category_id'] ?: null,
            $data['location_id'] ?: null,
            $data['supplier_id'] ?: null,
            $data['quantity'],
            $data['min_quantity'],
            $data['unit'],
            $data['price'],
            $data['description'],
            $id
        ]);
    }

    public function delete($id)
    {
        $this->db->beginTransaction();

        try {
            // Delete related transactions first
            $sql = "DELETE FROM inventory_transactions WHERE item_id = ?";
            $this->db->query($sql, [$id]);

            // Delete the item
            $sql = "DELETE FROM {$this->table} WHERE id = ?";
            $this->db->query($sql, [$id]);

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }
} 