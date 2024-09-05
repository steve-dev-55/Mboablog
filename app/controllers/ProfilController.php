<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfilController extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new UserModel();
    }

    public function show()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getById($userId);

        if (!$user) {
            $this->error404();
        }

        $this->view('profil/show', ['user' => $user]);
    }

    public function edit()
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('/login');
        }

        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getById($userId);

        if (!$user) {
            $this->error404();
        }

        if ($this->isPost()) {
            $data = $this->getPostData();
            $rules = [
                'username' => ['required' => true, 'min' => 3, 'max' => 255],
                'email' => ['required' => true, 'email' => true]
            ];

            if ($this->validator->validate($data, $rules)) {
                try {
                    $this->userModel->update($userId, $data);

                    // Gestion de la mise à jour de la photo de profil
                    if (!empty($_FILES['profile_picture']['name'])) {
                        $profilePicturePath = $this->uploadImage($_FILES['profile_picture']);
                        $this->userModel->updateProfilePicture($userId, $profilePicturePath);
                    }

                    $this->redirect('/profil');
                } catch (\Exception $e) {
                    $this->error500($e->getMessage());
                }
            } else {
                $this->view('profil/edit', [
                    'user' => $user,
                    'errors' => $this->validator->getErrors(),
                    'oldInput' => $data
                ]);
            }
        } else {
            $this->view('profil/edit', ['user' => $user]);
        }
    }
}
