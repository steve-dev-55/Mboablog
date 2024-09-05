<body>
    <div class="container">
        <h1><?= htmlspecialchars($post['title']) ?></h1>
        <p class="text-muted">By <?= htmlspecialchars($post['author_name']) ?> in <?= htmlspecialchars($post['category_name']) ?></p>

        <?php if ($post['image_path']): ?>
            <img src="/<?= $post['image_path'] ?>" alt="Image de l'article" class="post-image">
        <?php endif; ?>

        <div class="post-content">
            <?= nl2br(htmlspecialchars($post['content'])) ?>
        </div>

        <div class="post-meta">
            <p>Vues : <?= $post['view_count'] ?></p>
            <p>status : <?= $post['status'] ?></p>
            <p>Date de publication : <?= date('d/m/Y', strtotime($post['created_at'])) ?></p>
        </div>

        <!-- Section des commentaires existants -->
        <div class="row mt-5">
            <div class="col-12">
                <h2>Commentaires</h2>
                <?php if (!empty($comments)): ?>
                    <ul class="list-group">
                        <?php
                        $depth = 0; // Initialiser la profondeur à 0
                        foreach ($comments as $comment):
                            include __DIR__ . '/../../partial/comment_partial.php';
                        endforeach;
                        ?>
                    </ul>
                <?php else: ?>
                    <p>Aucun commentaire pour le moment.</p>
                <?php endif; ?>
            </div>
        </div>


        <a href="/admin/edit/<?= $post['id'] ?>" class="btn btn-warning">Modifier l'article</a>
        <a href="/admin/delete/<?= $post['id'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">Supprimer l'article</a>
        <?php if ($post['featured'] === 0): ?>
            <a href="/admin/feature/<?= $post['id'] ?>" class="btn btn-warning">Mettre a la une</a>
        <?php endif; ?>
    </div>
</body>