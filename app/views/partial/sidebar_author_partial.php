<!-- Blog Author Widget 2 -->
<div class="blog-author-widget-2 widget-item">

    <div class="d-flex flex-column align-items-center">
        <img src="/<?= htmlspecialchars($post['profile_picture']) ?>" class="rounded-circle flex-shrink-0" alt="">
        <h4><?= htmlspecialchars($post['author_name']) ?></h4>
        <div class="social-links">
            <a href="https://x.com/#"><i class="bi bi-twitter-x"></i></a>
            <a href="https://facebook.com/#"><i class="bi bi-facebook"></i></a>
            <a href="https://instagram.com/#"><i class="biu bi-instagram"></i></a>
            <a href="https://instagram.com/#"><i class="biu bi-linkedin"></i></a>
        </div>

        <p>
            <?= htmlspecialchars($post['bio']) ?>
        </p>

    </div>
</div><!--/Blog Author Widget 2 -->