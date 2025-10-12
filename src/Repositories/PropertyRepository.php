<?php
namespace Src\Repositories;

use Src\Models\Property;

interface PropertyRepository {
    public function getById(int $id): ?Property;
    /** @return Property[] */
    public function getAll(): array;
}
