<?php

namespace App\Models;

class CommentModel extends BaseModel
{
    protected $table = 'comments';
    private const MAX_DEPTH = 5;

    public function getCommentsForPost($postId, $limit = 10, $offset = 0)
    {
        return $this->getWhere('post_id = ? AND status = ?', [$postId, 'approved'], 'created_at DESC', $limit, $offset);
    }

    public function getCommentsByPostId($postId)
    {
        $sql = "SELECT c.*, u.username 
                FROM {$this->table} c
                JOIN users u ON c.user_id = u.id
                WHERE c.post_id = ? AND c.status = ? 
                ORDER BY c.parent_id ASC, c.created_at ASC";
        $comments = $this->query($sql, [$postId, 'approved'])->fetchAll();
        return $this->buildCommentTree($comments);
    }

    private function buildCommentTree(array $comments, $parentId = null, $depth = 0)
    {
        if ($depth >= self::MAX_DEPTH) {
            return [];
        }

        $branch = [];
        foreach ($comments as $comment) {
            if ($comment['parent_id'] == $parentId) {
                $children = $this->buildCommentTree($comments, $comment['id'], $depth + 1);
                if ($children) {
                    $comment['children'] = $children;
                }
                $comment['depth'] = $depth;
                $branch[] = $comment;
            }
        }
        return $branch;
    }

    public function getUnapprovedComments()
    {
        return $this->getWhere('status = ?', ['pending'], 'created_at ASC');
    }

    public function approveComment($commentId)
    {
        return $this->update($commentId, ['status' => 'approved']);
    }

    public function getCommentCountForPost($postId)
    {
        $sql = "SELECT COUNT(*) FROM {$this->table} WHERE post_id = ? AND status = ?";
        return $this->query($sql, [$postId, 'approved'])->fetchColumn();
    }

    // Méthode pour récupérer les commentaires approuvés avec l'auteur et l'article
    public function getApprovedCommentsWithAuthorAndPost($postId)
    {
        $sql = "
                SELECT c.*, u.username as author_name, p.title as post_title
                FROM {$this->table} c
                JOIN users u ON c.user_id = u.id
                JOIN posts p ON c.post_id = p.id
                WHERE c.status = 'approved' AND p.post_id = {$postId}
                ORDER BY c.created_at DESC
            ";
        return $this->query($sql)->fetchAll();
    }

    // Méthode pour récupérer tous les commentaires (approuvés et non approuvés) avec l'auteur et l'article
    public function getAllCommentsWithAuthorAndPost()
    {
        $sql = "
                SELECT c.*, u.username as author_name, p.title as post_title
                FROM {$this->table} c
                JOIN users u ON c.user_id = u.id
                JOIN posts p ON c.post_id = p.id
                ORDER BY c.created_at DESC
            ";
        return $this->query($sql)->fetchAll();
    }
}
