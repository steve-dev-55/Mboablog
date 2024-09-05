<?php $title = "Resultat(s) de la recherche"; ?>
<div class="page-title position-relative">
    <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0"><?= htmlspecialchars($posts['total']) ?> Resultat(s) pour la recherche "<?= htmlspecialchars($keyword) ?>"</h1>
    </div>
</div>
<div class="table-responsive">
    <?php if (!empty($posts['data'])): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Category</th>
                    <th>Date de création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($posts['data'] as $post): ?>
                    <tr>
                        <td><?= $post['id'] ?></td>
                        <td><?= htmlspecialchars($post['title']) ?></td>
                        <td><?= htmlspecialchars($post['author_name']) ?></td>
                        <td><?= htmlspecialchars($post['category_name']) ?></td>
                        <td><?= htmlspecialchars($post['created_at']) ?></td>
                        <td>
                            <a href="/admin/show/<?= $post['id'] ?>" class="btn btn-info">Voir</a>
                            <a href="/admin/edit/<?= $post['id'] ?>" class="btn btn-warning">Edit</a>
                            <form action="/admin/unpublish/<?= $post['id'] ?>" method="POST" style="display:inline-block;">
                                <button type="submit" class="btn btn-danger">Brouillon</button>
                            </form>
                            <form action="/admin/delete/<?= $post['id'] ?>" method="POST" style="display:inline-block;">
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php for ($i = 1; $i <= $posts['last_page']; $i++): ?>
                    <li class="page-item <?= $i == $posts['page'] ? 'active' : '' ?>">
                        <a class="page-link" href="/admin/search?page=<?= $i ?>&keyword=<?= htmlspecialchars($keyword) ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php else: ?>
        <p>Aucun article disponible pour le moment.</p>
    <?php endif; ?>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-8 mb-3 mt-3">
            <div class="row">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/post/create" class="btn btn-primary mb-3">Créer un nouvel article</a>
                <?php endif; ?>
                <?php if (!empty($posts['data'])): ?>
                    <?php foreach ($posts['data'] as $post): ?>
                        <div class="col-md-6 d-flex">
                            <div class="card shadow-sm border-0 mb-4 flex-fill">
                                <?php if (!empty($post['image_path'])): ?>
                                    <img src="/<?= htmlspecialchars($post['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($post['title']) ?>">
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
                                    <p class="card-text"><?= htmlspecialchars(substr($post['content'], 0, 150)) ?>...</p>
                                    <a href="/post/show/<?= htmlspecialchars($post['id']) ?>" class="btn btn-primary">Lire la suite</a>
                                </div>
                                <div class="card-footer text-muted">
                                    Publié le <?= date('d M Y', strtotime($post['created_at'])) ?> |
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <nav aria-label="Page navigation">
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $posts['last_page']; $i++): ?>
                                <li class="page-item <?= $i == $posts['page'] ? 'active' : '' ?>">
                                    <a class="page-link" href="/admin/search?page=<?= $i ?>&keyword=<?= htmlspecialchars($keyword) ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                <?php else: ?>
                    <p>Aucun resultat disponible.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>