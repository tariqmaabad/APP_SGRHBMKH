<?php
class GradeController extends Controller {
    private $gradeModel;
    private $corpsModel;

    public function __construct() {
        parent::__construct();
        require_once __DIR__ . '/../models/Grade.php';
        require_once __DIR__ . '/../models/Corps.php';
        
        $this->gradeModel = new Grade();
        $this->corpsModel = new Corps();
    }

    public function index() {
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $perPage = 10;
        $corps_id = isset($_GET['corps_id']) ? (int)$_GET['corps_id'] : null;
        
        try {
            $total = $this->gradeModel->countTotal($corps_id);
            $total_pages = ceil($total / $perPage);
            
            $grades = $this->gradeModel->findWithCorpsAndPagination($corps_id, $page, $perPage);
            $stats = $this->gradeModel->getStatsParCorps();
            $corps = $this->corpsModel->findAllSorted();
            
            $this->render('grades/index', [
                'grades' => $grades,
                'corps' => $corps,
                'stats' => $stats,
                'current_corps' => $corps_id,
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
            error_log("Error in grades index: " . $e->getMessage());
            $this->setFlashMessage('error', 'Une erreur est survenue lors du chargement des grades.');
            $this->redirect('/APP_SGRHBMKH/grades');
        }
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $data = [
                'nom_grade' => trim($_POST['nom_grade']),
                'corps_id' => (int)$_POST['corps_id'],
                'echelle' => trim($_POST['echelle'] ?? '')
            ];

            if (empty($data['nom_grade'])) {
                $this->setFlashMessage('error', 'Le nom du grade est obligatoire');
                $this->redirect('/APP_SGRHBMKH/grades/create');
            }

            if (empty($data['corps_id'])) {
                $this->setFlashMessage('error', 'Le corps est obligatoire');
                $this->redirect('/grades/create');
            }

            if ($this->gradeModel->create($data)) {
                $this->setFlashMessage('success', 'Grade créé avec succès');
                $this->redirect('/APP_SGRHBMKH/grades');
            } else {
                $this->setFlashMessage('error', 'Erreur lors de la création du grade');
                $this->redirect('/grades/create');
            }
        }

        $corps = $this->corpsModel->findAllSorted();
        $this->render('grades/create', [
            'corps' => $corps,
            'csrf_token' => $this->generateCSRFToken(),
            'messages' => $this->getFlashMessages()
        ]);
    }

    public function edit($id) {
        $grade = $this->gradeModel->findById($id);
        
        if (!$grade) {
            $this->setFlashMessage('error', 'Grade non trouvé');
            $this->redirect('/APP_SGRHBMKH/grades');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->validateCSRF();
            
            $data = [
                'nom_grade' => trim($_POST['nom_grade']),
                'corps_id' => (int)$_POST['corps_id'],
                'echelle' => trim($_POST['echelle'] ?? '')
            ];

            if (empty($data['nom_grade'])) {
                $this->setFlashMessage('error', 'Le nom du grade est obligatoire');
                $this->redirect("/APP_SGRHBMKH/grades/edit/$id");
            }

            if (empty($data['corps_id'])) {
                $this->setFlashMessage('error', 'Le corps est obligatoire');
                $this->redirect("/grades/edit/$id");
            }

            if ($this->gradeModel->update($id, $data)) {
                $this->setFlashMessage('success', 'Grade mis à jour avec succès');
                $this->redirect('/APP_SGRHBMKH/grades');
            } else {
                $this->setFlashMessage('error', 'Erreur lors de la mise à jour du grade');
                $this->redirect("/APP_SGRHBMKH/grades/edit/$id");
            }
        }

        $corps = $this->corpsModel->findAllSorted();
        $this->render('grades/edit', [
            'grade' => $grade,
            'corps' => $corps,
            'csrf_token' => $this->generateCSRFToken(),
            'messages' => $this->getFlashMessages()
        ]);
    }

    public function delete($id) {
        $this->validateCSRF();
        $grade = $this->gradeModel->findById($id);
        
        if (!$grade) {
            $this->setFlashMessage('error', 'Grade non trouvé');
            $this->redirect('/APP_SGRHBMKH/grades');
        }

        if ($this->gradeModel->delete($id)) {
            $this->setFlashMessage('success', 'Grade supprimé avec succès');
        } else {
            $this->setFlashMessage('error', 'Erreur lors de la suppression du grade');
        }

        $this->redirect('/APP_SGRHBMKH/grades');
    }

    public function byCorps($corps_id) {
        $grades = $this->gradeModel->findByCorps($corps_id);
        $this->json($grades);
    }
}
