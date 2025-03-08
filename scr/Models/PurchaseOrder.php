<?php

namespace App\Models;

use App\Core\Model;

class PurchaseOrder extends Model
{
    protected $table = 'purchase_orders';

    public function getWithDetails($id)
    {
        $sql = "SELECT po.*, 
                s.name as supplier_name,
                s.contact_person as supplier_contact,
                s.email as supplier_email,
                s.phone as supplier_phone,
                u.name as created_by_name,
                a.name as approved_by_name
                FROM {$this->table} po
                LEFT JOIN suppliers s ON po.supplier_id = s.id
                LEFT JOIN users u ON po.created_by = u.id
                LEFT JOIN users a ON po.approved_by = a.id
                WHERE po.id = ?";

        $order = $this->db->query($sql, [$id])->fetch();
        
        if ($order) {
            $order['items'] = $this->getOrderItems($id);
        }

        return $order;
    }

    public function getAllWithDetails($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT po.*, 
                s.name as supplier_name,
                u.name as created_by_name,
                a.name as approved_by_name
                FROM {$this->table} po
                LEFT JOIN suppliers s ON po.supplier_id = s.id
                LEFT JOIN users u ON po.created_by = u.id
                LEFT JOIN users a ON po.approved_by = a.id
                ORDER BY po.created_at DESC
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$perPage, $offset])->fetchAll();
    }

    public function getOrderItems($orderId)
    {
        $sql = "SELECT poi.*, 
                i.name as item_name,
                i.sku as item_sku,
                i.unit as item_unit
                FROM purchase_order_items poi
                LEFT JOIN inventory i ON poi.inventory_id = i.id
                WHERE poi.purchase_order_id = ?
                ORDER BY poi.id";

        return $this->db->query($sql, [$orderId])->fetchAll();
    }

    public function create($data)
    {
        $this->db->beginTransaction();

        try {
            // Insert purchase order
            $sql = "INSERT INTO {$this->table} (
                        po_number, supplier_id, total_amount, status,
                        notes, created_by, approved_by
                    ) VALUES (?, ?, ?, ?, ?, ?, ?)";

            $orderId = $this->db->query($sql, [
                $data['po_number'],
                $data['supplier_id'],
                $data['total_amount'],
                $data['status'] ?? 'draft',
                $data['notes'],
                $data['created_by'],
                $data['approved_by'] ?? null
            ]);

            // Insert order items
            foreach ($data['items'] as $item) {
                $sql = "INSERT INTO purchase_order_items (
                            purchase_order_id, inventory_id, quantity,
                            unit_price, total_price
                        ) VALUES (?, ?, ?, ?, ?)";

                $this->db->query($sql, [
                    $orderId,
                    $item['inventory_id'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['total_price']
                ]);
            }

            $this->db->commit();
            return $orderId;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function update($id, $data)
    {
        $this->db->beginTransaction();

        try {
            // Update purchase order
            $sql = "UPDATE {$this->table} SET
                        supplier_id = ?, total_amount = ?, status = ?,
                        notes = ?, approved_by = ?
                    WHERE id = ?";

            $this->db->query($sql, [
                $data['supplier_id'],
                $data['total_amount'],
                $data['status'],
                $data['notes'],
                $data['approved_by'],
                $id
            ]);

            // Delete existing items
            $sql = "DELETE FROM purchase_order_items WHERE purchase_order_id = ?";
            $this->db->query($sql, [$id]);

            // Insert updated items
            foreach ($data['items'] as $item) {
                $sql = "INSERT INTO purchase_order_items (
                            purchase_order_id, inventory_id, quantity,
                            unit_price, total_price
                        ) VALUES (?, ?, ?, ?, ?)";

                $this->db->query($sql, [
                    $id,
                    $item['inventory_id'],
                    $item['quantity'],
                    $item['unit_price'],
                    $item['total_price']
                ]);
            }

            $this->db->commit();
            return true;
        } catch (\Exception $e) {
            $this->db->rollBack();
            return false;
        }
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
        // Check if order can be deleted (only draft or cancelled orders)
        $sql = "SELECT status FROM {$this->table} WHERE id = ?";
        $order = $this->db->query($sql, [$id])->fetch();

        if (!$order || !in_array($order['status'], ['draft', 'cancelled'])) {
            return false;
        }

        $this->db->beginTransaction();

        try {
            // Delete order items
            $sql = "DELETE FROM purchase_order_items WHERE purchase_order_id = ?";
            $this->db->query($sql, [$id]);

            // Delete purchase order
            $sql = "DELETE FROM {$this->table} WHERE id = ?";
            $this->db->query($sql, [$id]);

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
        
        $sql = "SELECT po.*, 
                s.name as supplier_name,
                u.name as created_by_name,
                a.name as approved_by_name
                FROM {$this->table} po
                LEFT JOIN suppliers s ON po.supplier_id = s.id
                LEFT JOIN users u ON po.created_by = u.id
                LEFT JOIN users a ON po.approved_by = a.id
                WHERE po.po_number LIKE ? OR s.name LIKE ?
                ORDER BY po.created_at DESC
                LIMIT ? OFFSET ?";

        return $this->db->query($sql, [$searchTerm, $searchTerm, $perPage, $offset])->fetchAll();
    }

    public function getPurchaseOrderStats()
    {
        $sql = "SELECT 
                COUNT(*) as total_orders,
                SUM(CASE WHEN status = 'draft' THEN 1 ELSE 0 END) as draft_orders,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_orders,
                SUM(CASE WHEN status = 'approved' THEN 1 ELSE 0 END) as approved_orders,
                SUM(CASE WHEN status = 'ordered' THEN 1 ELSE 0 END) as ordered_orders,
                SUM(CASE WHEN status = 'received' THEN 1 ELSE 0 END) as received_orders,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_orders,
                SUM(total_amount) as total_amount
                FROM {$this->table}";

        return $this->db->query($sql)->fetch();
    }
} 