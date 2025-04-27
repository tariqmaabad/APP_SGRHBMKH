<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Model.php';
require_once __DIR__ . '/models/MouvementPersonnel.php';

$mouvements = new MouvementPersonnel();
$results = $mouvements->getRecents();

echo "Number of records found: " . count($results) . "\n\n";
echo "First few records:\n";
print_r(array_slice($results, 0, 3));
