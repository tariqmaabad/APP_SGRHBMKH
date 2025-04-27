<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Model.php';
require_once __DIR__ . '/models/MouvementPersonnel.php';

$mouvements = new MouvementPersonnel();
$type_mouvement = $_GET['type_mouvement'] ?? null;
$date_debut = $_GET['date_debut'] ?? null;
$date_fin = $_GET['date_fin'] ?? null;

$stats = [
    'total' => $mouvements->count($type_mouvement, $date_debut, $date_fin),
    'mutations' => $mouvements->countByType('MUTATION', $date_debut, $date_fin),
    'formations' => $mouvements->countByType('FORMATION', $date_debut, $date_fin),
    'autres' => $mouvements->countAutres($date_debut, $date_fin),
    'par_type' => $mouvements->getStatsByType($type_mouvement, $date_debut, $date_fin),
    'evolution_mensuelle' => $mouvements->getEvolutionMensuelle($type_mouvement, $date_debut, $date_fin)
];

echo "Stats data:\n";
print_r($stats);

$filtered_mouvements = $mouvements->getRecents($type_mouvement, $date_debut, $date_fin);
echo "\nNumber of filtered records: " . count($filtered_mouvements) . "\n";
