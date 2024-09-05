<div class="container mb-3">

    <h1>Profil de <?= htmlspecialchars($user['username']) ?></h1>

    <div class="row">
        <div class="col-md-4">
            <img src="<?= $user['profile_picture'] ? '/' . htmlspecialchars($user['profile_picture']) : '/images/default-profile.png' ?>" class="img-thumbnail" alt="Profile Picture">
        </div>
        <div class="col-md-8">
            <h2><?= htmlspecialchars($user['username']) ?></h2>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Bio:</strong> <?= isset($user['bio']) ? nl2br(htmlspecialchars($user['bio'])) : '' ?></p>
            <a href="/user/profile_edit" class="btn btn-primary">Edit Profile</a>
            <a href="/user/deleteuser" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer le Compte ?');">Supprimer le compte</a>
        </div>
    </div>
</div>