<div class="col-md-6 d-flex">
    <div class="card shadow-sm border-0 mb-4 flex-fill">
        <?php if (!empty($post['image_path'])): ?>
            <img src="/<?= htmlspecialchars($post['image_path']) ?>" class="card-img-top" alt="<?= htmlspecialchars($post['title']) ?>">
        <?php endif; ?>
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($post['title']) ?></h5>
            <h6 class="card-subtitle mb-2 mt-2 text-muted"> <i class="bi bi-folder2"></i> <span class=" ps-2"><a href="/category/list/<?= htmlspecialchars($post['category_id']) ?>"><?= htmlspecialchars($post['category_name']) ?></a></span>
            </h6>
            <p class="card-text"><?= htmlspecialchars(substr($post['content'], 0, 150)) ?>...</p>
            <a href="/post/show/<?= htmlspecialchars($post['id']) ?>" class="btn btn-primary">Lire la suite</a>
        </div>
        <div class="card-footer text-muted">
            Publié le <?= date('d M Y', strtotime($post['created_at'])) ?> |
            Par <?= htmlspecialchars($post['author_name']) ?>
        </div>
    </div>
</div>