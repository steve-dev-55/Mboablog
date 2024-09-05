<div class="col-md-12 mb-4 mt-4 d-flex">
    <?php if (!empty($blogeurs)): ?>
        <?php foreach ($blogeurs as $blogeur): ?>
            <div class="card shadow-sm m-2 flex-fill " style="width:400px">
                <img src="/<?= isset($blogeur['profile_picture']) ? htmlspecialchars($blogeur['profile_picture']) : 'storage\images\blog-author.jpg' ?>" class=" card-img-top flex-shrink-0" alt="">
                <div class="card-body">
                    <h4 class="card-title"><?= htmlspecialchars($blogeur['username']) ?></h4>
                    <p class="card-text"><?= isset($blogeur['bio']) ? htmlspecialchars(substr($blogeur['bio'], 0, 100)) : '' ?></p>
                    <a href="/user/list/<?= htmlspecialchars($blogeur['id']) ?>" class="btn btn-primary"><?= htmlspecialchars($blogeur['post_count']) ?> Articles</a>
                </div>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <li>Aucun Blogeur disponible.</li>
    <?php endif; ?>
</div>