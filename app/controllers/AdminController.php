<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\UserModel;
use App\Models\CategoryModel;
use App\Models\TagModel;
use App\Models\SettingModel;
use App\Models\CommentModel;
use App\Traits\PostOperationsTrait;

class AdminController extends BaseController
{
    protected $postModel;
    protected $userModel;
    protected $tagModel;
    protected $commentModel;
    protected $SettingModel;
    protected $categoryModel;

    use PostOperationsTrait;


    public function __construct()
    {
        parent::__construct();

        // Initialisation des modèles nécessaires pour le tableau de bord
        $this->postModel = new PostModel();
        $this->userModel = new UserModel();
        $this->categoryModel = new CategoryModel();
        $this->tagModel = new TagModel();
        $this->SettingModel = new SettingModel();
        $this->commentModel = new CommentModel();

        $this->initModels(); // Initialiser les modèles nécessaires

        // Vérification de l'authentification et des permissions
        if (!$this->isAuthenticated() || !$this->isAdmin()) {
            $this->redirect('/login');
            exit();
        }
    }

    public function dashboard()
    {

        // Récupération des statistiques
        $postCount = $this->postModel->countByStatus('published');
        $userCount = $this->userModel->Count();
        $categoryCount = $this->categoryModel->Count();
        $tagCount = $this->tagModel->Count();
        $commentCount = $this->commentModel->Count();

        // Chargement de la vue du tableau de bord avec les données
        $this->view('admin/dashboard', [
            'postCount' => $postCount,
            'userCount' => $userCount,
            'categoryCount' => $categoryCount,
            'tagCount' => $tagCount,
            'commentCount' => $commentCount
        ]);
    }

    // Méthode pour lister les utilisateurs
    public function listUsers()
    {
        try {
            $users = $this->userModel->getAll('id DESC');

            $this->view('admin/users/list', ['users' => $users]);
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    // Méthode pour afficher les détails d'un utilisateur
    public function showUser($id)
    {
        try {
            $user = $this->userModel->getById($id);
            if (!$user) {
                $this->error404();
            }
            $this->view('admin/users/show', ['user' => $user]);
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    // Méthode pour ajouter un nouvel utilisateur
    public function addUser()
    {
        if ($this->isPost()) {
            $data = $this->getPostData();
            $rules = [
                'username' => ['required' => true, 'min' => 3, 'max' => 50],
                'email' => ['required' => true, 'email' => true],
                'password' => ['required' => true, 'min' => 6],
                'confirm_password' => ['required' => true],
                'role' => ['required' => true]
            ];

            if ($this->validator->validate($data, $rules)) {
                if ($data['password'] !== $data['confirm_password']) {
                    $this->validator->addError('confirm_password', 'Les mots de passe ne correspondent pas.');
                } else {
                    try {
                        unset($data['confirm_password']);

                        // Vérification si l'email existe déjà
                        if ($this->userModel->Exists($data['email'], 'email')) {
                            $this->validator->addError('email', 'Cet email est déjà utilisé.');
                        }

                        // Vérification si le nom d'utilisateur existe déjà
                        if ($this->userModel->Exists($data['username'], 'username')) {
                            $this->validator->addError('username', 'Ce nom d\'utilisateur est déjà pris.');
                        }

                        if (!$this->validator->getErrors()) {

                            $this->userModel->create($data);
                            $this->redirect('/admin/listusers');
                        }
                    } catch (\Exception $e) {
                        $this->error500($e->getMessage());
                    }
                }
            }

            $this->view('admin/users/add', [
                'errors' => $this->validator->getErrors(),
                'oldInput' => $data
            ]);
        } else {
            $this->view('admin/users/add');
        }
    }

    // Méthode pour modifier un utilisateur existant
    public function editUser($id)
    {
        $user = $this->userModel->getById($id);
        if (!$user) {
            $this->error404();
        }

        if ($this->isPost()) {
            $data = $this->getPostData();
            $rules = [
                'username' => ['required' => true, 'min' => 3, 'max' => 50],
                'email' => ['required' => true, 'email' => true],
                'role' => ['required' => true]
            ];

            if (!empty($data['password'])) {
                $rules['password'] = ['min' => 6];
                if ($data['password'] !== $data['confirm_password']) {
                    $this->validator->addError('confirm_password', 'Les mots de passe ne correspondent pas.');
                }
            }

            if ($this->validator->validate($data, $rules)) {
                if (!empty($data['password'])) {
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                } else {
                    unset($data['password']);
                }

                try {
                    $this->userModel->update($id, $data);
                    $this->redirect('/admin/listusers');
                } catch (\Exception $e) {
                    $this->error500($e->getMessage());
                }
            }

            $this->view('admin/users/edit', [
                'user' => $user,
                'errors' => $this->validator->getErrors(),
                'oldInput' => $data
            ]);
        } else {
            $this->view('admin/users/edit', ['user' => $user]);
        }
    }

    // Méthode pour supprimer un utilisateur
    public function deleteUser($id)
    {
        try {
            if ($this->userModel->exists($id, 'id')) {
                $this->userModel->delete($id);
                $this->redirect('/admin/users');
            } else {
                $this->error404();
            }
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    protected function renderIndexView($posts)
    {
        $draft = $this->postModel->countByStatus('draft');
        $this->view('admin/post/index', ['posts' => $posts, 'n_draft' => $draft]);
    }

    protected function renderShowView($post, $comments)
    {
        $this->view('admin/post/show', ['post' => $post, 'comments' => $comments]);
    }

    protected function renderCreateView($csrfToken, $data = [], $errors = [], $categories = [])
    {
        $this->view('admin/post/create', ['errors' => $errors, 'oldInput' => $data, 'categories' => $categories, 'csrfToken' => $csrfToken]);
    }

    protected function handleCreateRedirect($postId)
    {
        $this->redirect("/admin/posts");
    }

    protected function renderEditView($post, $data = [], $errors = [], $categories = [])
    {
        $this->view('admin/post/edit', ['post' => $post, 'errors' => $errors, 'oldInput' => $data, 'categories' => $categories]);
    }

    protected function handleEditRedirect($id)
    {
        $this->redirect("/admin/post/show/{$id}");
    }

    protected function handleDeleteRedirect()
    {
        $this->redirect('/admin/post');
    }

    protected function handleSearchRedirect()
    {
        $this->redirect('/admin/search_results');
    }

    protected function renderSearchView($post, $keyword)
    {
        $this->view('/admin/search_results', ['posts' => $post, 'keyword' => $keyword]);
    }

    public function publish($id)
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }

        try {
            $this->postModel->update($id, ['status' => 'published']);
            $this->redirect('/admin/post');
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function unpublish($id)
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }

        try {
            $this->postModel->update($id, ['status' => 'draft']);
            $this->redirect('/admin/post');
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function listDraftpost()
    {
        $page = isset($this->getQueryData()['page']) ? (int)$this->getQueryData()['page'] : 1;
        $postsPerPage = 10;
        try {
            $posts = $this->postModel->paginate('draft', $page, $postsPerPage);
            $this->view('admin/post/listdraftpost', ['posts' => $posts]);
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function showDraftpost($id)
    {
        try {
            $post = $this->postModel->getPostWithCategoryAndAuthor($id, 'draft');

            if (!$post) {
                $this->error404();
            }

            $comments = $this->commentModel->getCommentsForPost($id);

            $this->postModel->incrementViewCount($id);
            $this->view('admin/post/show', ['post' => $post, 'comments' => $comments]);
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function filterByCategory($categoryId)
    {
        try {
            $posts = $this->postModel->getByCategory($categoryId);
            $categories = $this->categoryModel->getAll();
            $this->renderIndexView($posts, $categories);
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function filterByAuthor($authorId)
    {
        try {
            $posts = $this->postModel->getByAuthor($authorId);
            $categories = $this->categoryModel->getAll();
            $this->renderIndexView($posts, $categories);
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function listCategory()
    {
        try {
            $categories = $this->categoryModel->getCategoriesWithPostCount();
            $this->view('/admin/category/index', ['categories' => $categories]);
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function createCategory()
    {

        if ($this->isPost()) {
            $data = $this->getPostData();
            $rules = [
                'name' => ['required' => true, 'min' => 3, 'max' => 50]
            ];

            if ($this->validator->validate($data, $rules)) {
                try {

                    if ($this->categoryModel->Exists($data['name'], 'name')) {
                        $this->validator->addError('name', 'Cette Category existe déjà.');
                    }

                    if (!$this->validator->getErrors()) {

                        $categoryId = $this->categoryModel->create($data);
                        $this->redirect('/admin/listCategory');
                    }
                } catch (\Exception $e) {
                    $this->error500($e->getMessage());
                }
            }
            $this->view('/admin/category/create', [
                'errors' => $this->validator->getErrors(),
                'oldInput' => $data
            ]);
        } else {
            $this->view('/admin/category/create');
        }
    }

    public function editCategory($id)
    {
        if (!$this->isAdmin()) {
            $this->error403();
        }

        try {
            $category = $this->categoryModel->getById($id);
            if (!$category) {
                $this->error404();
            }

            if ($this->isPost()) {
                $data = $this->getPostData();
                $rules = [
                    'name' => ['required' => true, 'min' => 3, 'max' => 50]
                ];

                if ($this->validator->validate($data, $rules)) {
                    $this->categoryModel->update($id, $data);
                    $this->redirect('/admin/listCategory');
                } else {
                    $this->view('/admin/category/edit', [
                        'category' => $category,
                        'errors' => $this->validator->getErrors(),
                        'oldInput' => $data
                    ]);
                }
            } else {
                $this->view('/admin/category/edit', ['category' => $category]);
            }
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function deleteCategory($id)
    {
        if (!$this->isAdmin()) {
            $this->error403();
        }

        try {
            $category = $this->categoryModel->getById($id);
            if (!$category) {
                $this->error404();
            }

            $this->categoryModel->delete($id);
            $this->redirect('/admin/listCategory');
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function listTag()
    {
        try {
            $tags = $this->tagModel->getAll();
            $this->view('admin/tag/index', ['tags' => $tags]);
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function createTag()
    {
        if ($this->isPost()) {
            $data = $this->getPostData();
            $rules = ['name' => ['required' => true, 'min' => 2, 'max' => 50]];

            if ($this->validator->validate($data, $rules)) {

                $data['slug'] = $this->generateSlug($data['name']);

                try {
                    $this->tagModel->create($data);
                    $this->redirect('/admin/listtag');
                } catch (\Exception $e) {
                    $this->error500($e->getMessage());
                }
            } else {
                $this->view('admin/tag/create', ['errors' => $this->validator->getErrors()]);
            }
        } else {
            $this->view('admin/tag/create');
        }
    }

    public function editTags($id)
    {
        try {
            $tag = $this->tagModel->getById($id);
            if (!$tag) {
                $this->error404();
            }

            if ($this->isPost()) {
                $data = $this->getPostData();
                $rules = ['name' => ['required' => true, 'min' => 2, 'max' => 50]];

                if ($this->validator->validate($data, $rules)) {
                    try {
                        $this->tagModel->update($id, $data);
                        $this->redirect('/admin/listTag');
                    } catch (\Exception $e) {
                        $this->error500($e->getMessage());
                    }
                } else {
                    $this->view('admin/tag/edit', [
                        'tag' => $tag,
                        'errors' => $this->validator->getErrors()
                    ]);
                }
            } else {
                $this->view('admin/tag/edit', ['tag' => $tag]);
            }
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function deleteTags($id)
    {
        try {
            $tag = $this->tagModel->getById($id);
            if (!$tag) {
                $this->error404();
            }

            $this->tagModel->delete($id);
            $this->redirect('/admin/listag');
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function listComments()
    {
        try {
            $comments = $this->commentModel->getAllCommentsWithAuthorAndPost();
            $this->view('admin/comment/index', ['comments' => $comments]);
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function approveComment($id)
    {
        try {
            $comment = $this->commentModel->getById($id);
            if (!$comment) {
                $this->error404();
            }

            $this->commentModel->approveComment($id);
            $this->redirect('/admin/listcomments');
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function deleteComment($id)
    {

        try {
            $comment = $this->commentModel->getById($id);
            if (!$comment) {
                $this->error404();
            }

            $this->commentModel->delete($id);
            $this->redirect('/admin/listcomments');
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    // Afficher le formulaire de paramètres
    public function settings()
    {

        $settings = $this->SettingModel->getAllSettings();

        $this->view('admin/settings', [
            'settings' => $settings
        ]);
    }

    // Mettre à jour les paramètres
    public function updateSettings()
    {

        if ($this->isPost()) {
            $data = $this->getPostData();

            // Gérer l'upload du logo
            if (!empty($_FILES['logo']['name'])) {

                $data['logo'] = $this->uploadImage($_FILES['logo']);
            }

            foreach ($data as $key => $value) {
                $this->SettingModel->updateSetting($key, $value);
            }

            $this->redirect('/admin/settings');
        }
    }

    public function feature($postId)
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }

        $isFeatured = isset($_POST['featured']) ? 1 : 0;
        $this->postModel->update($postId, $isFeatured);

        $this->redirect('/admin/posts');
    }
}
