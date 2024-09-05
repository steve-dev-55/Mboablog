<?php

namespace App\Models;

class CategoryModel extends BaseModel
{
    protected $table = 'categories';

    public function getCategoriesWithPostCount()
    {
        $sql = "SELECT c.*, COUNT(p.id) as post_count 
                FROM {$this->table} c 
                LEFT JOIN posts p ON c.id = p.category_id
                WHERE p.status = ?
                GROUP BY c.id 
                ORDER BY c.name ASC";
        return $this->query($sql, ['published'])->fetchAll();
    }
}
