<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1>Edit Tag</h1>
</div>
<form action="/admin/edittags/<?= $tag['id'] ?>" method="post">
    <div class="mb-3">
        <label for="name" class="form-label">Tag Name</label>
        <input type="text" name="name" id="name" class="form-control" value="<?= $tag['name'] ?>" required>
        <?php if (!empty($errors['name'])): ?>
            <div class="text-danger"><?= $errors['name'] ?></div>
        <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Update</button>
    <a href="/admin/listag" class="btn btn-secondary">Cancel</a>
</form>