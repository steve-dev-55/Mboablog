<?php $title = htmlspecialchars($posts['data'][0]['author_name']); ?>
<div class="page-title position-relative">
    <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Blogeur <?= htmlspecialchars($posts['data'][0]['author_name']) ?> </h1>
        <nav class="breadcrumbs">
            <ol>
                <li><a href="/">Accueil</a></li>
                <li><a href="/user">Blogeur</a></li>
                <li class="current"><?= htmlspecialchars($posts['data'][0]['author_name']) ?></li>
            </ol>
        </nav>
    </div>
</div>
<div class="container mb-2">
    <div class="row">
        <div class="col-md-8 mb-3 mt-3">
            <div class="row">
                <a href="/post/create" class="btn btn-primary mb-3">Créer un nouvel article</a>
                <?php if (!empty($posts['data'])): ?>
                    <?php foreach ($posts['data'] as $post): ?>
                        <?php include __DIR__ . '/../partial/articles_row_partial.php'; ?>
                    <?php endforeach; ?>
                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $posts['last_page']; $i++): ?>
                                <li class="page-item <?= $i == $posts['page'] ? 'active' : '' ?>">
                                    <a class="page-link" href="/user/list/<?= $posts['user_id'] ?>?page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php else: ?>
                    <p>Aucun resultat disponible.</p>
                <?php endif; ?>
            </div>
        </div>
        <!-- sidebar Section -->
        <div class="col-md-4 mt-3">
            <?php include __DIR__ . '/../partial/sidebar_author_partial.php'; ?>
            <?php include __DIR__ . '/../partial/sidebar_search_partial.php'; ?>
            <?php include __DIR__ . '/../partial/sidebar_category_partial.php'; ?>
            <?php include __DIR__ . '/../partial/sidebar_recent_post_partial.php'; ?>
        </div>
    </div>
</div>