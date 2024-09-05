<div class="container mb-3">
    <div class="row">
        <div class="col-md-12">
            <h1>Edit Profile</h1>

            <form action="/user/profile_edit" method="post" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" name="username" id="username" class="form-control" value="<?= htmlspecialchars($oldInput['username'] ?? $user['username']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($oldInput['email'] ?? $user['email']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Profile Picture</label>
                    <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                    <?php if ($user['profile_picture']): ?>
                        <img src="/<?= htmlspecialchars($user['profile_picture']) ?>" class="img-thumbnail mt-2" alt="Profile Picture">
                    <?php endif; ?>
                </div>
                <div class="mb-3">
                    <label for="bio" class="form-label">Bio</label>
                    <textarea name="bio" id="bio" class="form-control" rows="5"><?= isset($user['bio']) ? htmlspecialchars($oldInput['bio'] ?? $user['bio']) : '' ?></textarea>
                </div>
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($user['csrfToken']); ?>">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="/user/profile" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>