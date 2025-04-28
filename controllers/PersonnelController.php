<?php
require_once __DIR__ . '/../controllers/Controller.php';
require_once __DIR__ . '/../models/Personnel.php';
require_once __DIR__ . '/../models/Specialite.php';

class PersonnelController extends Controller {
    private $personnelModel;
    
    public function __construct() {
        parent::__construct();
        $this->personnelModel = new Personnel();
    }

    // Afficher la liste du personnel
    public function index() {
        $this->requireAuth();
        
        // Add permission flags for view
        $canCreate = $this->canCreate();
        $canEdit = $this->canEdit();
        $canDelete = $this->canDelete();
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';
        $corps_id = isset($_GET['corps_id']) ? (int)$_GET['corps_id'] : null;
        $grade_id = isset($_GET['grade_id']) ? (int)$_GET['grade_id'] : null;
        $formation_id = isset($_GET['formation_id']) ? (int)$_GET['formation_id'] : null;
        $sort = isset($_GET['sort']) ? $_GET['sort'] : 'nom';
        $order = isset($_GET['order']) ? $_GET['order'] : 'ASC';
        
        try {
            $conditions = [];
            if ($corps_id) $conditions['corps_id'] = $corps_id;
            if ($grade_id) $conditions['grade_id'] = $grade_id;
            if ($formation_id) $conditions['formation_sanitaire_id'] = $formation_id;

            // Get dropdown data
            require_once __DIR__ . '/../models/Corps.php';
            require_once __DIR__ . '/../models/Grade.php';
            require_once __DIR__ . '/../models/FormationSanitaire.php';
            
            $corpsModel = new Corps();
            $gradeModel = new Grade();
            $formationModel = new FormationSanitaire();
            
            $corps_list = $corpsModel->findAllSorted();
            $grades_list = $gradeModel->findAllSorted();
            $formations_list = $formationModel->getAllWithDetails();

            $offset = ($page - 1) * $perPage;
            
            if (!empty($searchTerm)) {
                try {
                    error_log("Processing search request with term: " . $searchTerm);
                    $results = $this->personnelModel->search($searchTerm);
                    
                    // Apply filters to search results
                    if (!empty($conditions)) {
                        error_log("Applying filters to search results: " . print_r($conditions, true));
                        $results = array_filter($results, function($item) use ($conditions) {
                            foreach ($conditions as $key => $value) {
                                if (!isset($item[$key]) || $item[$key] != $value) return false;
                            }
                            return true;
                        });
                        $results = array_values($results); // Reset array keys
                    }
                    
                    $total = count($results);
                    error_log("Total results before pagination: " . $total);
                    
                    // Manual pagination for search results
                    $results = array_slice($results, $offset, $perPage);
                    error_log("Results after pagination: " . count($results));
                    
                } catch (Exception $e) {
                    error_log("Search error: " . $e->getMessage());
                    throw new Exception("Une erreur est survenue lors de la recherche");
                }
            } else {
                error_log("Performing regular listing with conditions: " . print_r($conditions, true));
                $total = $this->personnelModel->count($conditions);
                $results = $this->personnelModel->findAllWithDetails(
                    $conditions,
                    "$sort $order",
                    "LIMIT $perPage OFFSET $offset"
                );
            }

            $total_pages = ceil($total / $perPage);

            $this->render('personnel/index', [
                'personnel' => $results,
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
                'searchTerm' => $searchTerm,
                'corps_id' => $corps_id,
                'grade_id' => $grade_id,
                'formation_id' => $formation_id,
                'corps_list' => $corps_list,
                'grades_list' => $grades_list,
                'formations_list' => $formations_list,
                'sort' => $sort,
                'order' => $order,
                'messages' => $this->getFlashMessages(),
                'canCreate' => $canCreate,
                'canEdit' => $canEdit,
                'canDelete' => $canDelete
            ]);
            
        } catch (Exception $e) {
            // Log the error
            error_log("Personnel index error: " . $e->getMessage());
            
            $this->setFlashMessage('error', 'Une erreur est survenue lors du chargement de la liste du personnel.');
            $this->render('personnel/index', [
                'personnel' => [],
                'pagination' => null,
                'searchTerm' => $searchTerm,
                'messages' => $this->getFlashMessages()
            ]);
        }
    }

    // Afficher le formulaire de création
    public function create() {
        $this->enforceCreatePermission();
        
        // Get required data for dropdown lists
        require_once __DIR__ . '/../models/Corps.php';
        require_once __DIR__ . '/../models/Grade.php';
        require_once __DIR__ . '/../models/FormationSanitaire.php';
        
        $corpsModel = new Corps();
        $gradeModel = new Grade();
        $specialiteModel = new Specialite();
        $formationModel = new FormationSanitaire();

        $corps = $corpsModel->findAllSorted();
        $grades = $gradeModel->findAllSorted();
        $specialites = $specialiteModel->findAllSorted();
        $formations_sanitaires = $formationModel->getAllWithDetails();
        
        $this->render('personnel/create', [
            'corps' => $corps,
            'grades' => $grades,
            'specialites' => $specialites,
            'formations_sanitaires' => $formations_sanitaires,
            'csrf_token' => $this->generateCSRFToken(),
            'messages' => $this->getFlashMessages()
        ]);
    }

    // Traiter la création d'un nouveau personnel
    public function store() {
        try {
            $this->requireAuth();
            $this->validateCSRF();

            error_log("Starting personnel store process");
            $data = $this->sanitize($this->getPostData());
            error_log("POST data received: " . print_r($data, true));
            
            // Remove csrf_token from data before database operation
            unset($data['csrf_token']);
            error_log("Data after csrf removal: " . print_r($data, true));
            
            $errors = $this->personnelModel->validate($data);
            if (!empty($errors)) {
                error_log("Validation errors found: " . print_r($errors, true));
            }

            if (empty($errors)) {
                try {
                    if ($this->personnelModel->create($data)) {
                        error_log("Personnel created successfully");
                        $this->setFlashMessage('success', 'Personnel ajouté avec succès');
                        $this->redirect('/APP_SGRHBMKH/personnel');
                        return;
                    } else {
                        error_log("Error creating personnel");
                        throw new Exception('Erreur lors de l\'ajout du personnel');
                    }
                } catch (Exception $e) {
                    error_log("Database error: " . $e->getMessage());
                    $this->setFlashMessage('error', 'Erreur lors de l\'ajout du personnel: ' . $e->getMessage());
                }
            }

            // Get required data for dropdown lists
            require_once __DIR__ . '/../models/Corps.php';
            require_once __DIR__ . '/../models/Grade.php';
            require_once __DIR__ . '/../models/FormationSanitaire.php';
            
            $corpsModel = new Corps();
            $gradeModel = new Grade();
            $specialiteModel = new Specialite();
            $formationModel = new FormationSanitaire();

            $corps = $corpsModel->findAllSorted();
            $grades = $gradeModel->findAllSorted();
            $specialites = $specialiteModel->findAllSorted();
            $formations_sanitaires = $formationModel->getAllWithDetails();
            
            $this->render('personnel/create', [
                'errors' => $errors,
                'data' => $data,
                'corps' => $corps,
                'grades' => $grades,
                'specialites' => $specialites,
                'formations_sanitaires' => $formations_sanitaires,
                'csrf_token' => $this->generateCSRFToken(),
                'messages' => $this->getFlashMessages()
            ]);
            
        } catch (Exception $e) {
            error_log("Critical error in store method: " . $e->getMessage());
            $this->setFlashMessage('error', 'Une erreur est survenue lors de la création du personnel');
            $this->redirect('/APP_SGRHBMKH/personnel');
        }
    }

    // Afficher le formulaire de modification
    public function edit($id) {
        $this->enforceEditPermission();

        $personnel = $this->personnelModel->findByIdWithDetails($id);
        if (!$personnel) {
            $this->setFlashMessage('error', 'Personnel non trouvé');
            $this->redirect('/APP_SGRHBMKH/personnel');
        }

        // Get required data for dropdown lists
        require_once __DIR__ . '/../models/Corps.php';
        require_once __DIR__ . '/../models/Grade.php';
        require_once __DIR__ . '/../models/FormationSanitaire.php';
        
        $corpsModel = new Corps();
        $gradeModel = new Grade();
        $specialiteModel = new Specialite();
        $formationModel = new FormationSanitaire();

        $corps = $corpsModel->findAllSorted();
        $grades = $gradeModel->findAllSorted();
        $specialites = $specialiteModel->findAllSorted();
        $formations_sanitaires = $formationModel->getAllWithDetails();

        $this->render('personnel/edit', [
            'personnel' => $personnel,
            'corps' => $corps,
            'grades' => $grades,
            'specialites' => $specialites,
            'formations_sanitaires' => $formations_sanitaires,
            'csrf_token' => $this->generateCSRFToken(),
            'messages' => $this->getFlashMessages()
        ]);
    }

    // Traiter la modification d'un personnel
    public function update($id) {
        try {
            $this->requireAuth();
            $this->validateCSRF();

            // Verify personnel exists
            $personnel = $this->personnelModel->findById($id);
            if (!$personnel) {
                throw new Exception("Personnel non trouvé");
            }

            $data = $this->sanitize($this->getPostData());
            
            // Remove csrf_token from data before database operation
            unset($data['csrf_token']);
            
            $errors = $this->personnelModel->validate($data);

            if (!empty($errors)) {
                // Get required data for dropdown lists
                require_once __DIR__ . '/../models/Corps.php';
                require_once __DIR__ . '/../models/Grade.php';
                require_once __DIR__ . '/../models/Specialite.php';
                require_once __DIR__ . '/../models/FormationSanitaire.php';
                
                $corpsModel = new Corps();
                $gradeModel = new Grade();
                $specialiteModel = new Specialite();
                $formationModel = new FormationSanitaire();

                $corps = $corpsModel->findAllSorted();
                $grades = $gradeModel->findAllSorted();
                $specialites = $specialiteModel->findAllSorted();
                $formations_sanitaires = $formationModel->getAllWithDetails();

                $this->render('personnel/edit', [
                    'personnel' => array_merge(['id' => $id], $data),
                    'errors' => $errors,
                    'corps' => $corps,
                    'grades' => $grades,
                    'specialites' => $specialites,
                    'formations_sanitaires' => $formations_sanitaires,
                    'csrf_token' => $this->generateCSRFToken(),
                    'messages' => $this->getFlashMessages()
                ]);
                return;
            }

            // Update personnel
            if (!$this->personnelModel->update($id, $data)) {
                throw new Exception("Erreur lors de la mise à jour du personnel");
            }

            $this->setFlashMessage('success', 'Personnel mis à jour avec succès');
            $this->redirect('/APP_SGRHBMKH/personnel');

        } catch (Exception $e) {
            error_log("Error updating personnel $id: " . $e->getMessage());
            $this->setFlashMessage('error', $e->getMessage());
            $this->redirect('/APP_SGRHBMKH/personnel');
        }
    }

    // Afficher les détails d'un personnel
    public function show($id) {
        $this->requireAuth();

        $personnel = $this->personnelModel->findByIdWithDetails($id);
        if (!$personnel) {
            $this->setFlashMessage('error', 'Personnel non trouvé');
                $this->redirect('/APP_SGRHBMKH/personnel');
        }

        $mouvements = $this->personnelModel->getMouvements($id);
        
        // Get formations for the movement modal
        require_once __DIR__ . '/../models/FormationSanitaire.php';
        $formationModel = new FormationSanitaire();
        $formations_sanitaires = $formationModel->getAllWithDetails();

        $this->render('personnel/show', [
            'personnel' => $personnel,
            'mouvements' => $mouvements,
            'formations_sanitaires' => $formations_sanitaires,
            'csrf_token' => $this->generateCSRFToken(),
            'messages' => $this->getFlashMessages(),
            'canEdit' => $this->canEdit(),
            'canCreate' => $this->canCreate()
        ]);
    }

    // Supprimer un personnel
    public function delete($id) {
        $this->enforceDeletePermission();
        $this->validateCSRF();

        if ($this->personnelModel->delete($id)) {
            $this->setFlashMessage('success', 'Personnel supprimé avec succès');
        } else {
            $this->setFlashMessage('error', 'Erreur lors de la suppression du personnel');
        }

        $this->redirect('/APP_SGRHBMKH/personnel');
    }

    // Enregistrer un nouveau mouvement
    public function addMouvement() {
        $this->requireAuth();
        $this->validateCSRF();

        $data = $this->sanitize($this->getPostData());
        
        if ($this->personnelModel->addMouvement(
            $data['personnel_id'],
            $data['type_mouvement'],
            $data['date_mouvement'],
            $data['formation_sanitaire_origine_id'] ?? null,
            $data['formation_sanitaire_destination_id'] ?? null,
            $data['commentaire'] ?? ''
        )) {
            $this->setFlashMessage('success', 'Mouvement enregistré avec succès');
        } else {
            $this->setFlashMessage('error', 'Erreur lors de l\'enregistrement du mouvement');
        }

            $this->redirect('/APP_SGRHBMKH/personnel/show/' . $data['personnel_id']);
    }

    // API : Recherche de personnel (pour l'autocomplétion)
    public function search() {
        $this->requireAuth();
        
        $searchTerm = isset($_GET['term']) ? trim($_GET['term']) : '';
        if (empty($searchTerm)) {
            $this->json([]);
            return;
        }

        $results = $this->personnelModel->search($searchTerm);
        $formatted = array_map(function($item) {
            return [
                'id' => $item['id'],
                'label' => "{$item['nom']} {$item['prenom']} ({$item['ppr']})",
                'value' => $item['id']
            ];
        }, $results);

        $this->json($formatted);
    }
}
?>
