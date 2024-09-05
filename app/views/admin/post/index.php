<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Gérer les articles</h1>
</div>

<div class="row">
    <div class="col-12">
        <a href="/admin/create" class="btn btn-success mb-3">Create New Post</a>
    </div>
    <div class="col-4">
        <a href="/admin/listdraftpost" class="btn btn-success mb-3"><?php echo $n_draft ?> Articles en attente(s)</a>
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
                        <a class="page-link" href="/admin?page=<?= $i ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php else: ?>
        <p>Aucun article disponible pour le moment.</p>
    <?php endif; ?>
</div>