<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PostModel;
use App\Models\CategoryModel;

class UserController extends BaseController
{
    protected $userModel;
    protected $categoryModel;
    protected $postModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
        $this->postModel = new postModel();
        $this->categoryModel = new categoryModel();
    }

    public function index()
    {
        $blogeurs = $this->userModel->countPostByUser();
        $this->view('/user/index', [
            'blogeurs' => $blogeurs
        ]);
    }

    public function list($userId)
    {
        $page = isset($this->getQueryData()['page']) ? (int)$this->getQueryData()['page'] : 1;
        $perPage = 10;
        try {

            $categories = $this->categoryModel->getCategoriesWithPostCount();
            $recentPosts = $this->postModel->getPostsWithCategoriesAndAuthors('published', 4);
            $posts = $this->getPostByUserpaginate($userId, $page, $perPage);
            $posts['user_id'] = $userId;

            $this->view('user/list', ['posts' => $posts, 'categories' => $categories, 'recentPosts' => $recentPosts]);
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function getPostByUserpaginate($userId, $page = 1, $perPage = 10)
    {
        try {
            $offset = ($page - 1) * $perPage;
            $total = $this->userModel->countPostByUserId($userId);

            $data = $this->postModel->getByUser($userId, $perPage, $offset);

            return [
                'data' => $data,
                'total' => $total,
                'page' => $page,
                'last_page' => ceil($total / $perPage)
            ];
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function register()
    {

        if ($this->isPost()) {
            $formData = $this->getPostData();
            $this->validateCsrfToken();
            $rules = [
                'username' => ['required' => true, 'min' => 3, 'max' => 50],
                'email' => ['required' => true, 'email' => true],
                'password' => ['required' => true, 'min' => 6],
                'confirm_password' => ['required' => true]
            ];

            // Validation des données
            if ($this->validator->validate($formData, $rules)) {

                $data = [
                    'username' => htmlspecialchars($formData['username']),
                    'email' => htmlspecialchars($formData['email']),
                    'password' => htmlspecialchars($formData['password'])
                ];

                // Vérification si les mots de passe correspondent
                if ($data['password'] !== $formData['confirm_password']) {
                    $this->validator->addError('confirm_password', 'Les mots de passe ne correspondent pas.');
                } else {
                    try {
                        // Vérification si l'email existe déjà
                        if ($this->userModel->Exists($data['email'], 'email')) {
                            $this->validator->addError('email', 'Cet email est déjà utilisé.');
                        }

                        // Vérification si le nom d'utilisateur existe déjà
                        if ($this->userModel->Exists($data['username'], 'username')) {
                            $this->validator->addError('username', 'Ce nom d\'utilisateur est déjà pris.');
                        }

                        // Si aucune erreur, procéder à la création de l'utilisateur
                        if (!$this->validator->getErrors()) {

                            $data['role'] = 'user';

                            // Création de l'utilisateur
                            $userId = $this->userModel->create($data);

                            // Connexion automatique après l'inscription
                            $this->login($data['email'], $data['password']);

                            $this->redirect('/user/profile');
                        }
                    } catch (\Exception $e) {
                        $this->error500($e->getMessage());
                    }
                }
            }
            $csrfToken = $this->generateCsrfToken();
            // Affichage du formulaire avec les erreurs et les données précédentes
            $this->view('/user/register', [
                'csrfToken' => $csrfToken,
                'errors' => $this->validator->getErrors(),
                'oldInput' => $data
            ]);
        } else {
            $csrfToken = $this->generateCsrfToken();
            // Affichage du formulaire d'inscription vide
            $this->view('/user/register', [
                'csrfToken' => $csrfToken
            ]);
        }
    }

    public function login()
    {
        if ($this->isPost()) {
            $formData = $this->getPostData();
            $this->validateCsrfToken();

            $rules = [
                'email' => ['required' => true, 'email' => true],
                'password' => ['required' => true]
            ];

            if ($this->validator->validate($formData, $rules)) {
                try {
                    $data = [
                        'email' => htmlspecialchars($formData['email']),
                        'password' => htmlspecialchars($formData['password'])
                    ];

                    $user = $this->userModel->findByEmail($data['email']);

                    if ($user && $this->userModel->verifyPassword($user[0]['id'], $data['password'])) {
                        $this->createUserSession($user[0]);
                        $this->userModel->updateLastLogin($user[0]['id']);
                        $this->redirect('/');
                    } else {
                        $this->validator->addError('login', 'Email ou mot de passe incorrect');
                    }
                } catch (\Exception $e) {
                    $this->error500($e->getMessage());
                }
            }
            $csrfToken = $this->generateCsrfToken();
            $this->view('/user/login', [
                'csrfToken' => $csrfToken,
                'errors' => $this->validator->getErrors(),
                'oldInput' => $data
            ]);
        } else {
            $csrfToken = $this->generateCsrfToken();
            $this->view('/user/login', [
                'csrfToken' => $csrfToken
            ]);
        }
    }

    public function logout()
    {
        $this->destroyUserSession();
        $this->redirect('/');
    }

    public function profile()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getById($userId);

        try {
            $user = $this->userModel->getById($userId);
            if (!$user) {
                $this->error404();
            }
            $this->view('/user/profile', ['user' => $user]);
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function profile_edit()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getById($userId);

        if (!$user) {
            $this->error404();
        }

        $user['csrfToken'] = $this->generateCsrfToken();

        if ($this->isPost()) {
            $formData = $this->getPostData();
            $this->validateCsrfToken();

            $rules = [
                'username' => ['required' => true, 'min' => 3, 'max' => 255],
                'email' => ['required' => true, 'email' => true]
            ];

            if ($this->validator->validate($formData, $rules)) {
                try {

                    $data = [
                        'email' => htmlspecialchars($formData['email']),
                        'username' => htmlspecialchars($formData['username']),
                        'bio' => htmlspecialchars($formData['bio'])
                    ];

                    $this->userModel->update($userId, $data);

                    // Gestion de la mise à jour de la photo de profil
                    if (!empty($_FILES['profile_picture']['name'])) {
                        $profilePicturePath = $this->uploadImage($_FILES['profile_picture']);
                        $data['profile_picture'] = $profilePicturePath;
                    }

                    $this->userModel->update($userId, $data);

                    $this->redirect('/user/profile');
                } catch (\Exception $e) {
                    $this->error500($e->getMessage());
                }
            } else {
                $this->view('/user/profile_edit', [
                    'user' => $user,
                    'errors' => $this->validator->getErrors(),
                    'oldInput' => $formData
                ]);
            }
        } else {
            $this->view('/user/profile_edit', ['user' => $user]);
        }
    }

    // Méthode pour supprimer le compte
    public function deleteUser()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }

        $userId = $_SESSION['user_id'];

        try {
            if ($this->userModel->exists($userId, 'id')) {
                $this->userModel->delete($userId);
                $this->destroyUserSession();

                $this->redirect('/');
            } else {
                $this->error404();
            }
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    private function createUserSession($user)
    {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['username'];
        $_SESSION['user_role'] = $user['role'];
    }

    private function destroyUserSession()
    {
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        session_destroy();
    }
}
