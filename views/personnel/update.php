<?php
require_once __DIR__ . '/../../controllers/PersonnelController.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // Validate CSRF token
    if (!isset($_POST['csrf_token']) || !isset($_SESSION['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        throw new Exception("Token de sécurité invalide");
    }

    // Get ID from URL path
    $basePath = '/APP_SGRHBMKH';
    $url = $_SERVER['REQUEST_URI'];
    if (strpos($url, $basePath) === 0) {
        $url = substr($url, strlen($basePath));
    }
    $urlParts = explode('/', trim($url, '/'));
    
    // The ID should be the last part of personnel/update/{id}
    $id = isset($urlParts[2]) ? filter_var($urlParts[2], FILTER_VALIDATE_INT) : null;
    
    if (!$id) {
        throw new Exception("Identifiant du personnel invalide");
    }

    $controller = new PersonnelController();
    $controller->update($id);

} catch (Exception $e) {
    error_log("Error in personnel update: " . $e->getMessage());
    $_SESSION['error'] = $e->getMessage();
    header('Location: /APP_SGRHBMKH/personnel');
    exit;
}
