<?php
abstract class Controller {
    public function __construct() {
        // Check if this is an auth route
        $url = $_SERVER['REQUEST_URI'];
        $basePath = '/APP_SGRHBMKH';
        if (strpos($url, $basePath) === 0) {
            $url = substr($url, strlen($basePath));
        }
        $url = strtok($url, '?');
        $urlParts = explode('/', trim($url, '/'));

        // List of routes that don't require authentication
        $publicRoutes = [
            'auth/login',
            'auth/register',
            'auth/forgot-password',
            'auth/reset-password'
        ];

        // Check if current route requires authentication
        $currentRoute = implode('/', array_slice($urlParts, 0, 2));
        if (!in_array($currentRoute, $publicRoutes)) {
            $this->requireAuth();
        }
    }

    protected function render($view, $data = []) {
        // Extract data to make variables available in view
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include the view file
        $viewFile = __DIR__ . "/../views/" . $view . ".php";
        if (file_exists($viewFile)) {
            require $viewFile;
        } else {
            throw new Exception("View file not found: " . $view);
        }
        
        // Get contents and clean buffer
        $content = ob_get_clean();
        
        // Include the layout
        require __DIR__ . "/../views/layout/main.php";
    }

    protected function redirect($url) {
        header("Location: " . $url);
        exit();
    }

    protected function json($data) {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    protected function isPost() {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    protected function isGet() {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    protected function getPostData() {
        return $_POST;
    }

    protected function getQueryParams() {
        return $_GET;
    }

    protected function sanitize($data) {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->sanitize($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }

    protected function setFlashMessage($type, $message) {
        if (!isset($_SESSION['flash_messages'])) {
            $_SESSION['flash_messages'] = [];
        }
        $_SESSION['flash_messages'][] = [
            'type' => $type,
            'message' => $message
        ];
    }

    protected function getFlashMessages() {
        $messages = isset($_SESSION['flash_messages']) ? $_SESSION['flash_messages'] : [];
        unset($_SESSION['flash_messages']);
        return $messages;
    }

    protected function validateCSRF() {
        error_log("Validating CSRF token - POST: " . ($_POST['csrf_token'] ?? 'not set') . ", SESSION: " . ($_SESSION['csrf_token'] ?? 'not set'));
        
        if (!isset($_POST['csrf_token']) || empty($_POST['csrf_token'])) {
            error_log("CSRF validation failed: token missing in POST data");
            $this->setFlashMessage('error', 'CSRF token manquant');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/APP_SGRHBMKH/');
            return false;
        }

        if (!isset($_SESSION['csrf_token']) || empty($_SESSION['csrf_token'])) {
            error_log("CSRF validation failed: token missing in session");
            $this->setFlashMessage('error', 'Session expirée, veuillez réessayer');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/APP_SGRHBMKH/');
            return false;
        }

        if ($_POST['csrf_token'] !== $_SESSION['csrf_token']) {
            error_log("CSRF validation failed: tokens don't match");
            $this->setFlashMessage('error', 'Token de sécurité invalide');
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/APP_SGRHBMKH/');
            return false;
        }

        error_log("CSRF validation successful");
        return true;
    }

    protected function generateCSRFToken() {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        error_log("Generated new CSRF token: " . $token);
        return $token;
    }

    protected function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->setFlashMessage('error', 'Veuillez vous connecter pour accéder à cette page');
            $this->redirect('/APP_SGRHBMKH/auth/login');
            exit();
        }
        return true;
    }

    protected function requireRole($role) {
        $this->requireAuth();
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== $role) {
            $this->setFlashMessage('error', 'Vous n\'avez pas les droits nécessaires pour accéder à cette page');
            $this->redirect('/dashboard');
        }
    }

    protected function getPagination($total, $perPage, $currentPage) {
        $totalPages = ceil($total / $perPage);
        $currentPage = max(1, min($currentPage, $totalPages));
        
        return [
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $currentPage,
            'total_pages' => $totalPages,
            'has_previous' => $currentPage > 1,
            'has_next' => $currentPage < $totalPages,
            'previous_page' => $currentPage - 1,
            'next_page' => $currentPage + 1,
            'offset' => ($currentPage - 1) * $perPage
        ];
    }
}
