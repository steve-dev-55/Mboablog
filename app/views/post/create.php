<body>
    <div class="container mb-4 mt-5">
        <h1 class="mb-4">Ajouter un nouvel article</h1>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $error): ?>
                    <p><?= $error ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form action="/post/create" method="post" enctype="multipart/form-data">
            <div class="form-group mb-3">
                <label for="title" class="form-label">Titre :</label>
                <input type="text" id="title" name="title" class="form-control" value="<?= htmlspecialchars($oldInput['title'] ?? '') ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="content" class="form-label">Contenu :</label>
                <textarea id="content" name="content" class="form-control" rows="5" required><?= htmlspecialchars($oldInput['content'] ?? '') ?></textarea>
            </div>

            <div class="form-group mb-3">
                <label for="category_id" class="form-label">Catégorie :</label>
                <select id="category_id" name="category_id" class="form-select" required>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['id'] ?>" <?= isset($oldInput['category_id']) && $oldInput['category_id'] == $category['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group mb-3">
                <label for="tags" class="form-label">Tags :</label>
                <input type="text" id="tags" name="tags" class="form-control" placeholder="Entrez les tags séparés par des virgules" value="<?= htmlspecialchars($oldInput['tags'] ?? '') ?>">
            </div>

            <div class="form-group mb-3">
                <label for="image" class="form-label">Image :</label>
                <input type="file" id="image" name="image_path" class="form-control" accept="image/*">
            </div>

            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken); ?>">

            <button type="submit" class="btn btn-primary">Publier l'article</button>
        </form>
    </div>
</body>