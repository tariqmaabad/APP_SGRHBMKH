<?php
class AuthController extends Controller {
    private $authModel;

    public function __construct() {
        parent::__construct();
        $this->authModel = new Auth();
    }

    protected function render($view, $data = []) {
        // Auth views don't use the main layout
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
        
        // Output the buffer directly
        echo ob_get_clean();
    }

    public function showLoginForm() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/APP_SGRHBMKH/dashboard');
        }

        $this->render('auth/login', [
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    public function showRegistrationForm() {
        if (isset($_SESSION['user_id'])) {
            $this->redirect('/APP_SGRHBMKH/dashboard');
        }

        $this->render('auth/register', [
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    public function register() {
        if ($this->isPost()) {
            $this->validateCSRF();

            $data = [
                'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                'password' => $_POST['password'] ?? '',
                'password_confirm' => $_POST['password_confirm'] ?? '',
                'nom' => htmlspecialchars($_POST['nom'] ?? ''),
                'prenom' => htmlspecialchars($_POST['prenom'] ?? ''),
                'telephone' => htmlspecialchars($_POST['telephone'] ?? ''),
                'adresse' => htmlspecialchars($_POST['adresse'] ?? '')
            ];

            $errors = $this->validateRegistration($data);

            if (empty($errors)) {
                unset($data['password_confirm']);
                
                if ($this->authModel->createUser($data)) {
                    $this->setFlashMessage('success', 'Inscription réussie. Veuillez vous connecter.');
                    $this->redirect('/APP_SGRHBMKH/auth/login');
                } else {
                    $this->setFlashMessage('error', "Une erreur s'est produite lors de l'inscription.");
                }
            }

            $this->render('auth/register', [
                'errors' => $errors,
                'data' => $data,
                'csrf_token' => $this->generateCSRFToken()
            ]);
        } else {
            $this->redirect('/APP_SGRHBMKH/auth/register');
        }
    }

    public function login() {
        if ($this->isPost()) {
            $this->validateCSRF();

            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            $password = $_POST['password'] ?? '';

            $errors = [];

            if (!$email) {
                $errors['email'] = "L'email est requis";
            }
            if (!$password) {
                $errors['password'] = "Le mot de passe est requis";
            }

            if (empty($errors)) {
                $user = $this->authModel->findByEmail($email);
                
                if ($user && $this->authModel->verifyPassword($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['user_name'] = $user['nom'] . ' ' . $user['prenom'];

                    $this->authModel->updateLastLogin($user['id']);
                    $this->setFlashMessage('success', 'Connexion réussie');
                    $this->redirect('/APP_SGRHBMKH/dashboard');
                } else {
                    $errors['auth'] = 'Email ou mot de passe incorrect';
                }
            }

            $this->render('auth/login', [
                'messages' => $this->getFlashMessages(),
                'errors' => $errors,
                'email' => $email,
                'csrf_token' => $this->generateCSRFToken()
            ]);
        } else {
            $this->redirect('/APP_SGRHBMKH/auth/login');
        }
    }

    public function showProfile() {
        $this->requireLogin();
        
        $user = $this->authModel->findById($_SESSION['user_id']);
        unset($user['password']);
        
        $this->render('auth/profile', [
            'user' => $user,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    public function updateProfile() {
        $this->requireLogin();
        
        if ($this->isPost()) {
            $this->validateCSRF();

            $data = [
                'nom' => htmlspecialchars($_POST['nom'] ?? ''),
                'prenom' => htmlspecialchars($_POST['prenom'] ?? ''),
                'telephone' => htmlspecialchars($_POST['telephone'] ?? ''),
                'adresse' => htmlspecialchars($_POST['adresse'] ?? '')
            ];

            $password = $_POST['password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';

            $errors = $this->validateProfileUpdate($data, $password, $newPassword);

            if (empty($errors)) {
                if ($newPassword) {
                    $data['password'] = $newPassword;
                }

                if ($this->authModel->updateProfile($_SESSION['user_id'], $data)) {
                    $_SESSION['user_name'] = $data['nom'] . ' ' . $data['prenom'];
                    $this->setFlashMessage('success', 'Profil mis à jour avec succès');
                    $this->redirect('/APP_SGRHBMKH/auth/profile');
                } else {
                    $this->setFlashMessage('error', 'Erreur lors de la mise à jour du profil');
                }
            }

            $this->render('auth/profile', [
                'errors' => $errors,
                'user' => array_merge($this->authModel->findById($_SESSION['user_id']), $data),
                'csrf_token' => $this->generateCSRFToken()
            ]);
        }
    }

    public function logout() {
        if ($this->isPost()) {
            $this->validateCSRF();
            
            // Clear all session data
            $_SESSION = array();
            
            // Destroy the session cookie
            if (isset($_COOKIE[session_name()])) {
                setcookie(session_name(), '', time() - 3600, '/');
            }
            
            // Destroy the session
            session_destroy();
            
            $this->setFlashMessage('success', 'Déconnexion réussie');
            $this->redirect('/APP_SGRHBMKH/auth/login');
        } else {
            $this->redirect('/APP_SGRHBMKH/dashboard');
        }
    }

    public function forgotPassword() {
        if ($this->isPost()) {
            $this->validateCSRF();
            
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
            
            if ($email) {
                $token = $this->authModel->createPasswordResetToken($email);
                if ($token) {
                    // TODO: Send email with reset link
                    $this->setFlashMessage('success', 'Instructions de réinitialisation envoyées par email');
                }
            }
            
            $this->redirect('/APP_SGRHBMKH/auth/login');
        }
        
        $this->render('auth/forgot-password', [
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    public function resetPassword() {
        $token = $_GET['token'] ?? '';
        
        if ($this->isPost()) {
            $this->validateCSRF();
            
            $password = $_POST['password'] ?? '';
            $passwordConfirm = $_POST['password_confirm'] ?? '';
            
            $errors = [];
            if (!$password) {
                $errors['password'] = 'Le mot de passe est requis';
            } elseif (strlen($password) < 8) {
                $errors['password'] = 'Le mot de passe doit contenir au moins 8 caractères';
            } elseif ($password !== $passwordConfirm) {
                $errors['password_confirm'] = 'Les mots de passe ne correspondent pas';
            }
            
            if (empty($errors)) {
                if ($this->authModel->resetPassword($token, $password)) {
                    $this->setFlashMessage('success', 'Mot de passe réinitialisé avec succès');
                    $this->redirect('/APP_SGRHBMKH/auth/login');
                } else {
                    $this->setFlashMessage('error', 'Lien de réinitialisation invalide ou expiré');
                }
            }
            
            $this->render('auth/reset-password', [
                'errors' => $errors,
                'token' => $token,
                'csrf_token' => $this->generateCSRFToken()
            ]);
        } else {
            if (!$token || !$this->authModel->verifyResetToken($token)) {
                $this->setFlashMessage('error', 'Lien de réinitialisation invalide ou expiré');
                $this->redirect('/APP_SGRHBMKH/auth/login');
            }
            
            $this->render('auth/reset-password', [
                'token' => $token,
                'csrf_token' => $this->generateCSRFToken()
            ]);
        }
    }

    public function checkRole($role) {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        return $_SESSION['user_role'] === $role;
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    private function validateRegistration($data) {
        $errors = [];

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "L'email n'est pas valide";
        } elseif ($this->authModel->findByEmail($data['email'])) {
            $errors['email'] = "Cet email est déjà utilisé";
        }

        if (empty($data['password'])) {
            $errors['password'] = "Le mot de passe est requis";
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères";
        } elseif ($data['password'] !== $data['password_confirm']) {
            $errors['password_confirm'] = "Les mots de passe ne correspondent pas";
        }

        if (empty($data['nom'])) {
            $errors['nom'] = "Le nom est requis";
        }

        if (empty($data['prenom'])) {
            $errors['prenom'] = "Le prénom est requis";
        }

        return $errors;
    }

    private function validateProfileUpdate($data, $currentPassword, $newPassword) {
        $errors = [];

        if (empty($data['nom'])) {
            $errors['nom'] = "Le nom est requis";
        }

        if (empty($data['prenom'])) {
            $errors['prenom'] = "Le prénom est requis";
        }

        if ($newPassword) {
            if (empty($currentPassword)) {
                $errors['current_password'] = "Le mot de passe actuel est requis";
            } else {
                $user = $this->authModel->findById($_SESSION['user_id']);
                if (!$this->authModel->verifyPassword($currentPassword, $user['password'])) {
                    $errors['current_password'] = "Le mot de passe actuel est incorrect";
                }
            }

            if (strlen($newPassword) < 8) {
                $errors['new_password'] = "Le nouveau mot de passe doit contenir au moins 8 caractères";
            }
        }

        return $errors;
    }

    private function requireLogin() {
        if (!$this->isLoggedIn()) {
            $this->setFlashMessage('error', 'Veuillez vous connecter pour accéder à cette page');
            $this->redirect('/APP_SGRHBMKH/auth/login');
        }
    }
}
