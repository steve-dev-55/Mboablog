<div class="p-4 mb-3 widget-item rounded">
    <h3 class="widget-title">Categories</h3>
    <ul class="list-unstyled">
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
                <li>
                    <a href="/category/list/<?= htmlspecialchars($category['id']) ?>">
                        <?= htmlspecialchars($category['name']) ?> (<?= htmlspecialchars($category['post_count']) ?>)
                    </a>
                </li>
            <?php endforeach; ?>
        <?php else: ?>
            <li>Aucune Catégorie disponible.</li>
        <?php endif; ?>
    </ul>
</div>