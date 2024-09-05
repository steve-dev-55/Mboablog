<?php $title = htmlspecialchars($post['title']); ?>
<div class="container  mb-4">
    <div class="row">
        <div class="col-lg-8">
            <!-- Blog Details Section -->
            <section id="blog-details" class="blog-details section">
                <div class="container">

                    <article class="article">

                        <div class="post-img">
                            <img src="/<?= $post['image_path'] ?>" alt="" class="img-fluid">
                        </div>

                        <h2 class="title"><?= htmlspecialchars($post['title']) ?></h2>

                        <div class="meta-top">
                            <ul>
                                <li class="d-flex align-items-center"><i class="bi bi-person"></i> <a href="blog-details.html"><?= htmlspecialchars($post['author_name']) ?></a></li>
                                <li class="d-flex align-items-center"><i class="bi bi-clock"></i> <a href="#"><time datetime="<?= date('d/m/Y', strtotime($post['created_at'])) ?>"><?= date('d/m/Y', strtotime($post['created_at'])) ?></time></a></li>
                                <li class="d-flex align-items-center"><i class="bi bi-eye"></i> <a href="#"><?= $post['view_count'] ?></a></li>
                            </ul>
                        </div><!-- End meta top -->

                        <div class="content">

                            <?= nl2br(htmlspecialchars($post['content'])) ?>

                        </div><!-- End post content -->

                        <div class="meta-bottom">
                            <i class="bi bi-folder"></i>
                            <ul class="cats">
                                <li><a href="/category/list/<?= htmlspecialchars($post['category_id']) ?>"><?= htmlspecialchars($post['category_name']) ?></a></li>
                            </ul>

                            <i class="bi bi-tags"></i>
                            <ul class="tags">
                                <?php foreach (array_slice($post['tag'], 0, 10) as $postag): ?>
                                    <li><a href="/post/search?keyword=<?= htmlspecialchars($postag['name']) ?>"><?= htmlspecialchars($postag['name']) ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div><!-- End meta bottom -->

                    </article>

                </div>
            </section><!-- /Blog Details Section -->
            <!-- Section des commentaires existants -->
            <div class="row mt-5">
                <div class="col-12">
                    <h2>Commentaires</h2>
                    <?php if (!empty($comments)): ?>
                        <ul class="list-group">
                            <?php
                            $depth = 0; // Initialiser la profondeur à 0
                            foreach ($comments as $comment):
                                include __DIR__ . '/../partial/comment_partial.php';
                            endforeach;
                            ?>
                        </ul>
                    <?php else: ?>
                        <p>Aucun commentaire pour le moment.</p>
                    <?php endif; ?>
                </div>
            </div>
            <!-- Section pour ajouter un commentaire -->
            <div class="row mt-4">
                <div class="col-12">
                    <?php if ($this->isAuthenticated()): ?>
                        <h3>Laisser un commentaire</h3>
                        <form action="/comment/create/<?= $post['id'] ?>" method="post">
                            <div class="form-group">
                                <label for="commentContent">Votre commentaire :</label>
                                <textarea name="content" id="commentContent" rows="5" class="form-control" required></textarea>
                                <input type="hidden" name="parent_id" value="<?= $parent_id ?? null ?>">
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Envoyer</button>
                        </form>
                    <?php else: ?>
                        <p>
                            <a href="/login" class="btn btn-primary">Connectez-vous pour laisser un commentaire</a>
                        </p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <!-- sidebar Section -->
        <div class="col-lg-4 sidebar">
            <div class="widgets-container">
                <?php include __DIR__ . '/../partial/sidebar_author_partial.php'; ?>
                <?php include __DIR__ . '/../partial/sidebar_search_partial.php'; ?>
                <?php include __DIR__ . '/../partial/sidebar_recent_post_partial.php'; ?>
                <?php include __DIR__ . '/../partial/sidebar_tag_partial.php'; ?>
            </div>
        </div>

    </div>

    <!-- Section d'édition pour l'auteur -->
    <?php if ($this->isAuthenticated() && $this->isAuthor($post['user_id'])): ?>
        <a href="/post/edit/<?= $post['id'] ?>" class="btn btn-warning mt-2">Modifier l'article</a>
        <a href="/post/delete/<?= $post['id'] ?>" class="btn btn-danger mt-2" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">Supprimer l'article</a>
    <?php endif; ?>
</div>