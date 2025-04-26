<?php
require_once __DIR__ . '/../controllers/Controller.php';

class RapportController extends Controller {
    private $personnel;
    private $formations;
    private $mouvements;
    private $provinces;
    private $corps;
    private $categories;
    private $specialites;

    public function __construct() {
        parent::__construct();
        require_once __DIR__ . '/../models/Personnel.php';
        require_once __DIR__ . '/../models/FormationSanitaire.php';
        require_once __DIR__ . '/../models/MouvementPersonnel.php';
        require_once __DIR__ . '/../models/Province.php';
        require_once __DIR__ . '/../models/Corps.php';
        require_once __DIR__ . '/../models/CategorieEtablissement.php';
        require_once __DIR__ . '/../models/Specialite.php';

        $this->personnel = new Personnel();
        $this->formations = new FormationSanitaire();
        $this->mouvements = new MouvementPersonnel();
        $this->provinces = new Province();
        $this->corps = new Corps();
        $this->categories = new CategorieEtablissement();
        $this->specialites = new Specialite();
    }

    public function effectifs() {
        header('Content-Type: text/html; charset=utf-8');
        echo "<!--\nDebug:\n";
        echo "Session data: " . print_r($_SESSION, true) . "\n";
        
        try {
            if (!isset($_SESSION['user_id'])) {
                echo "No user session found\n-->";
                $this->redirect('/APP_SGRHBMKH/auth/login');
                return;
            }

            echo "User authenticated. Processing request...\n";

            $province_id = $_GET['province_id'] ?? null;
            $corps_id = $_GET['corps_id'] ?? null;

            echo "Filters set. Loading data...\n";

            $stats = [
                'total' => $this->personnel->count($province_id, $corps_id),
                'titulaires' => $this->personnel->countByCategorie('TITULAIRE', $province_id, $corps_id),
                'contractuels' => $this->personnel->countByCategorie('CONTRACTUEL', $province_id, $corps_id),
                'formations' => $this->formations->count($province_id),
                'par_corps' => $this->personnel->getStatsByCorps($province_id),
                'par_province' => $this->personnel->getStatsByProvince($corps_id),
                'pyramide_ages' => $this->personnel->getPyramideAges($province_id, $corps_id),
                'par_specialite' => $this->personnel->getStatsBySpecialite($province_id, $corps_id)
            ];

            echo "Stats loaded: " . print_r($stats, true) . "\n";

            $provinces = $this->provinces->findAllSorted();
            $corps = $this->corps->findAllSorted();

            echo "Filter data loaded\n";
            echo "About to render view\n-->";

            $this->render('rapports/effectifs', compact('stats', 'provinces', 'corps'));
        } catch (Exception $e) {
            echo "Error occurred: " . $e->getMessage() . "\n";
            echo "Stack trace: " . $e->getTraceAsString() . "\n-->";
            throw $e;
        }
    }

    public function mouvements() {
        $type_mouvement = $_GET['type_mouvement'] ?? null;
        $date_debut = $_GET['date_debut'] ?? null;
        $date_fin = $_GET['date_fin'] ?? null;

        $stats = [
            'total' => $this->mouvements->count($type_mouvement, $date_debut, $date_fin),
            'mutations' => $this->mouvements->countByType('MUTATION', $date_debut, $date_fin),
            'formations' => $this->mouvements->countByType('FORMATION', $date_debut, $date_fin),
            'autres' => $this->mouvements->countAutres($date_debut, $date_fin),
            'par_type' => $this->mouvements->getStatsByType($date_debut, $date_fin),
            'evolution_mensuelle' => $this->mouvements->getEvolutionMensuelle($type_mouvement, $date_debut, $date_fin)
        ];

        $mouvements = $this->mouvements->getRecents($type_mouvement, $date_debut, $date_fin);

        $this->render('rapports/mouvements', compact('stats', 'mouvements'));
    }

    public function etablissements() {
        $province_id = $_GET['province_id'] ?? null;
        $categorie_id = $_GET['categorie_id'] ?? null;
        $milieu = $_GET['milieu'] ?? null;

        $stats = [
            'total' => $this->formations->count($province_id, $categorie_id, $milieu),
            'urbain' => $this->formations->countByMilieu('URBAIN', $province_id, $categorie_id),
            'rural' => $this->formations->countByMilieu('RURAL', $province_id, $categorie_id),
            'personnel' => $this->personnel->countTotal(),
            'par_categorie' => $this->formations->getStatsByCategorie($province_id, $milieu),
            'par_province' => $this->formations->getStatsByProvince($categorie_id, $milieu)
        ];

        $etablissements = $this->formations->getAllWithDetails($province_id, $categorie_id, $milieu);
        $provinces = $this->provinces->findAllSorted();
        $categories = $this->categories->findAllSorted();

        $this->render('rapports/etablissements', compact('stats', 'etablissements', 'provinces', 'categories'));
    }
}
