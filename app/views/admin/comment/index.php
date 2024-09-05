<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Gérer les commentaires</h1>
</div>

<?php if (!empty($comments)): ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Post Title</th>
                <th>Comment</th>
                <th>Author</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>

            <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?= $comment['id'] ?></td>
                    <td><?= $comment['post_title'] ?></td>
                    <td><?= $comment['content'] ?></td>
                    <td><?= $comment['author_name'] ?></td>
                    <td><?= ucfirst($comment['status']) ?></td>
                    <td>
                        <?php if ($comment['status'] === 'pending'): ?>
                            <a href="/admin/approvecomment/<?= $comment['id'] ?>" class="btn btn-success btn-sm">Approve</a>
                        <?php endif; ?>
                        <a href="/admin/deletecomment/<?= $comment['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this comment?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun commentaire disponible pour le moment.</p>
<?php endif; ?>