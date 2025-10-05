<?php
namespace Src\Repositories;

use Src\Models\Property;

interface PropertyRepository {
    public function findById(int $id): ?Property;
    /** @return Property[] */
    public function findAll(): array;
}
