<?php
class CorpsController extends Controller {
    private $corpsModel;
    private $gradeModel;

    public function __construct() {
        parent::__construct();
        require_once __DIR__ . '/../models/Corps.php';
        require_once __DIR__ . '/../models/Grade.php';
        
        $this->corpsModel = new Corps();
        $this->gradeModel = new Grade();
    }

    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $type = $_GET['type'] ?? null;
        $stats = $this->corpsModel->getStatsByType();
        
        try {
            $offset = ($page - 1) * $perPage;
            
            if ($type) {
                $total = $this->corpsModel->countByType($type);
                $corps = $this->corpsModel->findByType($type, "LIMIT $perPage OFFSET $offset");
            } else {
                $total = $this->corpsModel->count();
                $corps = $this->corpsModel->findWithGrades($perPage, $offset);
            }

            $total_pages = ceil($total / $perPage);
            $types = $this->corpsModel->getTypes();
            
            $this->render('corps/index', [
                'corps' => $corps,
                'stats' => $stats,
                'types' => $types,
                'current_type' => $type,
                'pagination' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total_pages' => $total_pages,
                    'total' => $total,
                    'has_previous' => $page > 1,
                    'has_next' => $page < $total_pages,
                    'previous_page' => $page - 1,
                    'next_page' => $page + 1
                ]
            ]);
        } catch (Exception $e) {
            error_log("Error in corps index: " . $e->getMessage());
            $this->setFlashMessage('error', 'Une erreur est survenue lors du chargement des corps.');
            $this->redirect('/corps');
        }
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $data = [
                'nom_corps' => trim($_POST['nom_corps']),
                'description' => trim($_POST['description'] ?? ''),
                'type_corps' => $_POST['type_corps']
            ];

            if (empty($data['nom_corps'])) {
                $this->setFlashMessage('error', 'Le nom du corps est obligatoire');
                $this->redirect('/corps/create');
                return;
            }

            if (empty($data['type_corps'])) {
                $this->setFlashMessage('error', 'Le type du corps est obligatoire');
                $this->redirect('/corps/create');
                return;
            }

            if ($this->corpsModel->create($data)) {
                $this->setFlashMessage('success', 'Corps créé avec succès');
                $this->redirect('/corps');
            } else {
                $this->setFlashMessage('error', 'Erreur lors de la création du corps');
                $this->redirect('/corps/create');
            }
        }

        $types = $this->corpsModel->getTypes();
        $this->render('corps/create', [
            'types' => $types,
            'csrf_token' => $this->generateCSRFToken(),
            'messages' => $this->getFlashMessages()
        ]);
    }

    public function edit($id) {
        $corps = $this->corpsModel->findById($id);
        
        if (!$corps) {
            $this->setFlashMessage('error', 'Corps non trouvé');
            $this->redirect('/corps');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            $data = [
                'nom_corps' => trim($_POST['nom_corps']),
                'description' => trim($_POST['description'] ?? ''),
                'type_corps' => $_POST['type_corps']
            ];

            if (empty($data['nom_corps'])) {
                $this->setFlashMessage('error', 'Le nom du corps est obligatoire');
                $this->redirect("/corps/edit/$id");
                return;
            }

            if (empty($data['type_corps'])) {
                $this->setFlashMessage('error', 'Le type du corps est obligatoire');
                $this->redirect("/corps/edit/$id");
                return;
            }

            if ($this->corpsModel->update($id, $data)) {
                $this->setFlashMessage('success', 'Corps mis à jour avec succès');
                $this->redirect('/corps');
            } else {
                $this->setFlashMessage('error', 'Erreur lors de la mise à jour du corps');
                $this->redirect("/corps/edit/$id");
            }
        }

        $types = $this->corpsModel->getTypes();
        $this->render('corps/edit', [
            'corps' => $corps,
            'types' => $types,
            'csrf_token' => $this->generateCSRFToken(),
            'messages' => $this->getFlashMessages()
        ]);
    }

    public function delete($id) {
        $corps = $this->corpsModel->findById($id);
        
        if (!$corps) {
            $this->setFlashMessage('error', 'Corps non trouvé');
            $this->redirect('/corps');
        }

        // Check if corps has related grades
        $grades = $this->gradeModel->findByCorps($id);
        if (!empty($grades)) {
            $this->setFlashMessage('error', 'Impossible de supprimer ce corps car il contient des grades');
            $this->redirect('/APP_SGRHBMKH/corps');
            return;
        }

        if ($this->corpsModel->delete($id)) {
            $this->setFlashMessage('success', 'Corps supprimé avec succès');
        } else {
            $this->setFlashMessage('error', 'Erreur lors de la suppression du corps');
        }

        $this->redirect('/APP_SGRHBMKH/corps');
    }

    public function show($id) {
        $corps = $this->corpsModel->findDetailById($id);
        
        if (!$corps) {
            $this->setFlashMessage('error', 'Corps non trouvé');
            $this->redirect('/APP_SGRHBMKH/corps');
        }

        $grades = $this->gradeModel->findByCorps($id);
        $types = $this->corpsModel->getTypes();
        
        $this->render('corps/view', [
            'corps' => $corps,
            'grades' => $grades,
            'types' => $types
        ]);
    }
}
