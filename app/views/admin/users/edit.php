<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Modifier l'utilisateur</h1>
</div>
<form action="/admin/edituser/<?= $user['id'] ?>" method="post">
    <div class="form-group">
        <label for="username">Nom</label>
        <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($oldInput['username'] ?? $user['username']) ?>" required>
        <?php if (isset($errors['username'][0])): ?>
            <div class="text-danger"><?= htmlspecialchars($errors['username'][0]) ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($oldInput['email'] ?? $user['email']) ?>" required>
        <?php if (isset($errors['email'][0])): ?>
            <div class="text-danger"><?= htmlspecialchars($errors['email'][0]) ?></div>
        <?php endif; ?>
    </div>

    <div class="form-group">
        <label for="role">Rôle</label>
        <select name="role" id="role" class="form-control" required>
            <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>Utilisateur</option>
            <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Administrateur</option>
        </select>
        <?php if (isset($errors['role'][0])): ?>
            <div class="text-danger"><?= htmlspecialchars($errors['role'][0]) ?></div>
        <?php endif; ?>
    </div>

    <button type="submit" class="btn btn-primary">Mettre à jour</button>
    <a href="/admin/listusers" class="btn btn-secondary">Annuler</a>
</form>