<?php
// Ensure proper session handling
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Initialize CSRF token if not exists
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}


// Autoload des classes
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/controllers/',
        __DIR__ . '/models/',
    ];

    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Fonction pour gérer les erreurs 404
function notFound() {
    http_response_code(404);
    require __DIR__ . '/views/errors/404.php';
    exit();
}

// Parse l'URL
$url = $_SERVER['REQUEST_URI'];
// Retirer le chemin de base de l'URL
$basePath = '/APP_SGRHBMKH';
if (strpos($url, $basePath) === 0) {
    $url = substr($url, strlen($basePath));
}
$url = strtok($url, '?'); // Retire les paramètres GET
$urlParts = explode('/', trim($url, '/'));

// Routes par défaut
if (empty($urlParts[0])) {
    header('Location: ' . $basePath . '/auth/login');
    exit();
}

// Routage
try {
    switch ($urlParts[0]) {
        case 'personnel':
            $controller = new PersonnelController();
            
            if (!isset($urlParts[1])) {
                $controller->index();
            } else {
                switch ($urlParts[1]) {
                    case 'create':
                        $controller->create();
                        break;
                    case 'store':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->store();
                        } else {
                            notFound();
                        }
                        break;
                    case 'edit':
                        if (!isset($urlParts[2])) notFound();
                        $controller->edit($urlParts[2]);
                        break;
                    case 'update':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            if (!isset($urlParts[2])) notFound();
                            $controller->update($urlParts[2]);
                        } else {
                            notFound();
                        }
                        break;
                    case 'show':
                        if (!isset($urlParts[2])) notFound();
                        $controller->show($urlParts[2]);
                        break;
                    case 'delete':
                        if (!isset($urlParts[2])) notFound();
                        $controller->delete($urlParts[2]);
                        break;
                    default:
                        notFound();
                }
            }
            break;

        case 'mouvements':
            $controller = new MouvementPersonnelController();
            
            if (!isset($urlParts[1])) {
                $controller->index();
            } else {
                switch ($urlParts[1]) {
                    case 'create':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            // Handle form submission
                            $controller->create();
                        } else {
                            // Display create form
                            $controller->create();
                        }
                        break;
                    case 'show':
                        if (!isset($urlParts[2])) notFound();
                        $controller->show($urlParts[2]);
                        break;
                    case 'delete':
                        if (!isset($urlParts[2])) notFound();
                        $controller->delete($urlParts[2]);
                        break;
                    case 'stats':
                        $controller->stats();
                        break;
                    case 'by-type':
                        if (!isset($urlParts[2])) notFound();
                        $controller->byType($urlParts[2]);
                        break;
                    default:
                        notFound();
                }
            }
            break;

        case 'provinces':
            $controller = new ProvinceController();
            
            if (!isset($urlParts[1])) {
                $controller->index();
            } else {
                switch ($urlParts[1]) {
                    case 'create':
                        $controller->create();
                        break;
                    case 'edit':
                        if (!isset($urlParts[2])) notFound();
                        $controller->edit($urlParts[2]);
                        break;
                    case 'delete':
                        if (!isset($urlParts[2])) notFound();
                        $controller->delete($urlParts[2]);
                        break;
                    default:
                        notFound();
                }
            }
            break;

        case 'corps':
            $controller = new CorpsController();
            
            if (!isset($urlParts[1])) {
                $controller->index();
            } else {
                switch ($urlParts[1]) {
                    case 'create':
                        $controller->create();
                        break;
                    case 'edit':
                        if (!isset($urlParts[2])) notFound();
                        $controller->edit($urlParts[2]);
                        break;
                    case 'show':
                        if (!isset($urlParts[2])) notFound();
                        $controller->show($urlParts[2]);
                        break;
                    case 'delete':
                        if (!isset($urlParts[2])) notFound();
                        $controller->delete($urlParts[2]);
                        break;
                    default:
                        notFound();
                }
            }
            break;

        case 'grades':
            $controller = new GradeController();
            
            if (!isset($urlParts[1])) {
                $controller->index();
            } else {
                switch ($urlParts[1]) {
                    case 'create':
                        $controller->create();
                        break;
                    case 'edit':
                        if (!isset($urlParts[2])) notFound();
                        $controller->edit($urlParts[2]);
                        break;
                    case 'delete':
                        if (!isset($urlParts[2])) notFound();
                        $controller->delete($urlParts[2]);
                        break;
                    default:
                        notFound();
                }
            }
            break;

        case 'categories':
            $controller = new CategorieEtablissementController();
            
            if (!isset($urlParts[1])) {
                $controller->index();
            } else {
                switch ($urlParts[1]) {
                    case 'create':
                        $controller->create();
                        break;
                    case 'edit':
                        if (!isset($urlParts[2])) notFound();
                        $controller->edit($urlParts[2]);
                        break;
                    case 'show':
                        if (!isset($urlParts[2])) notFound();
                        $controller->show($urlParts[2]);
                        break;
                    case 'delete':
                        if (!isset($urlParts[2])) notFound();
                        $controller->delete($urlParts[2]);
                        break;
                    default:
                        notFound();
                }
            }
            break;

        case 'formations':
            $controller = new FormationSanitaireController();
            
            if (!isset($urlParts[1])) {
                $controller->index();
            } else {
                switch ($urlParts[1]) {
                    case 'create':
                        $controller->create();
                        break;
                    case 'edit':
                        if (!isset($urlParts[2])) notFound();
                        $controller->edit($urlParts[2]);
                        break;
                    case 'show':
                        if (!isset($urlParts[2])) notFound();
                        $controller->show($urlParts[2]);
                        break;
                    case 'delete':
                        if (!isset($urlParts[2])) notFound();
                        $controller->delete($urlParts[2]);
                        break;
                    default:
                        notFound();
                }
            }
            break;

        case 'rapports':
            if (!isset($urlParts[1])) {
                notFound();
            } else {
            $controller = new RapportController(); // Parent constructor will check authentication
            switch ($urlParts[1]) {
                    case 'effectifs':
                        $controller->effectifs();
                        break;
                    case 'mouvements':
                        $controller->mouvements();
                        break;
                    case 'etablissements':
                        $controller->etablissements();
                        break;
                    default:
                        notFound();
                }
            }
            break;

        case 'dashboard':
            $controller = new DashboardController();
            $controller->index();
            break;

        case 'users':
            $controller = new UserController();
            
            if (!isset($urlParts[1])) {
                $controller->index();
            } else {
                switch ($urlParts[1]) {
                    case 'create':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->create();
                        } else {
                            $controller->create();
                        }
                        break;
                    case 'edit':
                        if (!isset($urlParts[2])) notFound();
                        $controller->edit($urlParts[2]);
                        break;
                    case 'toggle-status':
                        if (!isset($urlParts[2])) notFound();
                        $controller->toggleStatus($urlParts[2]);
                        break;
                    default:
                        notFound();
                }
            }
            break;

        case 'notifications':
            $controller = new NotificationController();
            if (!isset($urlParts[1])) {
                notFound();
            } else {
                switch ($urlParts[1]) {
                    case 'retraite':
                        $controller->retraite();
                        break;
                    default:
                        notFound();
                }
            }
            break;

        case 'auth':
            $controller = new AuthController();
            if (!isset($urlParts[1])) {
                notFound();
            } else {
                switch ($urlParts[1]) {
                    case 'login':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->login();
                        } else {
                            $controller->showLoginForm();
                        }
                        break;
                    case 'register':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->register();
                        } else {
                            $controller->showRegistrationForm();
                        }
                        break;
                    case 'profile':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->updateProfile();
                        } else {
                            $controller->showProfile();
                        }
                        break;
                    case 'forgot-password':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->forgotPassword();
                        } else {
                            $controller->forgotPassword();
                        }
                        break;
                    case 'reset-password':
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $controller->resetPassword();
                        } else {
                            $controller->resetPassword();
                        }
                        break;
                    case 'logout':
                        $controller->logout();
                        break;
                    default:
                        notFound();
                }
            }
            break;

        case 'export':
            $controller = new ExportController();
            if (!isset($urlParts[1])) {
                notFound();
            } else {
                switch ($urlParts[1]) {
                    case 'staff':
                        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['format'])) {
                            $controller->exportStaffList();
                        } else {
                            $controller->staff();
                        }
                        break;
                    case 'movements':
                        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['format'])) {
                            $controller->exportMovementList();
                        } else {
                            $controller->movements();
                        }
                        break;
                    case 'establishments':
                        if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['format'])) {
                            $controller->exportEstablishmentsList();
                        } else {
                            $controller->establishments();
                        }
                        break;
                    default:
                        notFound();
                }
            }
            break;

        case 'api':
            if (!isset($urlParts[1])) notFound();
            switch ($urlParts[1]) {
                case 'grades':
                    if (!isset($urlParts[2])) notFound();
                    $controller = new GradeController();
                    $controller->byCorps($urlParts[2]);
                    break;
                case 'formations':
                    if (!isset($urlParts[2])) notFound();
                    $controller = new FormationSanitaireController();
                    if ($urlParts[2] === 'by-province' && isset($urlParts[3])) {
                        $controller->byProvince($urlParts[3]);
                    } elseif ($urlParts[2] === 'by-categorie' && isset($urlParts[3])) {
                        $controller->byCategorie($urlParts[3]);
                    } else {
                        notFound();
                    }
                    break;
                default:
                    notFound();
            }
            break;

        default:
            notFound();
    }
} catch (Exception $e) {
    // Log l'erreur
    error_log($e->getMessage());
    
    // Affiche une page d'erreur
    http_response_code(500);
    require __DIR__ . '/views/errors/500.php';
}
