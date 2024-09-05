<?php

namespace App\Controllers;

use App\Models\CommentModel;
use App\Models\PostModel;

class CommentController extends BaseController
{
    private $commentModel;
    private $postModel;

    public function __construct()
    {
        parent::__construct();
        $this->commentModel = new CommentModel();
        $this->postModel = new PostModel();
    }

    public function create($postId)
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }

        if ($this->isPost()) {
            $data = $this->getPostData();
            $rules = [
                'content' => ['required' => true, 'min' => 3, 'max' => 1000],
            ];

            if ($this->validator->validate($data, $rules)) {
                try {
                    $post = $this->postModel->getById($postId);
                    if (!$post) {
                        $this->error404();
                    }

                    $data['post_id'] = $postId;
                    $data['user_id'] = $_SESSION['user_id'];
                    $data['status'] = 'pending'; // ou 'approved' si pas de modération
                    $this->commentModel->create($data);
                    $this->redirect("/post/show/{$postId}");
                } catch (\Exception $e) {
                    $this->error500($e->getMessage());
                }
            } else {
                $this->view('post/show', [
                    'post' => $this->postModel->getById($postId),
                    'commentErrors' => $this->validator->getErrors(),
                    'oldCommentInput' => $data
                ]);
            }
        } else {
            $this->redirect("/post/show/{$postId}");
        }
    }
}
