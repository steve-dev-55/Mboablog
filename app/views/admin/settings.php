<?php $title = "Gestion des paramètres"; ?>
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Gestion des paramètres</h1>
</div>
<div class="contenair mb-4">
    <form action="/admin/updateSettings" method="POST" enctype="multipart/form-data">
        <div class="form-group mb-2">
            <label for="site_title">Titre du site</label>
            <input type="text" class="form-control" id="site_title" name="site_title" value="<?= isset($settings['site_title']) ? htmlspecialchars($settings['site_title']) : '' ?>" required>
        </div>

        <div class="form-group mb-2">
            <label for="logo" class="form-label">logo :</label>
            <input type="file" class="form-control" id="logo" name="logo">
            <?php if (!empty($settings['logo'])): ?>
                <img src="/<?= htmlspecialchars($settings['logo']) ?>" alt="Logo actuel" class="form-control">
            <?php endif; ?>
        </div>

        <div class="form-group mb-2">
            <label for="seo_description">Description SEO</label>
            <textarea class="form-control" id="seo_description" name="seo_description" rows="3"><?= isset($settings['seo_description']) ? htmlspecialchars($settings['seo_description']) : '' ?></textarea>
        </div>

        <div class="form-group mb-2">
            <label for="articles_per_page">Nombre d'articles par page</label>
            <input type="number" class="form-control" id="articles_per_page" name="articles_per_page" value="<?= isset($settings['articles_per_page']) ? htmlspecialchars($settings['articles_per_page']) : '' ?>" required>
        </div>

        <div class="form-group mb-2">
            <label for="social_facebook">Lien Facebook</label>
            <input type="url" class="form-control" id="social_facebook" name="social_facebook" value="<?= isset($settings['social_facebook']) ? htmlspecialchars($settings['social_facebook']) : '' ?>">
        </div>

        <div class="form-group mb-2">
            <label for="social_twitter">Lien Twitter</label>
            <input type="url" class="form-control" id="social_twitter" name="social_twitter" value="<?= isset($settings['social_twitter']) ? htmlspecialchars($settings['social_twitter']) : '' ?>">
        </div>

        <div class="form-group mb-2">
            <label for="social_instagram">Lien Instagram</label>
            <input type="url" class="form-control" id="social_instagram" name="social_instagram" value="<?= isset($settings['social_instagram']) ? htmlspecialchars($settings['social_instagram']) : '' ?>">
        </div>

        <div class="form-group mb-2">
            <label for="social_linkedin">Lien Linkedin</label>
            <input type="url" class="form-control" id="social_linkedin" name="social_linkedin" value="<?= isset($settings['social_linkedin']) ? htmlspecialchars($settings['social_linkedin']) : '' ?>">
        </div>
        <!-- Ajoutez d'autres champs pour les paramètres supplémentaires -->

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>