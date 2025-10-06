<?php
namespace Src\Strategies;

use Src\Models\Property;
use Src\Models\Client;

interface AffinityStrategy {
    public function compute_affinity(Property $property, Client $client): array;
}
