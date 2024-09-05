<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Manage Categories</h1>
</div>
<div class="row">
    <div class="col-12">
        <a href="/admin/createCategory" class="btn btn-success mb-3">Create New Category</a>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= $category['id'] ?></td>
                    <td><?= htmlspecialchars($category['name']) ?></td>
                    <td>
                        <a href="/admin/editCategory/<?= $category['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                        <form action="/admin/deleteCategory/<?= $category['id'] ?>" method="POST" style="display:inline-block;">
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>