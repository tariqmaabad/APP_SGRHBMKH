<?php
class ProvinceController extends Controller {
    private $provinceModel;

    public function __construct() {
        parent::__construct();
        require_once __DIR__ . '/../models/Province.php';
        $this->provinceModel = new Province();
    }

   /* public function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/APP_SGRHBMKH/provinces');
        }
    }
*/
    public function index() {
        $this->requireAuth();
        $provinces = $this->provinceModel->findAllSorted();
        $this->render('provinces/index', [
            'provinces' => $provinces,
            'messages' => $this->getFlashMessages(),
            'canCreate' => $this->canCreate(),
            'canEdit' => $this->canEdit(),
            'canDelete' => $this->canDelete()
        ]);
    }

    public function show($id) {
        $this->requireAuth();
        
        $province = $this->provinceModel->findDetailById($id);
        if (!$province) {
            $this->setFlashMessage('error', 'Province non trouvée');
            $this->redirect('/APP_SGRHBMKH/provinces');
        }

        $this->render('provinces/show', [
            'province' => $province,
            'messages' => $this->getFlashMessages(),
            'canEdit' => $this->canEdit(),
            'canDelete' => $this->canDelete()
        ]);
    }

    public function create() {
        $this->enforceCreatePermission();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $data = [
                'nom_province' => trim($_POST['nom_province'])
            ];

            $errors = $this->provinceModel->validate($data);
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $this->setFlashMessage('error', $error);
                }
                $this->redirect('/APP_SGRHBMKH/provinces/create');
                return;
            }

            if ($this->provinceModel->create($data)) {
                $this->setFlashMessage('success', 'Province créée avec succès');
                $this->redirect('/APP_SGRHBMKH/provinces');
            } else {
                $this->setFlashMessage('error', 'Erreur lors de la création de la province');
                $this->redirect('/APP_SGRHBMKH/provinces/create');
            }
        }

        $this->render('provinces/create', [
            'csrf_token' => $this->generateCSRFToken(),
            'messages' => $this->getFlashMessages()
        ]);
    }

    public function edit($id) {
        $this->enforceEditPermission();

        $province = $this->provinceModel->findDetailById($id);
        if (!$province) {
            $this->setFlashMessage('error', 'Province non trouvée');
            $this->redirect('/APP_SGRHBMKH/provinces');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $data = [
                'nom_province' => trim($_POST['nom_province'])
            ];

            $errors = $this->provinceModel->validate($data);
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $this->setFlashMessage('error', $error);
                }
                $this->redirect("/APP_SGRHBMKH/provinces/edit/$id");
                return;
            }

            if ($this->provinceModel->update($id, $data)) {
                $this->setFlashMessage('success', 'Province mise à jour avec succès');
                $this->redirect('/APP_SGRHBMKH/provinces');
            } else {
                $this->setFlashMessage('error', 'Erreur lors de la mise à jour de la province');
                $this->redirect("/APP_SGRHBMKH/provinces/edit/$id");
            }
        }

        $this->render('provinces/edit', [
            'province' => $province,
            'csrf_token' => $this->generateCSRFToken(),
            'messages' => $this->getFlashMessages()
        ]);
    }

    public function delete($id) {
        $this->enforceDeletePermission();
        $this->validateCSRF();

        $province = $this->provinceModel->findDetailById($id);
        if (!$province) {
            $this->setFlashMessage('error', 'Province non trouvée');
            $this->redirect('/APP_SGRHBMKH/provinces');
        }

        if ($province['nombre_formations'] > 0) {
            $this->setFlashMessage('error', 'Impossible de supprimer la province car elle contient des formations sanitaires');
            $this->redirect('/APP_SGRHBMKH/provinces');
            return;
        }

        if ($this->provinceModel->delete($id)) {
            $this->setFlashMessage('success', 'Province supprimée avec succès');
        } else {
            $this->setFlashMessage('error', 'Erreur lors de la suppression de la province');
        }

        $this->redirect('/APP_SGRHBMKH/provinces');
    }
}
