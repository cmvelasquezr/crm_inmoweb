<?php
// --- MODELS ---
require_once __DIR__ . '/src/Models/Property.php';
require_once __DIR__ . '/src/Models/Client.php';
require_once __DIR__ . '/src/Models/AffinityCalculator.php';

// --- STRATEGIES ---
require_once __DIR__ . '/src/Strategies/AffinityStrategy.php';
require_once __DIR__ . '/src/Strategies/BasicAffinityStrategy.php';

// --- CONTRACTS ---
require_once __DIR__ . '/src/Contracts/ErrorAwareRepositoryInterface.php';

// --- REPOSITORIES ---
require_once __DIR__ . '/src/Repositories/BaseRepository.php';
require_once __DIR__ . '/src/Repositories/PropertyRepository.php';
require_once __DIR__ . '/src/Repositories/ClientRepository.php';
require_once __DIR__ . '/src/Repositories/MySQLClientRepository.php';
require_once __DIR__ . '/src/Repositories/MySQLPropertyRepository.php';

// --- CONTROLLERS ---
require_once __DIR__ . '/src/Controllers/PropertyController.php';

// --- HANDLERS ----
require_once __DIR__ . '/src/Services/ErrorHandler.php';

use Src\Controllers\PropertyController;
use Src\Models\AffinityCalculator;
use Src\Strategies\BasicAffinityStrategy;
use Src\Repositories\MySQLClientRepository;
use Src\Repositories\MySQLPropertyRepository;
use Src\Services\ErrorHandler;

$errorHandler = new ErrorHandler();

// Inyección manual de dependencias
// Propiedad
try {
    $propertyRepo = new MySQLPropertyRepository();
} catch (Exception $e) {
    $previous = $e->getPrevious();
    $errorMessage = $e->getMessage();
    json_encode([
        'error' => true,
        'message' => $e->getMessage(),
        'details' => $previous ? $previous->getMessage() : null
    ]);
    $errorHandler->handle($e, $errorMessage);
}

// Cliente
try {
    $clientRepo = new MySQLClientRepository();
} catch (Exception $e) {
    http_response_code(500);
    $errorMessage = $e->getMessage();
    json_encode([
        'error' => true,
        'message' => 'Error al obtener clientes.',
        'details' => $e->getMessage()
    ]);
    $errorHandler->handle($e, $errorMessage);
}

$strategy = new BasicAffinityStrategy();
$calculator = new AffinityCalculator($strategy);


try {
    $controller = new PropertyController($propertyRepo, $clientRepo, $calculator);
} catch (Throwable $e) {
    http_response_code(500); // Error interno del servidor
    echo json_encode([
        'error' => true,
        'message' => 'Error al inicializar el controlador.',
        'details' => $e->getMessage()
    ]);
    exit;
}

//ID Metodo GET por URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, [
    'options' => ['default' => isset($_GET['id']) ? (int) $_GET['id'] : 1, 'min_range' => 1, 'max_range' => 3]
]);


$controller->showProperty($id);
