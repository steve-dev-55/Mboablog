<?php $title = "Liste des Catégories"; ?>
<div class="page-title position-relative">
    <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Catégories</h1>
        <nav class="breadcrumbs">
            <ol>
                <li><a href="/">Accueil</a></li>
                <li class="current">Categories</li>
            </ol>
        </nav>
    </div>
</div>
<div class="container">
    <div class="row">
        <?php foreach ($categories as $category): ?>
            <div class="col-md-4 mb-3 mt-3">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($category['name']) ?></h5>
                        <p class="card-text"><?= $category['post_count'] ?> article(s)</p>
                        <a href="/category/list/<?= $category['id'] ?>" class="btn btn-primary">Voir les articles</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>