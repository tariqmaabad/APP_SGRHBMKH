<?php
class FormationSanitaireController extends Controller {
    private $formationModel;
    private $provinceModel;
    private $categorieModel;

    public function __construct() {
        parent::__construct();
        require_once __DIR__ . '/../models/FormationSanitaire.php';
        require_once __DIR__ . '/../models/Province.php';
        require_once __DIR__ . '/../models/CategorieEtablissement.php';
        
        $this->formationModel = new FormationSanitaire();
        $this->provinceModel = new Province();
        $this->categorieModel = new CategorieEtablissement();
    }

    public function index() {
        $formations = $this->formationModel->getAllWithDetails();
        $categories = $this->categorieModel->findAllSorted();
        $this->render('formations/index', [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'nom_formation' => trim($_POST['nom_formation']),
                'type_formation' => trim($_POST['type_formation'] ?? ''),
                'province_id' => (int)$_POST['province_id'],
                'categorie_id' => (int)$_POST['categorie_id'],
                'milieu' => $_POST['milieu']
            ];

            $errors = $this->formationModel->validate($data);
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $this->setFlashMessage('error', $error);
                }
                $this->redirect('/APP_SGRHBMKH/formations/create');
                return;
            }

            if ($this->formationModel->create($data)) {
                $this->setFlashMessage('success', 'Formation sanitaire créée avec succès');
                $this->redirect('/APP_SGRHBMKH/formations');
            } else {
                $this->setFlashMessage('error', 'Erreur lors de la création de la formation sanitaire');
                $this->redirect('/APP_SGRHBMKH/formations/create');
            }
        }

        $provinces = $this->provinceModel->findAllSorted();
        $categories = $this->categorieModel->findAllSorted();
        error_log("Categories data: " . print_r($categories, true));
        $this->render('formations/create', [
            'provinces' => $provinces,
            'categories_etablissements' => $categories,
            'csrf_token' => $this->generateCSRFToken(),
            'messages' => $this->getFlashMessages(),
            'data' => $_POST ?? [] // Preserve form data on validation errors
        ]);
    }

    public function edit($id) {
        $formation = $this->formationModel->findById($id);
        
        if (!$formation) {
            $this->setFlashMessage('error', 'Formation sanitaire non trouvée');
            $this->redirect('/APP_SGRHBMKH/formations');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $this->validateCSRF();
            $data = [
                'nom_formation' => trim($_POST['nom_formation']),
                'type_formation' => trim($_POST['type_formation'] ?? ''),
                'province_id' => (int)$_POST['province_id'],
                'categorie_id' => (int)$_POST['categorie_id'],
                'milieu' => $_POST['milieu']
            ];

            $errors = $this->formationModel->validate($data);
            if (!empty($errors)) {
                foreach ($errors as $error) {
                    $this->setFlashMessage('error', $error);
                }
                $this->redirect("/APP_SGRHBMKH/formations/edit/$id");
                return;
            }

            if ($this->formationModel->update($id, $data)) {
                $this->setFlashMessage('success', 'Formation sanitaire mise à jour avec succès');
                $this->redirect('/APP_SGRHBMKH/formations');
            } else {
                $this->setFlashMessage('error', 'Erreur lors de la mise à jour de la formation sanitaire');
                $this->redirect("/APP_SGRHBMKH/formations/edit/$id");
            }
        }

        $provinces = $this->provinceModel->findAllSorted();
        $categories = $this->categorieModel->findAllSorted();
        $this->render('formations/edit', [
            'formation' => $formation,
            'provinces' => $provinces,
            'categories_etablissements' => $categories,
            'csrf_token' => $this->generateCSRFToken(),
            'messages' => $this->getFlashMessages()
        ]);
    }

    public function delete($id) {
        $this->validateCSRF();
        $formation = $this->formationModel->findById($id);
        
        if (!$formation) {
            $this->setFlashMessage('error', 'Formation sanitaire non trouvée');
            $this->redirect('/APP_SGRHBMKH/formations');
        }

        if ($this->formationModel->delete($id)) {
            $this->setFlashMessage('success', 'Formation sanitaire supprimée avec succès');
        } else {
            $this->setFlashMessage('error', 'Erreur lors de la suppression de la formation sanitaire');
        }

        $this->redirect('/formations');
    }

    public function show($id) {
        $formation = $this->formationModel->findDetailById($id);
        
        if (!$formation) {
            $this->setFlashMessage('error', 'Formation sanitaire non trouvée');
            $this->redirect('/APP_SGRHBMKH/formations');
        }

        $stats = $this->formationModel->getPersonnelStats($id);
        $personnel = $this->formationModel->getPersonnelList($id);
        
        $this->render('formations/view', [
            'formation' => $formation,
            'stats' => $stats,
            'personnel' => $personnel
        ]);
    }

    public function byProvince($province_id) {
        $formations = $this->formationModel->findByProvince($province_id);
        $this->json($formations);
    }

    public function byCategorie($categorie_id) {
        $formations = $this->formationModel->findByCategorie($categorie_id);
        $this->json($formations);
    }
}
