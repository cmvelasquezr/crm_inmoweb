<?php
/*namespace Src\Repositories;

use Src\Models\Property;

class InMemoryPropertyRepository implements PropertyRepository {
    private $properties;

    public function __construct() {
        $this->properties = [
            new Property(1, 'Piso luminoso con terraza en Chamberí', 'Chamberí', 420000, 3, true, true, 95),
            new Property(2, 'Apartamento en el Centro', 'Centro', 250000, 1, false, false, 45)
        ];
    }

    public function findById(int $id): ?Property {
        foreach ($this->properties as $p) {
            if ($p->id === $id) return $p;
        }
        return null;
    }

    public function findAll(): array {
        return $this->properties;
    }
}
*/