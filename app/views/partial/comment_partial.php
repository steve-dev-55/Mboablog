<?php
$maxDepth = 5; // Limite d'imbrication, par exemple à 5 niveaux

if (!isset($depth)) {
    $depth = 0;
}

if ($depth >= $maxDepth) {
    return;
}
?>

<li class="list-group-item" style="margin-left: <?= $depth * 20 ?>px;">
    <strong><?= htmlspecialchars($comment['username']) ?>:</strong>
    <p><?= htmlspecialchars($comment['content']) ?></p>
    <small class="text-muted"><?= date('d/m/Y', strtotime($comment['created_at'])) ?></small>

    <?php if ($this->isAuthenticated()): ?>
        <a href="#" class="reply-link" data-comment-id="<?= $comment['id'] ?>">Répondre</a>
        <form action="/comment/create/<?= $post['id'] ?>" method="post" class="reply-form d-none" id="reply-form-<?= $comment['id'] ?>">
            <div class="form-group">
                <textarea name="content" rows="3" class="form-control" required></textarea>
                <input type="hidden" name="parent_id" value="<?= $comment['id'] ?>">
            </div>
            <button type="submit" class="btn btn-primary mt-2">Envoyer</button>
        </form>
    <?php endif; ?>

    <?php if (!empty($comment['children'])): ?>
        <ul class="list-group mt-3">
            <?php
            $newDepth = $depth + 1;
            foreach ($comment['children'] as $childComment):
                $comment = $childComment;
                $depth = $newDepth;
                include __DIR__ . '/comment_partial.php';
            endforeach;
            ?>
        </ul>
    <?php endif; ?>
</li>