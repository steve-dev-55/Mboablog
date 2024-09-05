<?php $title = "Inscription"; ?>
<div class="page-title position-relative">
    <div class="container d-lg-flex justify-content-between align-items-center">
        <h1 class="mb-2 mb-lg-0">Inscription</h1>
    </div>
</div>
<div class="container mb-4 mt-4 text-center">

    <form action="/user/register" method="post" class="form-signin blog-author-widget-2 widget-item">
        <div class="mb-3">
            <label for="username" class="sr-only">Nom d'utilisateur</label>
            <input type="text" class="form-control" id="username" name="username" value="<?= $oldInput['username'] ?? '' ?>" required>
            <?php if (isset($errors['username'])): ?>
                <div class="text-danger"><?= $errors['username'][0] ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="email" class="sr-only">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= $oldInput['email'] ?? '' ?>" required>
            <?php if (isset($errors['email'])): ?>
                <div class="text-danger"><?= $errors['email'][0] ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="password" class="sr-only">Mot de passe</label>
            <input type="password" class="form-control" id="password" name="password" required>
            <?php if (isset($errors['password'])): ?>
                <div class="text-danger"><?= $errors['password'][0] ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="confirm_password" class="sr-only">Confirmer le mot de passe</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
            <?php if (isset($errors['confirm_password'])): ?>
                <div class="text-danger"><?= $errors['confirm_password'][0] ?></div>
            <?php endif; ?>
        </div>
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken); ?>">
        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
</div>