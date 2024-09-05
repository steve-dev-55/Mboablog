<?php

namespace App\Traits;

use App\Models\PostModel;
use App\Models\CategoryModel;
use App\Models\UserModel;
use App\Models\TagModel;

trait PostOperationsTrait
{
    protected $postModel;
    protected $categoryModel;
    protected $userModel;
    protected $tagModel;


    public function initModels()
    {
        $this->postModel = new PostModel();
        $this->categoryModel = new CategoryModel();
        $this->userModel = new UserModel();
        $this->tagModel = new TagModel();
    }

    public function index()
    {
        $page = isset($this->getQueryData()['page']) ? (int)$this->getQueryData()['page'] : 1;
        $postsPerPage = 10;
        try {
            $posts = $this->postModel->paginate('published', $page, $postsPerPage);
            $this->renderIndexView($posts);
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $post = $this->postModel->getPostWithCategoryAndAuthor($id);

            if (!$post) {
                $this->error404();
            }

            $comments = $this->commentModel->getCommentsByPostId($id);

            $this->postModel->incrementViewCount($id);
            $this->rendershowView($post, $comments);
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function create()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/user/login');
        }

        if ($this->isPost()) {
            $formData = $this->getPostData();
            $this->validateCsrfToken();

            // Validation des données
            $rules = [
                'title' => ['required' => true, 'min' => 3, 'max' => 255],
                'content' => ['required' => true, 'min' => 10],
                'category_id' => ['required' => true]
            ];

            if ($this->validator->validate($formData, $rules)) {
                try {

                    // Traitement et sécurisation des données
                    $data = [
                        'title' => htmlspecialchars($formData['title']),
                        'content' => htmlspecialchars($formData['content']),
                        'category_id' => (int)$formData['category_id'],
                        'user_id' => $this->getCurrentUserId()
                    ];

                    // Gestion de l'image
                    if (!empty($_FILES['image_path']['name'])) {
                        $imagePath = $this->uploadImage($_FILES['image_path']);
                        $data['image_path'] = $imagePath;
                    }

                    // Traitement des tags
                    $tags = isset($formData['tags']) && !empty($formData['tags']) ? explode(',', htmlspecialchars($formData['tags'])) : [];

                    // Création de l'article
                    $postId = $this->postModel->create($data);

                    // Gérer les tags
                    foreach ($tags as $tag) {
                        $tag = trim($tag);
                        $tagResult = $this->tagModel->getByTableName($tag, 'name');

                        if (!empty($tagResult['id'])) {
                            $this->tagModel->attachTagsToPost($postId, $tagResult['id']);
                        } else {
                            $dataTag = [
                                'name' => $tag,
                                'slug' => $this->generateSlug($tag)
                            ];
                            $tagId = $this->tagModel->create($dataTag);
                            $this->tagModel->attachTagsToPost($postId, $tagId);
                        }
                    }

                    // Redirection après la création
                    $this->handleCreateRedirect($postId);
                } catch (\Exception $e) {
                    $this->error500($e->getMessage());
                }
            } else {
                $categories = $this->categoryModel->getAll();
                $csrfToken = $this->generateCsrfToken();
                $this->renderCreateView($csrfToken, $formData, $this->validator->getErrors(), $categories);
            }
        } else {
            $categories = $this->categoryModel->getAll();
            $csrfToken = $this->generateCsrfToken();
            $this->renderCreateView($csrfToken, [], [], $categories);
        }
    }



    public function edit($id)
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/user/login');
        }

        try {
            $post = $this->postModel->getById($id);
            if (!$post || $post['user_id'] !== $this->getCurrentUserId()) {
                $this->error403(); // Unauthorized
            }

            $post['csrfToken'] = $this->generateCsrfToken();

            // Charger les tags associés à l'article
            $tags = $this->tagModel->getTagsForPost($id);
            $post['tags'] = implode(', ', array_column($tags, 'name'));

            if ($this->isPost()) {
                $formData = $this->getPostData();
                $this->validateCsrfToken();

                // Validation des données
                $rules = [
                    'title' => ['required' => true, 'min' => 3, 'max' => 255],
                    'content' => ['required' => true, 'min' => 10],
                    'category_id' => ['required' => true]
                ];

                if ($this->validator->validate($formData, $rules)) {
                    try {

                        // Traitement et sécurisation des données
                        $data = [
                            'title' => htmlspecialchars($formData['title']),
                            'content' => htmlspecialchars($formData['content']),
                            'category_id' => (int)$formData['category_id'],
                            'featured' => isset($formData['featured']) ? 1 : 0
                        ];

                        // Gestion de l'image
                        if (!empty($_FILES['image_path']['name'])) {
                            $imagePath = $this->uploadImage($_FILES['image_path']);
                            if ($post['image_path']) {
                                $this->deleteImage($post['image_path']);
                            }
                            $data['image_path'] = $imagePath;
                        } else {
                            $data['image_path'] = $post['image_path'];
                        }

                        // Traitement des tags
                        $tags = isset($formData['tags']) && !empty($formData['tags']) ? explode(',', htmlspecialchars($formData['tags'])) : [];

                        $this->postModel->update($id, $data);

                        // Gérer les tags
                        $this->tagModel->detachTagsFromPost($id); // Détacher les anciens tags
                        foreach ($tags as $tag) {
                            $tag = trim($tag);
                            $tagResult = $this->tagModel->getByTableName($tag, 'name');

                            if (!empty($tagResult['id'])) {
                                $this->tagModel->attachTagsToPost($id, $tagResult['id']);
                            } else {
                                $dataTag = [
                                    'name' => $tag,
                                    'slug' => $this->generateSlug($tag)
                                ];
                                $tagId = $this->tagModel->create($dataTag);
                                $this->tagModel->attachTagsToPost($id, $tagId);
                            }
                        }

                        $this->handleEditRedirect($id);
                    } catch (\Exception $e) {
                        $this->error500($e->getMessage());
                    }
                } else {
                    $categories = $this->categoryModel->getAll();
                    $this->renderEditView($post, $formData, $this->validator->getErrors(), $categories);
                }
            } else {
                $categories = $this->categoryModel->getAll();
                $this->renderEditView($post, [], [], $categories);
            }
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }


    public function delete($id)
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/user/login');
        }

        try {
            $post = $this->postModel->getById($id);
            if (!$post || $post['user_id'] !== $this->getCurrentUserId()) {
                $this->error403(); // Unauthorized
            }

            if ($post['image']) {
                $this->deleteImage($post['image']);
            }

            $this->postModel->delete($id);
            $this->handleDeleteRedirect();
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    protected function getCurrentUserId()
    {
        // Assume a method that returns the currently authenticated user's ID
        return $_SESSION['user_id'] ?? null;
    }

    public function search()
    {
        $page = isset($this->getQueryData()['page']) ? (int)$this->getQueryData()['page'] : 1;

        if ($this->isGet() && !empty($_GET['keyword'])) {

            $keyword = $this->getQueryData();

            if (!empty($keyword)) {
                $posts = $this->postModel->getSearchPost($keyword['keyword'], 'published', $page);

                $this->renderSearchView($posts, $keyword['keyword']);
            } else {
                $this->handleSearchRedirect(); // Rediriger si aucun mot-clé n'est fourni
            }
        } else {
            $this->handleSearchRedirect();
        }
    }

    protected abstract function renderIndexView($posts);

    protected abstract function rendershowView($post, $comments);

    protected abstract function renderCreateView($csrfToken, $data = [], $errors = [], $categories = []);

    protected abstract function handleCreateRedirect($postId);

    protected abstract function renderEditView($post, $data = [], $errors = [], $categories = []);

    protected abstract function handleEditRedirect($id);

    protected abstract function handleDeleteRedirect();

    protected abstract function renderSearchView($post, $keyword);

    protected abstract function handleSearchRedirect();
}
