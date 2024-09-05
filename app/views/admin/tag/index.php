<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Manage Tags</h1>
</div>
<a href="/admin/createtag" class="btn btn-primary mb-3">Create New Tag</a>

<table class="table table-striped">
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($tags as $tag): ?>
            <tr>
                <td><?= $tag['id'] ?></td>
                <td><?= $tag['name'] ?></td>
                <td>
                    <a href="/admin/edittags/<?= $tag['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="/admin/deletetags/<?= $tag['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this tag?');">Delete</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>