<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Détails de l'utilisateur</h1>
</div>
<table class="table table-bordered">
    <tr>
        <th>ID</th>
        <td><?= htmlspecialchars($user['id']) ?></td>
    </tr>
    <tr>
        <th>Nom</th>
        <td><?= htmlspecialchars($user['username']) ?></td>
    </tr>
    <tr>
        <th>Email</th>
        <td><?= htmlspecialchars($user['email']) ?></td>
    </tr>
    <tr>
        <th>Rôle</th>
        <td><?= htmlspecialchars($user['role']) ?></td>
    </tr>
    <tr>
        <th>Date de Création</th>
        <td><?= htmlspecialchars($user['created_at']) ?></td>
    </tr>
</table>

<a href="/admin/edituser/<?= $user['id'] ?>" class="btn btn-warning">Modifier</a>
<a href="/admin/deleteusers/<?= $user['id'] ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
<a href="/admin/listusers" class="btn btn-secondary">Retour à la liste</a>