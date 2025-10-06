<?php
namespace Src\Controllers;

use Src\Repositories\PropertyRepository;
use Src\Repositories\ClientRepository;
use Src\Models\AffinityCalculator;

class PropertyController {
    private $propertyRepo;
    private $clientRepo;
    private $calculator;

    public function __construct(PropertyRepository $propertyRepo, ClientRepository $clientRepo, AffinityCalculator $calculator) {
        $this->propertyRepo = $propertyRepo;
        $this->clientRepo = $clientRepo;
        $this->calculator = $calculator;
    }

    public function showProperty(int $id): void {
        $property = $this->propertyRepo->getById($id);
        if (!$property) {
            echo "<h2>Inmueble no encontrado (id={$id})</h2>";
            return;
        }

        $clients = $this->clientRepo->findAll();
        $matches = [];

        foreach ($clients as $client) {
            $result = $this->calculator->calculate($property, $client);
            $matches[] = [
                'client' => $client,
                'score' => $result['score'],
                'details' => $result['details']
            ];
        }


        // Ordenar por mayor afinidad
        usort($matches, fn($a, $b) => $b['score'] <=> $a['score']);

        include __DIR__ . '/../../views/property_view.php';
    }
}
