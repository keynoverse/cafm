<?php

namespace App\Core;

abstract class Model
{
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function find($id)
    {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?",
            [$id]
        );
    }

    public function all()
    {
        return $this->db->fetchAll("SELECT * FROM {$this->table}");
    }

    public function create(array $data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, array $data)
    {
        return $this->db->update(
            $this->table,
            $data,
            "{$this->primaryKey} = ?",
            [$id]
        );
    }

    public function delete($id)
    {
        return $this->db->delete(
            $this->table,
            "{$this->primaryKey} = ?",
            [$id]
        );
    }

    public function where($conditions, $params = [])
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE $conditions",
            $params
        );
    }

    public function findOne($conditions, $params = [])
    {
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE $conditions LIMIT 1",
            $params
        );
    }

    public function count($conditions = null, $params = [])
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        if ($conditions) {
            $sql .= " WHERE $conditions";
        }
        $result = $this->db->fetch($sql, $params);
        return $result['count'];
    }

    public function paginate($page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $items = $this->db->fetchAll(
            "SELECT * FROM {$this->table} LIMIT ? OFFSET ?",
            [$perPage, $offset]
        );
        $total = $this->count();

        return [
            'items' => $items,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }
} 