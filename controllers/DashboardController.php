<?php
require_once __DIR__ . '/../models/Dashboard.php';
require_once __DIR__ . '/../models/Personnel.php';
require_once __DIR__ . '/../config/database.php';

class DashboardController extends Controller {
    private $dashboardModel;
    private $db;

    public function __construct() {
        $this->dashboardModel = new Dashboard();
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function index() {
        $this->requireAuth();

        // Récupère toutes les statistiques via le modèle Dashboard
        $stats = $this->dashboardModel->getStats();

        $this->render('dashboard/index', [
            'stats' => $stats,
            'messages' => $this->getFlashMessages()
        ]);
    }
}
?>
