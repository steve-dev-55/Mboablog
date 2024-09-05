<?php

namespace App\Models;

class UserModel extends BaseModel
{
    protected $table = 'users';

    public function findByEmail($email)
    {
        return $this->getWhere('email = ?', [$email], '', 1);
    }

    public function findByUsername($username)
    {
        return $this->getWhere('username = ?', [$username], '', 1);
    }

    public function verifyPassword($userId, $password)
    {
        $user = $this->getById($userId);
        return $user && password_verify($password, $user['password']);
    }

    public function create($data)
    {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        return parent::create($data);
    }

    public function updateLastLogin($userId)
    {
        $sql = "UPDATE {$this->table} SET updated_at = NOW() WHERE id = ?";
        return $this->query($sql, [$userId]);
    }

    public function getUserRoleById($userId)
    {
        $user = $this->getById($userId);
        return $user['role'];
    }

    public function countPostByUser()
    {
        $sql = "SELECT u.*, COUNT(p.id) as post_count 
                FROM {$this->table} u 
                LEFT JOIN posts p ON u.id = p.user_id
                WHERE p.status = ?
                GROUP BY u.id 
                ORDER BY u.username ASC";
        return $this->query($sql, ['published'])->fetchAll();
    }

    public function countPostByUserId($userId)
    {
        $sql = "SELECT COUNT(p.id) 
                FROM {$this->table} u 
                LEFT JOIN posts p ON u.id = p.user_id
                WHERE p.status = ? AND p.user_id = ?";
        return $this->query($sql, ['published', $userId])->fetchColumn();
    }
}
