<?php

namespace App\Models;

use App\Core\Database;
use PDO;
use PDOException;

class BaseModel
{
    protected $db;
    protected $table;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    // Récupérer tous les enregistrements
    public function getAll($order = 'id ASC', $limit = null, $offset = null)
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY {$order}";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
            if ($offset) {
                $sql .= " OFFSET {$offset}";
            }
        }
        return $this->query($sql)->fetchAll();
    }

    // Récupérer un enregistrement par ID
    public function getById($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        return $this->query($sql, [':id' => $id])->fetch();
    }

    // Créer un nouvel enregistrement
    public function create($data)
    {
        $keys = array_keys($data);
        $fields = implode(', ', $keys);
        $placeholders = ':' . implode(', :', $keys);

        $sql = "INSERT INTO {$this->table} ({$fields}) VALUES ({$placeholders})";

        $this->query($sql, $data);
        return $this->db->lastInsertId();
    }

    // Mettre à jour un enregistrement
    public function update($id, $data)
    {
        $fields = '';
        foreach ($data as $key => $value) {
            $fields .= "{$key} = :{$key}, ";
        }
        $fields = rtrim($fields, ', ');

        $sql = "UPDATE {$this->table} SET {$fields} WHERE id = :id";
        $data['id'] = $id;

        return $this->query($sql, $data)->rowCount();
    }

    // Supprimer un enregistrement
    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->query($sql, [':id' => $id])->rowCount();
    }

    // Compter le nombre total d'enregistrements
    public function count()
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        return $this->query($sql)->fetchColumn();
    }

    // Recherche générique
    public function search($keyword, $fields)
    {
        $conditions = [];
        $params = [];
        foreach ($fields as $field) {
            $conditions[] = "{$field} LIKE :keyword";
            $params[':keyword'] = "%{$keyword}%";
        }
        $whereClause = implode(' OR ', $conditions);

        $sql = "SELECT * FROM {$this->table} WHERE {$whereClause}";
        return $this->query($sql, $params)->fetchAll();
    }

    // Exécuter une requête personnalisée
    protected function query($sql, $params = [])
    {
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            // Gérer l'erreur (log, throw, etc.)
            throw new \Exception("Erreur de base de données : " . $e->getMessage());
        }
    }

    // Vérifier si un enregistrement existe
    public function exists($keyword, $fields)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE $fields = :{$fields}";
        return (bool) $this->query($sql, [":{$fields}" => $keyword])->fetchColumn();
    }

    // Récupérer plusieurs enregistrements par leurs table name
    public function getByTableName($keyword, $tableName)
    {
        $sql = "SELECT * FROM {$this->table} WHERE $tableName = :{$tableName}";
        return $this->query($sql, [":{$tableName}" => $keyword])->fetch();
    }

    // Récupérer plusieurs enregistrements par leurs IDs
    public function getByIds(array $ids)
    {
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        $sql = "SELECT * FROM {$this->table} WHERE id IN ({$placeholders})";
        return $this->query($sql, $ids)->fetchAll();
    }

    // Récupérer des enregistrements avec une clause WHERE personnalisée
    public function getWhere($conditions, $params = [], $order = 'id ASC', $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$conditions} ";
        if ($order) {
            $sql .= " ORDER BY {$order}";
        }
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }

        return $this->query($sql, $params)->fetchAll();
    }
}
