<?php

namespace App\Models;

class PostModel extends BaseModel
{
    protected $table = 'posts';

    public function getPublishedPosts($limit = 10, $offset = 0)
    {
        return $this->getWhere('status = ?', ['published'], 'created_at DESC', $limit, $offset);
    }

    public function getByCategory($categoryId, $limit = 10, $offset = 0)
    {
        $sql = "SELECT p.*, c.name as category_name, u.username as author_name 
        FROM {$this->table} p
        JOIN categories c ON p.category_id = c.id
        JOIN users u ON p.user_id = u.id
        WHERE p.category_id = ? AND p.status = ?
        ORDER BY p.created_at DESC
        LIMIT ? OFFSET ?";

        return $this->query($sql, [$categoryId, 'published', $limit, $offset])->fetchAll();
    }

    public function getByUser($userId, $limit = 10, $offset = 0)
    {
        $sql = "SELECT p.*, c.name as category_name, u.username as author_name, u.bio, u.profile_picture 
        FROM {$this->table} p
        JOIN categories c ON p.category_id = c.id
        JOIN users u ON p.user_id = u.id
        WHERE p.user_id = ? AND p.status = ?
        ORDER BY p.created_at DESC
        LIMIT ? OFFSET ?";

        return $this->query($sql, [$userId, 'published', $limit, $offset])->fetchAll();
    }

    public function getPostWithCategoryAndAuthor($postId, $status = 'published')
    {
        $sql = "SELECT p.*, c.name as category_name, u.username as author_name, u.profile_picture, u.bio 
                FROM {$this->table} p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.user_id = u.id
                WHERE p.id = ? AND p.status = ?
                LIMIT 1";

        return $this->query($sql, [$postId, $status])->fetch();
    }

    public function getPostsWithCategoriesAndAuthors($status = 'published', $limit = 10, $offset = 0)
    {
        $sql = "SELECT p.*, c.name as category_name, u.username as author_name 
                FROM {$this->table} p
                JOIN categories c ON p.category_id = c.id
                JOIN users u ON p.user_id = u.id
                WHERE p.status = ?
                ORDER BY p.created_at DESC
                LIMIT ? OFFSET ?";

        return $this->query($sql, [$status, $limit, $offset])->fetchAll();
    }

    // Pagination
    public function paginate($status, $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $total = $this->countByStatus($status);

        $data = $this->getPostsWithCategoriesAndAuthors($status, $perPage, $offset);

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    // Pagination
    public function getSearchPost($keyword, $status = 'published', $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;

        $data = $this->searchPosts($keyword, $status, $perPage, $offset);
        $total = $this->countSearchResults($keyword, $status);

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'last_page' => ceil($total / $perPage)
        ];
    }

    public function countByStatus($status)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE status = ?";
        return $this->query($sql, [$status])->fetchColumn();
    }

    public function getByTag($tagId, $limit = 10, $offset = 0)
    {
        $sql = "SELECT p.* FROM {$this->table} p 
                JOIN post_tags pt ON p.id = pt.post_id 
                WHERE pt.tag_id = ? AND p.status = ? 
                ORDER BY p.created_at DESC LIMIT ? OFFSET ?";
        return $this->query($sql, [$tagId, 'published', $limit, $offset])->fetchAll();
    }

    public function countSearchResults($keyword, $status = 'published')
    {

        $safeKeyword = '%' . htmlspecialchars($keyword) . '%';

        $sql = "
            SELECT COUNT(DISTINCT p.id) as total
            FROM {$this->table} p
            LEFT JOIN post_tags pt ON p.id = pt.post_id
            LEFT JOIN tags t ON pt.tag_id = t.id
            WHERE (p.title LIKE ?
               OR p.content LIKE ?
               OR t.name LIKE ?)
               AND p.status = ?
        ";

        return $this->query($sql, [$safeKeyword, $safeKeyword, $safeKeyword, $status])->fetchColumn();
    }

    public function searchPosts($keyword, $status = 'published', $limit = 10, $offset = 0)
    {
        $safeKeyword = '%' . htmlspecialchars($keyword) . '%';

        $sql = "
            SELECT p.*, c.name as category_name, u.username as author_name 
            FROM {$this->table} p
            LEFT JOIN post_tags pt ON p.id = pt.post_id
            LEFT JOIN tags t ON pt.tag_id = t.id
            LEFT JOIN categories c ON p.category_id = c.id
            LEFT JOIN users u ON p.user_id = u.id
            WHERE (p.title LIKE ?
               OR p.content LIKE ?
               OR t.name LIKE ?)
               AND p.status = ?
            ORDER BY p.created_at DESC
            LIMIT ? OFFSET ?
        ";

        return $this->query($sql, [$safeKeyword, $safeKeyword, $safeKeyword, $status, $limit, $offset])->fetchAll();
    }

    public function incrementViewCount($postId)
    {
        $sql = "UPDATE {$this->table} SET view_count = view_count + 1 WHERE id = ?";
        return $this->query($sql, [$postId]);
    }

    public function getByAuthor($userId, $limit = 10, $offset = 0)
    {
        return $this->getWhere('user_id = ? AND status = ?', [$userId, 'published'], 'created_at DESC', $limit, $offset);
    }

    // Récupère tous les articles à la une
    public function getFeaturedPosts()
    {
        $sql = "SELECT * FROM {$this->table} WHERE featured = 1 AND status = 'published' ORDER BY created_at DESC";
        return $this->query($sql)->fetchAll();
    }

    public function getPopulardPosts($limit = 5)
    {
        $sql = "SELECT * FROM {$this->table} WHERE status = 'published' ORDER BY view_count DESC LIMIT ?";
        return $this->query($sql, [$limit])->fetchAll();
    }

    public function getCountPostByCategory($categoryId)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE status = ? AND category_id = ?";
        return $this->query($sql, ['published', $categoryId])->fetchColumn();
    }

    public function getCountPostByUser($userId)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE status = ? AND user_id = ?";
        return $this->query($sql, ['published', $userId])->fetchColumn();
    }
}
