<?php $title = "Connexion"; ?>
<div class="page-title position-relative">
    <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Connexion</h1>
    </div>
</div>
<div class="container mb-4 mt-4 text-center">

    <form action="/user/login" method="post" class="form-signin blog-author-widget-2 widget-item">
        <div class="mb-3">
            <label for="email" class="sr-only">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $oldInput['email'] ?? '' ?>" required>
        </div>
        <div class="mb-3">
            <label for="password" class="sr-only">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <?php if (isset($errors['login'])): ?>
            <div class="alert alert-danger"><?= $errors['login'][0] ?></div>
        <?php endif; ?>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken); ?>">
        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>
</div>