<?php
namespace Src\Models;

use Src\Strategies\AffinityStrategy;

class AffinityCalculator {
    private $strategy;

    public function __construct(AffinityStrategy $strategy) {
        $this->strategy = $strategy;
    }

    public function calculate(Property $property, Client $client): array {
        return $this->strategy->compute_affinity($property, $client);
    }
}
