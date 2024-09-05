<?php

namespace App\Controllers;

use App\Core\Validator;
use App\Models\UserModel;

class BaseController
{
    protected $validator;
    protected $userModel;


    public function __construct()
    {
        $this->validator = new Validator();
        $this->userModel = new UserModel();
    }

    protected function view($view, $data = [])
    {
        extract($data);

        ob_start();

        $viewPath = BASE_PATH . "app/Views/{$view}.php";
        if (file_exists($viewPath)) {
            require $viewPath;
        } else {
            $this->error404();
        }
        $content = ob_get_clean();

        // Vérifier si le contrôleur est AdminController
        $layout = (strpos(get_class($this), 'AdminController') !== false)
            ? __DIR__ . '/../views/admin/layouts/main.php'
            : __DIR__ . '/../views/layouts/main.php';

        require_once $layout;
    }


    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function error403()
    {
        header('HTTP/1.1 403 Forbidden');
        echo '<h1>403 Forbidden</h1>';
        echo '<p>Vous n\'avez pas l\'autorisation d\'accéder à cette ressource.</p>';
        exit;
    }

    protected function error404()
    {
        header("HTTP/1.0 404 Not Found");
        $this->view('errors/404');
        exit;
    }

    protected function error500($message = "Une erreur interne est survenue.")
    {
        header("HTTP/1.0 500 Internal Server Error");
        $this->view('errors/500', ['message' => $message]);
        exit;
    }

    protected function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function getPostData()
    {
        return $_POST;
    }

    protected function getQueryData()
    {
        return $_GET;
    }

    protected function isAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }

    protected function isAdmin()
    {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        // Récupérer le rôle de l'utilisateur à partir de la base de données
        $userRole = $this->userModel->getUserRoleById($_SESSION['user_id']);
        return $userRole === 'admin';
    }

    protected function generateCsrfToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    protected function validateCsrfToken()
    {
        if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            $this->error403();
        }
    }

    protected function uploadImage($file)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Erreur lors du téléchargement de l\'image.');
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($file['type'], $allowedTypes)) {
            throw new \Exception('Type de fichier non autorisé.');
        }

        if ($file['size'] > 2 * 1024 * 1024) { // Limiter à 2 Mo
            throw new \Exception('La taille de l\'image dépasse la limite autorisée.');
        }

        // Utilisation d'un chemin relatif pour le stockage des images
        $uploadDir = 'storage/images/';
        $fileName = uniqid() . '-' . basename($file['name']);
        $filePath = $uploadDir . $fileName;

        // Vérification que le répertoire existe, sinon, le créer
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        if (!move_uploaded_file($file['tmp_name'], $filePath)) {
            throw new \Exception('Erreur lors du déplacement de l\'image.');
        }

        return $filePath;
    }

    // Supprimer l'image du serveur
    protected function deleteImage($imagePath)
    {
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    function generateSlug($string)
    {
        // Convertir la chaîne en minuscules
        $string = strtolower($string);

        // Supprimer les accents
        $string = str_replace(
            ['à', 'â', 'ä', 'á', 'ã', 'å', 'ā', 'è', 'é', 'ê', 'ë', 'ē', 'ė', 'ę', 'î', 'ï', 'í', 'ī', 'ì', 'ô', 'ö', 'ò', 'ó', 'õ', 'œ', 'ø', 'ō', 'ù', 'ü', 'ú', 'ū', 'ñ', 'ç', 'ß', 'ÿ', 'ý'],
            ['a', 'a', 'a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'oe', 'o', 'o', 'u', 'u', 'u', 'u', 'n', 'c', 'ss', 'y', 'y'],
            $string
        );

        // Remplacer les caractères non alphanumériques (à l'exception des tirets) par des tirets
        $string = preg_replace('/[^a-z0-9\-]/', '-', $string);

        // Supprimer les tirets multiples générés
        $string = preg_replace('/-+/', '-', $string);

        // Supprimer les tirets en début et fin de chaîne
        $string = trim($string, '-');

        return $string;
    }
}
