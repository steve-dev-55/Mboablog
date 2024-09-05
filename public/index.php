<?php
session_start();

// Définir le chemin de base
define('BASE_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

// Charger l'autoloader de Composer
require_once BASE_PATH . 'vendor/autoload.php';

use App\Models\SettingModel;

// Vérifier si les settings sont déjà en session
if (!isset($_SESSION['settings'])) {
    // Si non, les récupérer depuis la base de données
    $settingsModel = new SettingModel();
    $settings = $settingsModel->getAllSettings();

    // Stocker les settings dans la session
    $_SESSION['settings'] = $settings;
} else {
    // Sinon, récupérer les settings depuis la session
    $settings = $_SESSION['settings'];
}

use App\Core\App;

$app = new App();
$SettingModel = new SettingModel();
