<?php
class MouvementPersonnelController extends Controller {
    private $MouvementPersonnel;
    private $Personnel;
    private $FormationSanitaire;

    private function validateMouvementData($data) {
        error_log("Validating mouvement data: " . json_encode($data));

        // Vérifier les champs obligatoires
        if (empty($data['personnel_id']) || 
            empty($data['type_mouvement']) || 
            empty($data['date_mouvement'])) {
            error_log("Missing required fields");
            return false;
        }

        // Vérifier la validité de la date
        if (!strtotime($data['date_mouvement'])) {
            error_log("Invalid date format: " . $data['date_mouvement']);
            return false;
        }

        // Vérifier les champs spécifiques pour mutation/mise à disposition
        if (in_array($data['type_mouvement'], ['MUTATION', 'MISE_A_DISPOSITION'])) {
            if (empty($data['formation_sanitaire_destination_id'])) {
                error_log("Missing destination for mutation/mise à disposition");
                return false;
            }
        }

        error_log("Data validation successful");
        return true;
    }

    public function __construct() {
        try {
            parent::__construct();
            require_once __DIR__ . '/../models/MouvementPersonnel.php';
            require_once __DIR__ . '/../models/Personnel.php';
            require_once __DIR__ . '/../models/FormationSanitaire.php';
            
            $this->MouvementPersonnel = new MouvementPersonnel();
            $this->Personnel = new Personnel();
            $this->FormationSanitaire = new FormationSanitaire();
        } catch (Exception $e) {
            error_log("Error in MouvementPersonnelController constructor: " . $e->getMessage());
            throw $e;
        }
    }

    public function index() {
        $this->requireAuth();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $type = isset($_GET['type']) ? $_GET['type'] : '';
        $date_debut = isset($_GET['date_debut']) ? $_GET['date_debut'] : '';
        $date_fin = isset($_GET['date_fin']) ? $_GET['date_fin'] : '';
        
        try {
            $conditions = [];
            if (!empty($type)) {
                $conditions['type_mouvement'] = $type;
            }
            if (!empty($date_debut)) {
                $conditions['date_debut'] = $date_debut;
            }
            if (!empty($date_fin)) {
                $conditions['date_fin'] = $date_fin;
            }

            $total = $this->MouvementPersonnel->count($type, $date_debut, $date_fin);
            $offset = ($page - 1) * $perPage;
            $total_pages = ceil($total / $perPage);

            $mouvements = $this->MouvementPersonnel->findWithDetails(
                $conditions,
                "LIMIT $perPage OFFSET $offset"
            );
            
            $stats = $this->MouvementPersonnel->getStats($date_debut, $date_fin);
            $types = $this->MouvementPersonnel->getTypesMouvement();

            $this->render('mouvements/index', [
                'mouvements' => $mouvements,
                'types' => $types,
                'stats' => $stats,
                'canCreate' => $this->canCreate(),
                'canEdit' => $this->canEdit(),
                'canDelete' => $this->canDelete(),
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total_pages' => $total_pages,
                    'total' => $total,
                    'has_previous' => $page > 1,
                    'has_next' => $page < $total_pages,
                    'previous_page' => $page - 1,
                    'next_page' => $page + 1
                ],
                'filters' => [
                    'type' => $type,
                    'date_debut' => $date_debut,
                    'date_fin' => $date_fin
                ]
            ]);
        } catch (Exception $e) {
            error_log("Error in mouvement index: " . $e->getMessage());
            $this->setFlashMessage('error', 'Une erreur est survenue lors du chargement des mouvements.');
            $this->redirect('/APP_SGRHBMKH/mouvements');
        }
    }

    public function create() {
        $this->enforceCreatePermission();
        
        error_log("MouvementPersonnelController::create() called with method: " . $_SERVER['REQUEST_METHOD']);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            error_log("Processing POST request with data: " . json_encode($_POST));
            $this->validateCSRF();
            error_log("POST data received: " . json_encode($_POST));
            
            $data = [
                'personnel_id' => (int)$_POST['personnel_id'],
                'type_mouvement' => $_POST['type_mouvement'],
                'date_mouvement' => $_POST['date_mouvement'],
                'commentaire' => trim($_POST['commentaire'] ?? '')
            ];

            // Validations
            if (empty($data['personnel_id'])) {
                $this->setFlashMessage('error', 'Le personnel est obligatoire');
                $this->redirect('/APP_SGRHBMKH/mouvements/create');
                return;
            }

            if (empty($data['type_mouvement'])) {
                $this->setFlashMessage('error', 'Le type de mouvement est obligatoire');
                $this->redirect('/APP_SGRHBMKH/mouvements/create');
                return;
            }

            if (empty($data['date_mouvement'])) {
                $this->setFlashMessage('error', 'La date du mouvement est obligatoire');
                $this->redirect('/APP_SGRHBMKH/mouvements/create');
                return;
            }

            // Pour les mutations et mises à disposition
            if (in_array($data['type_mouvement'], ['MUTATION', 'MISE_A_DISPOSITION'])) {
                $data['formation_sanitaire_origine_id'] = (int)$_POST['formation_sanitaire_origine_id'];
                $data['formation_sanitaire_destination_id'] = (int)$_POST['formation_sanitaire_destination_id'];

                if (empty($data['formation_sanitaire_destination_id'])) {
                    $this->setFlashMessage('error', 'La formation sanitaire de destination est obligatoire');
                    $this->redirect('/APP_SGRHBMKH/mouvements/create');
                    return;
                }
            }

            try {
                error_log("Attempting to create mouvement with data: " . json_encode($data));
                
                // Validate required fields again as a safeguard
                if (!$this->validateMouvementData($data)) {
                    $this->setFlashMessage('error', 'Données invalides. Veuillez vérifier tous les champs obligatoires.');
                    $this->redirect('/APP_SGRHBMKH/mouvements/create');
                    return;
                }

                $result = $this->MouvementPersonnel->createMouvement($data);
                error_log("Create mouvement result: " . ($result ? "success" : "failure"));
                if ($result) {
                    $this->setFlashMessage('success', 'Mouvement enregistré avec succès');
                    $this->redirect('/APP_SGRHBMKH/mouvements');
                } else {
                    throw new Exception('Erreur lors de l\'enregistrement du mouvement');
                }
            } catch (Exception $e) {
                $this->setFlashMessage('error', 'Erreur lors de l\'enregistrement du mouvement: ' . $e->getMessage());
                $this->redirect('/APP_SGRHBMKH/mouvements/create');
            }
            return;
        }

        $personnel = $this->Personnel->findAllWithDetails([], 'nom ASC, prenom ASC');
        $formations = $this->FormationSanitaire->getAllWithDetails();
        $types = $this->MouvementPersonnel->getTypesMouvement();

        $this->render('mouvements/create', [
            'personnel' => $personnel,
            'formations' => $formations,
            'types' => $types,
            'csrf_token' => $this->generateCSRFToken(),
            'data' => $_POST ?? [] // Preserve form data on validation errors
        ]);
    }

    public function show($id) {
        $mouvement = $this->MouvementPersonnel->findDetailById($id);
        
        if (!$mouvement) {
            $this->setFlashMessage('error', 'Mouvement non trouvé');
            $this->redirect('/APP_SGRHBMKH/mouvements');
            return;
        }

        $types = $this->MouvementPersonnel->getTypesMouvement();
        
        $this->render('mouvements/view', [
            'mouvement' => $mouvement,
            'types' => $types,
            'canEdit' => $this->canEdit(),
            'canDelete' => $this->canDelete()
        ]);
    }

    public function byType($type) {
        if (!array_key_exists($type, $this->MouvementPersonnel->getTypesMouvement())) {
            $this->setFlashMessage('error', 'Type de mouvement invalide');
            $this->redirect('/APP_SGRHBMKH/mouvements');
            return;
        }

        $mouvements = $this->MouvementPersonnel->findByType($type);
        $types = $this->MouvementPersonnel->getTypesMouvement();
        
        $this->render('mouvements/by_type', [
            'mouvements' => $mouvements,
            'type_actuel' => $type,
            'types' => $types,
            'canCreate' => $this->canCreate(),
            'canEdit' => $this->canEdit(),
            'canDelete' => $this->canDelete()
        ]);
    }

    public function stats() {
        $stats = $this->MouvementPersonnel->getStats();
        $types = $this->MouvementPersonnel->getTypesMouvement();
        
        $this->render('mouvements/stats', [
            'stats' => $stats,
            'types' => $types
        ]);
    }

    public function delete($id) {
        $this->enforceDeletePermission();
        $mouvement = $this->MouvementPersonnel->findById($id);
        
        if (!$mouvement) {
            $this->setFlashMessage('error', 'Mouvement non trouvé');
            $this->redirect('/APP_SGRHBMKH/mouvements');
            return;
        }

        try {
            if ($this->MouvementPersonnel->delete($id)) {
                $this->setFlashMessage('success', 'Mouvement supprimé avec succès');
            } else {
                throw new Exception('Erreur lors de la suppression du mouvement');
            }
        } catch (Exception $e) {
            $this->setFlashMessage('error', 'Erreur lors de la suppression du mouvement: ' . $e->getMessage());
        }

        $this->redirect('/APP_SGRHBMKH/mouvements');
    }
}
