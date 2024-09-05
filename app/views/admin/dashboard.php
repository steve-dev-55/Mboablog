<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Tableau de Bord</h1>
</div>

<div class="grid text-center">
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card bg-light">
                <a class="nav-link" href="/admin/posts">
                    <div class="card-body">
                        <h5 class="card-title">Articles</h5>
                        <p class="card-text"><?= htmlspecialchars($postCount); ?> articles</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card bg-light">
                <a class="nav-link" href="/admin/listusers">
                    <div class="card-body">
                        <h5 class="card-title">Utilisateurs</h5>
                        <p class="card-text"><?= htmlspecialchars($userCount); ?> utilisateurs</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card bg-light">
                <a class="nav-link" href="/admin/listcategory">
                    <div class="card-body">
                        <h5 class="card-title">Catégories</h5>
                        <p class="card-text"><?= htmlspecialchars($categoryCount); ?> catégories</p>
                    </div>
                </a>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card bg-light">
                <a class="nav-link" href="/admin/listTag">
                    <div class="card-body">
                        <h5 class="card-title">Tags</h5>
                        <p class="card-text"><?= htmlspecialchars($tagCount); ?> tags</p>
                    </div>
            </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card bg-light">
                <a class="nav-link" href="/admin/listComments">
                    <div class="card-body">
                        <h5 class="card-title">Commentaires</h5>
                        <p class="card-text"><?= htmlspecialchars($commentCount); ?> commentaires</p>
                    </div>
            </div>
            </a>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card bg-light">
                <a class="nav-link active" href="/admin/dashboard">
                    <div class="card-body">
                        <h5 class="card-title">Paramètres</h5>
                        <p class="card-text">Vos Paramètres</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>