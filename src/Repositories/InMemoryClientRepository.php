<?php
namespace Src\Repositories;

use Src\Models\Client;

class InMemoryClientRepository implements ClientRepository {
    private $clients;

    public function __construct() {
        $this->clients = [
            new Client(101, 'Ana Pérez', ['Chamberí', 'Ríos Rosas'], 350000, 450000, 2, 4, true, true),
            new Client(102, 'Carlos Ruiz', ['Centro'], 200000, 300000, 1, 2, false, false),
            new Client(103, 'María López', ['Chamberí'], 400000, 480000, 3, 5, false, true),
            new Client(104, 'Javier Martín', ['Salamanca'], 500000, 800000, 3, 5, true, false),
            new Client(105, 'Carmen García', ['Centro', 'Salamanca'], 300000, 480000, 3, 5, false, true),
        ];
    }

    public function findAll(): array {
        return $this->clients;
    }
}
