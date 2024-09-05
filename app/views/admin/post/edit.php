<body>
    <div class="container mb-5">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1>Modifier l'article</h1>
        </div>

        <?php if (!empty($errors)): ?>
            <div class="errors">
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>


        <form action="/admin/edit/<?= $post['id'] ?>" method="post" enctype="multipart/form-data">
            <div class="form-group mb-3">
                <label for="title">Titre :</label>
                <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($oldInput['title'] ?? $post['title']) ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="content">Contenu :</label>
                <textarea id="content" name="content" class="form-control" rows="7" required><?= htmlspecialchars($oldInput['content'] ?? $post['content']) ?></textarea>
            </div>
            <div class="form-group mb-3">
                <label for="category_id" class="form-label">Category</label>
                <select class="form-select" id="category_id" name="category_id" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= isset($oldInput['category_id']) && $oldInput['category_id'] == $category['id'] ? 'selected' : ($post['category_id'] == $category['id'] ? 'selected' : '') ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group mb-3">
                <label for="status">status</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="draft" <?= $post['status'] === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                    <option value="published" <?= $post['status'] === 'published' ? 'selected' : '' ?>>Publier</option>
                </select>
                <?php if (isset($errors['status'][0])): ?>
                    <div class="text-danger"><?= htmlspecialchars($errors['status'][0]) ?></div>
                <?php endif; ?>
            </div>
            <div class="form-group mb-3">
                <label for="tags">Tags</label>
                <input type="text" class="form-control" id="tags" name="tags" placeholder="Entrez les tags séparés par des virgules" value="<?= isset($post['tags']) ? htmlspecialchars($post['tags']) : '' ?>">
            </div>
            <div class="form-group mb-3">
                <input class="form-check-input" type="checkbox" id="featured<?= $post['id'] ?>" name="featured" <?= $post['featured'] ? 'checked' : '' ?>>
                <label class="form-check-label" for="featured<?= $post['id'] ?>">
                    Mettre à la une
                </label>
            </div>
            <?php if ($post['image_path']): ?>
                <div>
                    <p>Image actuelle :</p>
                    <img src="/<?= $post['image_path'] ?>" alt="Image actuelle" style="max-width: 200px;">
                </div>
            <?php endif; ?>

            <div class="form-group mb-3 mt-2">
                <label for="image" class="form-label">Image :</label>
                <label for="image">Nouvelle image (laissez vide pour conserver l'image actuelle) :</label>
                <input type="file" id="image" class="form-control" name="image_path" accept="image/*">
            </div>

            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($post['csrfToken']); ?>">
            <button type="submit" class="btn btn-primary">Mettre à jour l'article</button>
        </form>
    </div>
</body>