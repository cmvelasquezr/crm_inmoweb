<?php
// --- MODELS ---
require_once __DIR__ . '/src/Models/Property.php';
require_once __DIR__ . '/src/Models/Client.php';
require_once __DIR__ . '/src/Models/AffinityCalculator.php';

// --- STRATEGIES ---
require_once __DIR__ . '/src/Strategies/AffinityStrategy.php';
require_once __DIR__ . '/src/Strategies/BasicAffinityStrategy.php';

// --- REPOSITORIES ---
require_once __DIR__ . '/src/Repositories/PropertyRepository.php';
require_once __DIR__ . '/src/Repositories/ClientRepository.php';
require_once __DIR__ . '/src/Repositories/MySQLClientRepository.php';
require_once __DIR__ . '/src/Repositories/MySQLPropertyRepository.php';

// --- CONTROLLERS ---
require_once __DIR__ . '/src/Controllers/PropertyController.php';

use Src\Controllers\PropertyController;
use Src\Models\AffinityCalculator;
use Src\Strategies\BasicAffinityStrategy;
use Src\Repositories\MySQLClientRepository;
use Src\Repositories\MySQLPropertyRepository;

// Inyección manual de dependencias
$propertyRepo = new MySQLPropertyRepository();
$clientRepo = new MySQLClientRepository();
$strategy = new BasicAffinityStrategy();
$calculator = new AffinityCalculator($strategy);

$controller = new PropertyController($propertyRepo, $clientRepo, $calculator);

// ID desde la URL
$id = $_GET['id'] ?? 1;
$controller->showProperty($id);
