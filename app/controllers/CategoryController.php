<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\PostModel;

class CategoryController extends BaseController
{
    private $categoryModel;
    protected $postModel;

    public function __construct()
    {
        parent::__construct();
        $this->categoryModel = new CategoryModel();
        $this->postModel = new PostModel();
    }

    public function index()
    {
        try {
            $categories = $this->categoryModel->getCategoriesWithPostCount();
            $this->view('category/index', ['categories' => $categories]);
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function list($categoryId)
    {
        $page = isset($this->getQueryData()['page']) ? (int)$this->getQueryData()['page'] : 1;
        $perPage = 10;

        try {

            $categories = $this->categoryModel->getCategoriesWithPostCount();
            $recentPosts = $this->postModel->getPostsWithCategoriesAndAuthors('published', 4);
            $posts = $this->getPostByCategoriespaginate($categoryId, $page, $perPage);
            $posts['category'] = $this->categoryModel->getById($categoryId);

            $this->view('category/list', ['posts' => $posts, 'categories' => $categories, 'recentPosts' => $recentPosts]);
        } catch (\Exception $e) {
            $this->error500($e->getMessage());
        }
    }

    public function getPostByCategoriespaginate($categoryId, $page = 1, $perPage = 2)
    {
        try {
            $offset = ($page - 1) * $perPage;
            $data = $this->postModel->getByCategory($categoryId, $perPage, $offset);
            $total = $this->postModel->getCountPostByCategory($categoryId);

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
}
