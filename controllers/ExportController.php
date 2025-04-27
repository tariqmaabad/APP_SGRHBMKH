<?php
require_once __DIR__ . '/Controller.php';

class ExportController extends Controller {
    private $personnel;
    private $mouvements;
    private $formations;
    private $provinces;
    private $corps;

    public function __construct() {
        parent::__construct();
        require_once __DIR__ . '/../models/Personnel.php';
        require_once __DIR__ . '/../models/MouvementPersonnel.php';
        require_once __DIR__ . '/../models/FormationSanitaire.php';
        require_once __DIR__ . '/../models/Province.php';
        require_once __DIR__ . '/../models/Corps.php';

        $this->personnel = new Personnel();
        $this->mouvements = new MouvementPersonnel();
        $this->formations = new FormationSanitaire();
        $this->provinces = new Province();
        $this->corps = new Corps();
    }

    public function staff() {
        $provinces = $this->provinces->findAll();
        $formations = $this->formations->findAll();
        $corps = $this->corps->findAll();
        
        $this->render('rapports/export_staff', compact('provinces', 'formations', 'corps'));
    }

    public function movements() {
        $provinces = $this->provinces->findAll();
        $corps = $this->corps->findAll();
        
        $this->render('rapports/export_movements', compact('provinces', 'corps'));
    }

    public function establishments() {
        $this->render('rapports/export_establishments');
    }

    public function exportStaffList() {
        $province_id = $_GET['province_id'] ?? null;
        $formation_id = $_GET['formation_id'] ?? null;
        $corps_id = $_GET['corps_id'] ?? null;
        $id = $_GET['id'] ?? null;
        $format = $_GET['format'] ?? 'excel';

        if ($id) {
            // Export individual personnel details
            $person = $this->personnel->findByIdWithDetails($id);
            $mouvements = $this->personnel->getMouvements($id);
            
            if (!$person) {
                header('Location: /APP_SGRHBMKH/personnel');
                exit;
            }

            $data = [array_merge($person, ['mouvements' => $mouvements])];
            $filename = 'personnel_' . $person['ppr'] . '_' . date('Y-m-d_His');
            
            // Format the person data
            $formattedPerson = array_merge($person, [
                'date_naissance' => date('d/m/Y', strtotime($person['date_naissance'])),
                'date_recrutement' => date('d/m/Y', strtotime($person['date_recrutement'])),
                'date_prise_service' => date('d/m/Y', strtotime($person['date_prise_service'])),
                'sexe' => $person['sexe'] === 'M' ? 'Masculin' : 'Féminin',
                'situation_familiale' => [
                    'CELIBATAIRE' => 'Célibataire',
                    'MARIE' => 'Marié(e)',
                    'DIVORCE' => 'Divorcé(e)',
                    'VEUF' => 'Veuf/Veuve'
                ][$person['situation_familiale']] ?? $person['situation_familiale']
            ]);

            // Format the movements
            $formattedMovements = [];
            foreach ($mouvements as $mouvement) {
                $formattedMovements[] = array_merge($mouvement, [
                    'date_mouvement' => date('d/m/Y', strtotime($mouvement['date_mouvement'])),
                    'type_mouvement' => [
                        'MUTATION' => 'Mutation',
                        'MISE_A_DISPOSITION' => 'Mise à disposition',
                        'FORMATION' => 'Formation',
                        'SUSPENSION' => 'Suspension',
                        'MISE_EN_DISPONIBILITE' => 'Mise en disponibilité',
                        'RETRAITE_AGE' => 'Retraite',
                        'DECES' => 'Décès',
                        'DEMISSION' => 'Démission'
                    ][$mouvement['type_mouvement']] ?? $mouvement['type_mouvement']
                ]);
            }
            
            $data = [array_merge($formattedPerson, ['mouvements' => $formattedMovements])];
            
            if ($format === 'pdf') {
                $headers = [
                    'ppr' => 'PPR',
                    'cin' => 'CIN',
                    'nom' => 'Nom',
                    'prenom' => 'Prénom',
                    'date_naissance' => 'Date de naissance',
                    'sexe' => 'Sexe',
                    'situation_familiale' => 'Situation familiale',
                    'nom_corps' => 'Corps',
                    'nom_grade' => 'Grade',
                    'nom_specialite' => 'Spécialité',
                    'nom_formation' => 'Formation Sanitaire',
                    'nom_province' => 'Province',
                    'date_recrutement' => 'Date de recrutement',
                    'date_prise_service' => 'Date de prise de service'
                ];
                
                $title = 'Fiche du Personnel - ' . $person['nom'] . ' ' . $person['prenom'];
                $this->exportToPDF($data, $filename, $title, $headers, true);
            } else {
                $this->exportToExcel($data, $filename, [
                    'ppr' => 'PPR',
                    'cin' => 'CIN',
                    'nom' => 'Nom',
                    'prenom' => 'Prénom',
                    'date_naissance' => 'Date de naissance',
                    'sexe' => 'Sexe',
                    'situation_familiale' => 'Situation familiale',
                    'nom_corps' => 'Corps',
                    'nom_grade' => 'Grade',
                    'nom_specialite' => 'Spécialité',
                    'nom_formation' => 'Formation Sanitaire',
                    'nom_province' => 'Province',
                    'date_recrutement' => 'Date de recrutement',
                    'date_prise_service' => 'Date de prise de service'
                ], true);
            }
            exit;
        }

        // Export list of personnel
        $conditions = [];
        if ($corps_id) $conditions['corps_id'] = $corps_id;
        if ($formation_id) $conditions['formation_sanitaire_id'] = $formation_id;
        
        $staff = $this->personnel->findAllWithDetails($conditions);
        
        if ($province_id) {
            $staff = array_filter($staff, function($person) use ($province_id) {
                return isset($person['province_id']) && $person['province_id'] == $province_id;
            });
        }

        $filename = 'staff_list_' . date('Y-m-d_His');
        
        if ($format === 'pdf') {
            $this->exportToPDF($staff, $filename, 'Liste du Personnel', [
                'PPR', 'CIN', 'Nom', 'Prénom', 'Corps', 'Grade', 'Établissement', 'Province'
            ]);
        } else {
            $this->exportToExcel($staff, $filename, [
                'ppr' => 'PPR',
                'cin' => 'CIN',
                'nom' => 'Nom',
                'prenom' => 'Prénom',
                'nom_corps' => 'Corps',
                'nom_grade' => 'Grade',
                'nom_formation' => 'Établissement',
                'nom_province' => 'Province'
            ], false, 'Liste du Personnel');
        }
    }

    public function exportMovementList() {
        $province_id = $_GET['province_id'] ?? null;
        $corps_id = $_GET['corps_id'] ?? null;
        $format = $_GET['format'] ?? 'excel';

        $conditions = [];
        if ($corps_id) $conditions['corps_id'] = $corps_id;

        // Get movements data with details
        $movements = $this->mouvements->findWithDetails($conditions);

        // Filter by province if specified
        if ($province_id) {
            $movements = array_filter($movements, function($movement) use ($province_id) {
                return (isset($movement['origine_province_id']) && $movement['origine_province_id'] == $province_id) ||
                       (isset($movement['destination_province_id']) && $movement['destination_province_id'] == $province_id);
            });
        }

        $filename = 'movements_list_' . date('Y-m-d_His');

        if ($format === 'pdf') {
            $this->exportToPDF($movements, $filename, 'Liste des Mouvements', [
                'Date', 'PPR', 'Nom', 'Prénom', 'Type', 'Origine', 'Destination'
            ]);
        } else {
            $this->exportToExcel($movements, $filename, [
                'date_mouvement' => 'Date',
                'ppr' => 'PPR',
                'nom' => 'Nom',
                'prenom' => 'Prénom',
                'type_mouvement' => 'Type',
                'origine_nom' => 'Origine',
                'destination_nom' => 'Destination'
            ], false, 'Liste des Mouvements');
        }
    }

    public function exportEstablishmentsList() {
        $format = $_GET['format'] ?? 'excel';

        // Get all establishments with their staff count
        $establishments = $this->formations->getAllWithDetails();
        
        foreach ($establishments as &$establishment) {
            $establishment['staff_count'] = $this->personnel->count(null, $establishment['id']);
        }

        $filename = 'establishments_list_' . date('Y-m-d_His');

        if ($format === 'pdf') {
            $this->exportToPDF($establishments, $filename, 'Liste des Établissements', [
                'Nom', 'Catégorie', 'Province', 'Type', 'Milieu', 'Effectif'
            ]);
        } else {
            $this->exportToExcel($establishments, $filename, [
                'nom_formation' => 'Nom',
                'nom_categorie' => 'Catégorie',
                'nom_province' => 'Province',
                'type_formation' => 'Type',
                'milieu' => 'Milieu',
                'staff_count' => 'Effectif'
            ], false, 'Liste des Établissements');
        }
    }

    private function exportToExcel($data, $filename, $headers, $isDetailedView = false, $title = 'Export de données') {
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="' . $filename . '.xls"');
        
        if ($isDetailedView) {
            // Detailed personnel view
            $person = $data[0];
            
            // Title
            echo "FICHE DU PERSONNEL\n";
            echo str_repeat('=', 100) . "\n\n";
            
            // Personal Information Section
            echo "INFORMATIONS PERSONNELLES\n";
            echo str_repeat('-', 50) . "\n";
            foreach ($headers as $key => $label) {
                echo str_pad($label, 30, ' ', STR_PAD_RIGHT) . "\t" . ($person[$key] ?? '') . "\n";
            }
            
            // Movements Section
            if (isset($person['mouvements']) && !empty($person['mouvements'])) {
                echo "\nHISTORIQUE DES MOUVEMENTS\n";
                echo str_repeat('-', 50) . "\n\n";
                
                // Headers with fixed widths
                echo str_pad("Date", 15, ' ', STR_PAD_RIGHT) . "\t"
                   . str_pad("Type", 25, ' ', STR_PAD_RIGHT) . "\t"
                   . str_pad("Origine", 30, ' ', STR_PAD_RIGHT) . "\t"
                   . str_pad("Destination", 30, ' ', STR_PAD_RIGHT) . "\t"
                   . "Commentaire\n";
                echo str_repeat('-', 120) . "\n";
                
                foreach ($person['mouvements'] as $mouvement) {
                    echo str_pad($mouvement['date_mouvement'] ?? '', 15, ' ', STR_PAD_RIGHT) . "\t"
                       . str_pad($mouvement['type_mouvement'] ?? '', 25, ' ', STR_PAD_RIGHT) . "\t"
                       . str_pad($mouvement['origine_nom'] ?? $mouvement['origine'] ?? '', 30, ' ', STR_PAD_RIGHT) . "\t"
                       . str_pad($mouvement['destination_nom'] ?? $mouvement['destination'] ?? '', 30, ' ', STR_PAD_RIGHT) . "\t"
                       . ($mouvement['commentaire'] ?? '') . "\n";
                }
            }
        } else {
            // List view title
            echo mb_strtoupper($title) . "\n";
            echo str_repeat('=', 100) . "\n\n";
            
            // Column headers with fixed widths
            $columnWidths = [];
            foreach ($headers as $key => $label) {
                $columnWidths[$key] = 20; // Default width
            }
            
            // Adjust specific column widths
            if (isset($headers['nom'])) $columnWidths['nom'] = 25;
            if (isset($headers['prenom'])) $columnWidths['prenom'] = 25;
            if (isset($headers['nom_formation'])) $columnWidths['nom_formation'] = 40;
            
            // Print headers
            $headerLine = '';
            foreach ($headers as $key => $label) {
                $headerLine .= str_pad($label, $columnWidths[$key] ?? 20, ' ', STR_PAD_RIGHT) . "\t";
            }
            echo $headerLine . "\n";
            echo str_repeat('-', strlen(strip_tags($headerLine)) + 20) . "\n";
            
            // Print data
            foreach ($data as $row) {
                $line = '';
                foreach ($headers as $key => $label) {
                    $value = $row[$key] ?? '';
                    $line .= str_pad($value, $columnWidths[$key] ?? 20, ' ', STR_PAD_RIGHT) . "\t";
                }
                echo $line . "\n";
            }
        }
        exit;
    }

    private function exportToPDF($data, $filename, $title, $headers, $isDetailedView = false) {
        require_once __DIR__ . '/../config/tcpdf_config.php';
        require_once __DIR__ . '/../vendor/tcpdf/tcpdf.php';
        
        // Set page orientation
        $orientation = $isDetailedView ? 'P' : 'L';  // Portrait for detailed view, Landscape for lists
        $pdf = new TCPDF($orientation, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(PDF_AUTHOR);
        $pdf->SetTitle($title);
        
        // Remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(true);
        
        // Set margins
        $pdf->SetMargins(15, 15, 15);
        $pdf->SetAutoPageBreak(TRUE, 15);
        
        // Add a page
        $pdf->AddPage();

        if ($isDetailedView) {
            $person = $data[0];
            
            // Title
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(0, 10, 'Fiche du Personnel', 0, 1, 'C');
            $pdf->Ln(5);

            // Personal Information Section
            $pdf->SetFont('helvetica', 'B', 14);
            $pdf->Cell(0, 10, 'Informations Personnelles', 0, 1, 'L');
            $pdf->SetFont('helvetica', '', 11);
            
            $html = '<table border="1" cellpadding="5">';
            foreach ($headers as $key => $label) {
                $html .= '<tr>
                    <th width="30%" style="background-color: #f5f5f5;"><b>' . $label . '</b></th>
                    <td width="70%">' . htmlspecialchars($person[$key] ?? '-') . '</td>
                </tr>';
            }
            $html .= '</table>';
            $pdf->writeHTML($html, true, false, true, false, '');
            
            // Movements Section
            if (isset($person['mouvements']) && !empty($person['mouvements'])) {
                $pdf->AddPage();
                $pdf->SetFont('helvetica', 'B', 14);
                $pdf->Cell(0, 10, 'Historique des Mouvements', 0, 1, 'L');
                $pdf->SetFont('helvetica', '', 11);
                
                $html = '<table border="1" cellpadding="5">
                    <tr style="background-color: #f5f5f5;">
                        <th width="20%"><b>Date</b></th>
                        <th width="15%"><b>Type</b></th>
                        <th width="25%"><b>Origine</b></th>
                        <th width="25%"><b>Destination</b></th>
                        <th width="15%"><b>Commentaire</b></th>
                    </tr>';
                    
                foreach ($person['mouvements'] as $mouvement) {
                    $html .= '<tr>
                        <td>' . htmlspecialchars($mouvement['date_mouvement']) . '</td>
                        <td>' . htmlspecialchars($mouvement['type_mouvement']) . '</td>
                        <td>' . htmlspecialchars($mouvement['origine_nom'] ?? $mouvement['origine'] ?? '-') . '</td>
                        <td>' . htmlspecialchars($mouvement['destination_nom'] ?? $mouvement['destination'] ?? '-') . '</td>
                        <td style="font-size: 9pt;">' . htmlspecialchars($mouvement['commentaire'] ?? '-') . '</td>
                    </tr>';
                }
                $html .= '</table>';
                $pdf->writeHTML($html, true, false, true, false, '');
            }
        } else {
            // Regular list view
            $pdf->SetFont('helvetica', 'B', 16);
            $pdf->Cell(0, 10, $title, 0, 1, 'C');
            $pdf->Ln(5);
            
            $pdf->SetFont('helvetica', '', 11);
            $html = '<table border="1" cellpadding="5">';
            $html .= '<tr style="background-color: #f5f5f5;"><th>' . implode('</th><th>', $headers) . '</th></tr>';
            
            foreach ($data as $row) {
                $html .= '<tr>';
                if ($headers && is_array($headers)) {
                    foreach ($headers as $key => $label) {
                        $value = is_array($row) && isset($row[$key]) ? $row[$key] : '';
                        
                        // Format dates
                        if (in_array($key, ['date_naissance', 'date_recrutement', 'date_prise_service']) && !empty($value)) {
                            $value = date('d/m/Y', strtotime($value));
                        }
                        
                        // Format gender
                        if ($key === 'sexe') {
                            $value = $value === 'M' ? 'Masculin' : 'Féminin';
                        }
                        
                        // Format situation familiale
                        if ($key === 'situation_familiale') {
                            $situations = [
                                'CELIBATAIRE' => 'Célibataire',
                                'MARIE' => 'Marié(e)',
                                'DIVORCE' => 'Divorcé(e)',
                                'VEUF' => 'Veuf/Veuve'
                            ];
                            $value = $situations[$value] ?? $value;
                        }
                        
                        $html .= '<td>' . htmlspecialchars($value) . '</td>';
                    }
                } else {
                    foreach ($row as $value) {
                        $html .= '<td>' . htmlspecialchars($value) . '</td>';
                    }
                }
                $html .= '</tr>';
            }
            $html .= '</table>';
            
            $pdf->writeHTML($html, true, false, true, false, '');
        }
        
        // Close and output PDF document
        $pdf->Output($filename . '.pdf', 'D');
        exit;
    }
}
