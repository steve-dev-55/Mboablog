<div class="recent-posts-widget widget-item">
    <h4 class="widget-title">Articles récents</h4>
    <?php if (!empty($recentPosts)): ?>
        <?php foreach (array_slice($recentPosts, 0, 10) as $post): ?>

            <div class="post-item">
                <?php if (!empty($post['image_path'])): ?>
                    <img src="/<?= htmlspecialchars($post['image_path']) ?> " class="flex-shrink-0" alt="<?= htmlspecialchars($post['title']) ?>">
                <?php endif; ?>
                <div>
                    <h4><a href="/post/show/<?= htmlspecialchars($post['id']) ?>">
                            <?= htmlspecialchars($post['title']) ?>
                        </a></h4>
                    <time datetime="<?= htmlspecialchars($post['created_at']) ?>"><?= htmlspecialchars($post['created_at']) ?></time>
                </div>
            </div><!-- End recent post item-->
        <?php endforeach; ?>
    <?php else: ?>
        <p>No recommended articles available.</p>
    <?php endif; ?>
</div>