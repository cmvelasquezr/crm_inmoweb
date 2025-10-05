<?php
namespace Src\Strategies;

use Src\Models\Property;
use Src\Models\Client;

interface AffinityStrategy {
    public function calculate(Property $property, Client $client): array;
}
