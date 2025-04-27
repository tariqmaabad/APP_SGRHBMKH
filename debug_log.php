<?php
ini_set('display_errors', 1);
ini_set('log_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/models/Model.php';
require_once __DIR__ . '/models/MouvementPersonnel.php';

$mouvements = new MouvementPersonnel();

// Test data retrieval
$type_test = $mouvements->getStatsByType();
echo "Type Statistics:\n";
print_r($type_test);

$recent_test = $mouvements->getRecents();
echo "\nRecent Movements:\n";
print_r(array_slice($recent_test, 0, 3));
