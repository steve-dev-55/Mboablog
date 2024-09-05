<?php

namespace App\Controllers;

use App\Traits\PostOperationsTrait;
use App\Models\PostModel;
use App\Models\CommentModel;

class PostController extends BaseController
{
    protected $postModel;
    private $commentModel;

    use PostOperationsTrait;

    public function __construct()
    {
        parent::__construct();
        $this->postModel = new PostModel();
        $this->commentModel = new CommentModel();
        $this->initModels(); // Initialiser les modèles nécessaires
    }

    protected function renderIndexView($posts)
    {
        $popularPost = $this->postModel->getPopulardPosts();
        $categories = $this->categoryModel->getCategoriesWithPostCount();

        $this->view('post/index', ['posts' => $posts, 'popularPost' => $popularPost, 'categories' => $categories]);
    }

    protected function renderShowView($post, $comments)
    {
        $recentPosts = $this->postModel->getPostsWithCategoriesAndAuthors('published', 4);
        $populartag = $this->tagModel->getTagsWithPostCount();
        $post['tag'] = $this->tagModel->getTagsForPost($post['id']);
        $categories = $this->categoryModel->getCategoriesWithPostCount();

        $this->view('post/show', ['post' => $post, 'comments' => $comments, 'recentPosts' => $recentPosts, 'populartag' => $populartag, 'categories' => $categories]);
    }

    protected function renderCreateView($csrfToken, $data = [], $errors = [], $categories = [])
    {
        $this->view('post/create', ['errors' => $errors, 'oldInput' => $data, 'categories' => $categories, 'csrfToken' => $csrfToken]);
    }

    protected function handleCreateRedirect($postId)
    {
        $this->redirect("/post/postSucces");
    }

    protected function renderEditView($post, $data = [], $errors = [], $categories = [])
    {
        $this->view('post/edit', ['post' => $post, 'errors' => $errors, 'oldInput' => $data, 'categories' => $categories]);
    }

    protected function handleEditRedirect($id)
    {
        $this->redirect("/post/show/{$id}");
    }

    protected function handleDeleteRedirect()
    {
        $this->redirect('/post');
    }

    protected function handleSearchRedirect()
    {
        $this->redirect('/post/search_results');
    }

    protected function renderSearchView($post, $keyword)
    {
        $categories = $this->categoryModel->getCategoriesWithPostCount();
        $recentPosts = $this->postModel->getPostsWithCategoriesAndAuthors('published', 4);

        $this->view('/post/search_results', ['posts' => $post, 'keyword' => $keyword, 'recentPosts' => $recentPosts, 'categories' => $categories]);
    }

    protected function isAuthor($user_id)
    {
        return ($_SESSION['user_id'] === $user_id);
    }

    public function postSucces()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }

        $this->view('/post/succes');
    }
}
