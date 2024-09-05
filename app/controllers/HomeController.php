<?php

namespace App\Controllers;

use App\Models\PostModel;
use App\Models\CategoryModel;

class HomeController extends BaseController
{
    protected $postModel;
    protected $categoryModel;

    public function __construct()
    {
        parent::__construct();
        $this->postModel = new PostModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index()
    {

        $recentPosts = $this->postModel->getPostsWithCategoriesAndAuthors('published', 4);
        $categories = $this->categoryModel->getCategoriesWithPostCount();
        $featuredPosts = $this->postModel->getFeaturedPosts();
        $popularPost = $this->postModel->getPopulardPosts();
        $blogeurs = $this->userModel->countPostByUser();

        $this->view('home/index', [
            'recentPosts' => $recentPosts,
            'categories' => $categories,
            'featuredPosts' => $featuredPosts,
            'popularPost' => $popularPost,
            'blogeurs' => $blogeurs
        ]);
    }
}
