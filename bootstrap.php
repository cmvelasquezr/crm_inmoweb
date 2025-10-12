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

// --- HANDLERS ---
require_once __DIR__ . '/src/Services/ErrorHandler.php';

// --- CONFIG (si tienes) ---
if (file_exists(__DIR__ . '/src/Config/constants.php')) {
    require_once __DIR__ . '/src/Config/constants.php';
}
if (file_exists(__DIR__ . '/src/Config/database.php')) {
    require_once __DIR__ . '/src/Config/database.php';
}

// --- SESIÓN Y ZONA HORARIA ---
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
date_default_timezone_set('Europe/Madrid');

