<?php
class UserController extends Controller {
    private $userModel;

    public function __construct() {
        parent::__construct();
        $this->userModel = new Auth();
        $this->requireAuth();
        
        // Only admin can access user management
        if (!$this->checkRole('admin')) {
            $this->setFlashMessage('error', 'Accès non autorisé');
            $this->redirect('/APP_SGRHBMKH/dashboard');
        }
    }

    public function index() {
        $users = $this->userModel->getAllUsers();
        
        $this->render('users/index', [
            'users' => $users,
            'csrf_token' => $this->generateCSRFToken()
        ]);
    }

    public function create() {
        if ($this->isPost()) {
            $this->validateCSRF();

            $data = [
                'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                'password' => $_POST['password'] ?? '',
                'password_confirm' => $_POST['password_confirm'] ?? '',
                'nom' => htmlspecialchars($_POST['nom'] ?? ''),
                'prenom' => htmlspecialchars($_POST['prenom'] ?? ''),
                'telephone' => htmlspecialchars($_POST['telephone'] ?? ''),
                'adresse' => htmlspecialchars($_POST['adresse'] ?? ''),
                'role' => $_POST['role'] ?? 'user',
                'status' => $_POST['status'] ?? 'active'
            ];

            $errors = $this->validateUser($data);

            if (empty($errors)) {
                unset($data['password_confirm']);
                
                if ($this->userModel->createUser($data)) {
                    $this->setFlashMessage('success', 'Utilisateur créé avec succès');
                    $this->redirect('/APP_SGRHBMKH/users');
                } else {
                    $this->setFlashMessage('error', "Une erreur s'est produite lors de la création");
                }
            }

            $this->render('users/create', [
                'errors' => $errors,
                'data' => $data,
                'csrf_token' => $this->generateCSRFToken()
            ]);
        } else {
            $this->render('users/create', [
                'csrf_token' => $this->generateCSRFToken()
            ]);
        }
    }

    public function edit($id) {
        $user = $this->userModel->findById($id);
        
        if (!$user) {
            $this->setFlashMessage('error', 'Utilisateur non trouvé');
            $this->redirect('/APP_SGRHBMKH/users');
        }

        if ($this->isPost()) {
            $this->validateCSRF();

            $data = [
                'email' => filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL),
                'nom' => htmlspecialchars($_POST['nom'] ?? ''),
                'prenom' => htmlspecialchars($_POST['prenom'] ?? ''),
                'telephone' => htmlspecialchars($_POST['telephone'] ?? ''),
                'adresse' => htmlspecialchars($_POST['adresse'] ?? ''),
                'role' => $_POST['role'] ?? $user['role'],
                'status' => $_POST['status'] ?? $user['status']
            ];

            $password = $_POST['password'] ?? '';
            if ($password) {
                $data['password'] = $password;
            }

            $errors = $this->validateUserEdit($data, $id);

            if (empty($errors)) {
                if ($this->userModel->updateUser($id, $data)) {
                    $this->setFlashMessage('success', 'Utilisateur mis à jour avec succès');
                    $this->redirect('/APP_SGRHBMKH/users');
                } else {
                    $this->setFlashMessage('error', 'Erreur lors de la mise à jour');
                }
            }

            $this->render('users/edit', [
                'errors' => $errors,
                'user' => array_merge($user, $data),
                'csrf_token' => $this->generateCSRFToken()
            ]);
        } else {
            $this->render('users/edit', [
                'user' => $user,
                'csrf_token' => $this->generateCSRFToken()
            ]);
        }
    }

    public function toggleStatus($id) {
        if ($this->isPost()) {
            $this->validateCSRF();
            
            $user = $this->userModel->findById($id);
            if (!$user) {
                $this->setFlashMessage('error', 'Utilisateur non trouvé');
                $this->redirect('/APP_SGRHBMKH/users');
            }

            // Cannot deactivate own account
            if ($user['id'] === $_SESSION['user_id']) {
                $this->setFlashMessage('error', 'Vous ne pouvez pas désactiver votre propre compte');
                $this->redirect('/APP_SGRHBMKH/users');
            }

            $newStatus = $user['status'] === 'active' ? 'inactive' : 'active';
            
            if ($this->userModel->updateUser($id, ['status' => $newStatus])) {
                $this->setFlashMessage('success', 'Statut mis à jour avec succès');
            } else {
                $this->setFlashMessage('error', 'Erreur lors de la mise à jour du statut');
            }
        }
        
        $this->redirect('/APP_SGRHBMKH/users');
    }

    private function validateUser($data) {
        $errors = [];

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "L'email n'est pas valide";
        } elseif ($this->userModel->findByEmail($data['email'])) {
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

        if (!in_array($data['role'], ['admin', 'user'])) {
            $errors['role'] = "Le rôle n'est pas valide";
        }

        if (!in_array($data['status'], ['active', 'inactive'])) {
            $errors['status'] = "Le statut n'est pas valide";
        }

        return $errors;
    }

    private function validateUserEdit($data, $userId) {
        $errors = [];

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "L'email n'est pas valide";
        } else {
            $existingUser = $this->userModel->findByEmail($data['email']);
            if ($existingUser && $existingUser['id'] != $userId) {
                $errors['email'] = "Cet email est déjà utilisé";
            }
        }

        if (isset($data['password']) && strlen($data['password']) > 0 && strlen($data['password']) < 8) {
            $errors['password'] = "Le mot de passe doit contenir au moins 8 caractères";
        }

        if (empty($data['nom'])) {
            $errors['nom'] = "Le nom est requis";
        }

        if (empty($data['prenom'])) {
            $errors['prenom'] = "Le prénom est requis";
        }

        if (!in_array($data['role'], ['admin', 'user'])) {
            $errors['role'] = "Le rôle n'est pas valide";
        }

        if (!in_array($data['status'], ['active', 'inactive'])) {
            $errors['status'] = "Le statut n'est pas valide";
        }

        return $errors;
    }

    private function checkRole($role) {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
    }
}
