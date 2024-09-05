<!--/Tags Widget -->
<div class="tags-widget widget-item">
    <h3 class="widget-title">Tags</h3>
    <?php if (!empty($populartag)): ?>
        <ul>
            <?php foreach (array_slice($populartag, 0, 10) as $tag): ?>
                <li><a href="/post/search?keyword=<?= htmlspecialchars($tag['name']) ?>"><?= htmlspecialchars($tag['name']) ?></a></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Pas de tag disponible.</p>
    <?php endif; ?>
</div>