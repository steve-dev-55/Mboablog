<?php

namespace App\Models;

class TagModel extends BaseModel
{
    protected $table = 'tags';

    public function getTagsWithPostCount()
    {
        $sql = "SELECT t.*, COUNT(pt.post_id) as post_count 
                FROM {$this->table} t 
                LEFT JOIN post_tags pt ON t.id = pt.tag_id 
                GROUP BY t.id 
                ORDER BY t.name ASC";
        return $this->query($sql)->fetchAll();
    }

    public function getTagsForPost($postId)
    {
        $sql = "SELECT t.* FROM {$this->table} t 
                JOIN post_tags pt ON t.id = pt.tag_id 
                WHERE pt.post_id = ?";
        return $this->query($sql, [$postId])->fetchAll();
    }

    public function attachTagsToPost($postId, $tagId)
    {
        $sql = "INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?)";
        return  $this->query($sql, [$postId, $tagId]);
    }

    public function detachTagsFromPost($postId)
    {
        $sql = "DELETE FROM post_tags WHERE post_id = ?";
        return $this->query($sql, [$postId]);
    }
}
