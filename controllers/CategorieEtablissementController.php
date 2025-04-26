<?php
class CategorieEtablissementController extends Controller {
    private $categorieModel;
    private $formationSanitaireModel;

    public function __construct() {
        parent::__construct();
        require_once __DIR__ . '/../models/CategorieEtablissement.php';
        require_once __DIR__ . '/../models/FormationSanitaire.php';
        
        $this->categorieModel = new CategorieEtablissement();
        $this->formationSanitaireModel = new FormationSanitaire();
    }

    public function index() {
        $categories = $this->categorieModel->findWithStats();
        $this->render('categories/index', ['categories' => $categories]);
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $data = [
                'nom_categorie' => trim($_POST['nom_categorie']),
                'description' => trim($_POST['description'] ?? '')
            ];

            if (empty($data['nom_categorie'])) {
                $this->setFlashMessage('error', 'Le nom de la catégorie est obligatoire');
                $this->redirect('/APP_SGRHBMKH/categories/create');
            }

            if ($this->categorieModel->create($data)) {
                $this->setFlashMessage('success', 'Catégorie créée avec succès');
                $this->redirect('/APP_SGRHBMKH/categories');
            } else {
                $this->setFlashMessage('error', 'Erreur lors de la création de la catégorie');
                $this->redirect('/APP_SGRHBMKH/categories/create');
            }
        }

        $this->render('categories/create', [
            'csrf_token' => $this->generateCSRFToken(),
            'messages' => $this->getFlashMessages()
        ]);
    }

    public function edit($id) {
        $categorie = $this->categorieModel->findById($id);
        
        if (!$categorie) {
            $this->setFlashMessage('error', 'Catégorie non trouvée');
            $this->redirect('/APP_SGRHBMKH/categories');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $data = [
                'nom_categorie' => trim($_POST['nom_categorie']),
                'description' => trim($_POST['description'] ?? '')
            ];

            if (empty($data['nom_categorie'])) {
                $this->setFlashMessage('error', 'Le nom de la catégorie est obligatoire');
                $this->redirect("/APP_SGRHBMKH/categories/edit/$id");
            }

            if ($this->categorieModel->update($id, $data)) {
                $this->setFlashMessage('success', 'Catégorie mise à jour avec succès');
                $this->redirect('/APP_SGRHBMKH/categories');
            } else {
                $this->setFlashMessage('error', 'Erreur lors de la mise à jour de la catégorie');
                $this->redirect("/APP_SGRHBMKH/categories/edit/$id");
            }
        }

        $this->render('categories/edit', [
            'categorie' => $categorie,
            'csrf_token' => $this->generateCSRFToken(),
            'messages' => $this->getFlashMessages()
        ]);
    }

    public function delete($id) {
        $categorie = $this->categorieModel->findById($id);
        
        if (!$categorie) {
            $this->setFlashMessage('error', 'Catégorie non trouvée');
            $this->redirect('/APP_SGRHBMKH/categories');
        }

        // Check if category has related formations
        $formations = $this->formationSanitaireModel->findByCategorie($id);
        if (!empty($formations)) {
            $this->setFlashMessage('error', 'Impossible de supprimer cette catégorie car elle est utilisée par des formations sanitaires');
            $this->redirect('/APP_SGRHBMKH/categories');
            return;
        }

        if ($this->categorieModel->delete($id)) {
            $this->setFlashMessage('success', 'Catégorie supprimée avec succès');
        } else {
            $this->setFlashMessage('error', 'Erreur lors de la suppression de la catégorie');
        }

        $this->redirect('/APP_SGRHBMKH/categories');
    }

    public function show($id) {
        $categorie = $this->categorieModel->findById($id);
        
        if (!$categorie) {
            $this->setFlashMessage('error', 'Catégorie non trouvée');
            $this->redirect('/APP_SGRHBMKH/categories');
        }

        $formations = $this->formationSanitaireModel->findByCategorie($id);
        
        $this->render('categories/view', [
            'categorie' => $categorie,
            'formations' => $formations
        ]);
    }
}
