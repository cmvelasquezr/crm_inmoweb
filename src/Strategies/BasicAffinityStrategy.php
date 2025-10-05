<?php
namespace Src\Strategies;

use Src\Models\Property;
use Src\Models\Client;

class BasicAffinityStrategy implements AffinityStrategy {

    public function calculate(Property $p, Client $c): array {
        $score = 0;
        $total = 5;

        $details = [
            'zone' => false,
            'price' => false,
            'rooms' => false,
            'garage' => false,
            'terrace' => false
        ];

        // 1. Zona
        if (in_array($p->zone, $c->preferredZones)) {
            $score++;
            $details['zone'] = true;
        }

        // 2. Precio
        if ($p->price >= $c->budgetMin && $p->price <= $c->budgetMax) {
            $score++;
            $details['price'] = true;
        }

        // 3. Habitaciones
        if ($p->rooms >= $c->roomsMin && $p->rooms <= $c->roomsMax) {
            $score++;
            $details['rooms'] = true;
        }

        // 4. Garaje
        if (!$c->needsGarage || ($c->needsGarage && $p->hasGarage)) {
            $score++;
            $details['garage'] = true;
        }

        // 5. Terraza
        if (!$c->wantsTerrace || ($c->wantsTerrace && $p->hasTerrace)) {
            $score++;
            $details['terrace'] = true;
        }

        $percent = round(($score / $total) * 100, 1);

        return [
            'score' => $percent,
            'details' => $details
        ];
    }
}
