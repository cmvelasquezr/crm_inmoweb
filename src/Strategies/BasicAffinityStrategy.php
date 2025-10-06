<?php
namespace Src\Strategies;

use Src\Models\Property;
use Src\Models\Client;

class BasicAffinityStrategy implements AffinityStrategy {

    /**
     * Normaliza una puntuación entre 0 y 1
     */
    private function clamp01($v) {
        if ($v < 0) return 0;
        if ($v > 1) return 1;
        return $v;
    }
        
    /**
     * Calcula afinidad entre propiedad y cliente.
     * Devuelve array con 'score' (0-100) y 'details' (array de razones)
     */
    public function compute_affinity(Property $property, Client $client):array {
        $details = [];

        $weights = [
            'zone' => 30,
            'price' => 30,
            'rooms' => 15,
            'garage' => 10,
            'terrace' => 10,
            'area' => 5
        ];

        // Zona: exact match en preferred_zones
        $zone_match = in_array($property->zone, $client->preferredZones) ? 1.0 : 0.0;
        $details['zone'] = [
            'value' => $zone_match,
            'weight' => $weights['zone'],
            'note' => $zone_match ? "Zona preferida" : "Zona fuera de preferencias"
        ];

        // Precio: puntuación basada en distancia al rango
        if ($property->price >= $client->budgetMin && $property->price <= $client->budgetMax) {
            $price_score = 1.0;
            $price_note = "Dentro del presupuesto";
        } else {
            // penaliza proporcionalmente a la distancia relativa
            $diff = 0;
            if ($property->price < $client->budgetMin) {
                $diff = ($client->budgetMin - $property->price) / max(1, $client->budgetMin);
            } else {
                $diff = ($property->price - $client->budgetMax) / max(1, $client->budgetMax);
            }
            // mapear a score: si la diferencia es grande -> 0; si pequeña -> cercano a 1
            $price_score = $this->clamp01(1.0 - $diff*2); // factor 2 para mayor sensibilidad
            $price_note = "Fuera de presupuesto (diferencia relativa: " . round($diff, 2) . ")";
        }
        $details['price'] = [
            'value' => $price_score,
            'weight' => $weights['price'],
            'note' => $price_note,
            'prop_price' => $property->price,
            'client_range' => [$client->budgetMin, $client->budgetMax]
        ];

        // Habitaciones: si está dentro del rango -> 1, si no penaliza según distancia
        if ($property->rooms >= $client->roomsMin && $property->rooms <= $client->roomsMax) {
            $rooms_score = 1.0;
            $rooms_note = "Número de habitaciones dentro del rango";
        } else {
            $rooms_diff = min(
                abs($property->rooms - $client->roomsMin),
                abs($property->rooms - $client->roomsMax)
            );
            $rooms_score = $this->clamp01(1.0 - ($rooms_diff / max(1, $client->roomsMax)) );
            $rooms_note = "Fuera de rango de habitaciones (diferencia: $rooms_diff)";
        }
        $details['rooms'] = [
            'value' => $rooms_score,
            'weight' => $weights['rooms'],
            'note' => $rooms_note,
            'prop_rooms' => $property->rooms,
            'client_rooms' => [$client->roomsMin, $client->roomsMax]
        ];

        // Garaje: si cliente necesita y propiedad tiene -> 1; si cliente necesita y propiedad no tiene -> 0
        $garage_score = 1.0;
        $garage_note = "No es un requisito";
        if ($client->needsGarage) {
            $garage_score = $property->hasGarage ? 1.0 : 0.0;
            $garage_note = $property->hasGarage ? "Cumple requisito de garaje" : "Cliente necesita garaje";
        } else {
            // cliente no necesita: leve preferencia si tiene
            $garage_score = $property->hasGarage ? 0.9 : 1.0;
            $garage_note = $property->hasGarage ? "Tiene garaje (no necesario)" : "No tiene garaje (no necesario)";
        }
        $details['garage'] = [
            'value' => $garage_score,
            'weight' => $weights['garage'],
            'note' => $garage_note
        ];

        // Terraza: parecido a garaje, pero menos estricto
        $terrace_weight = $weights['terrace'];
        if ($client->wantsTerrace) {
            $terrace_score = $property->hasTerrace ? 1.0 : 0.3; // si no tiene, no es 0 pero baja mucho
            $terrace_note = $property->hasTerrace ? "Tiene terraza" : "Cliente desea terraza";
        } else {
            $terrace_score = $property->hasTerrace ? 0.8 : 1.0;
            $terrace_note = $property->hasTerrace ? "Tiene terraza (no deseada)" : "No tiene terraza (ok)";
        }
        $details['terrace'] = [
            'value' => $terrace_score,
            'weight' => $terrace_weight,
            'note' => $terrace_note
        ];

        // Superficie (area): damos una puntuación sencilla: si mayor que rooms*20 m2 se considera bueno
        $ideal_area_score = $this->clamp01($property->areaM2 / max(1, ($property->rooms * 25))); // 25 m2 por habitación ideal
        
        $area_score = $this->clamp01($ideal_area_score);
        $details['area'] = [
            'value' => $area_score,
            'weight' => $weights['area'],
            'note' => "Ratio área/habitación: " . round($ideal_area_score, 2)
        ];

        // Calcular score ponderado (0..100)
        $total_weight = array_sum($weights);
        $weighted_sum = 0.0;
        foreach ($details as $k => $d) {
            $weighted_sum += ($d['value'] * $d['weight']);
        }
        $score_percent = ($weighted_sum / $total_weight) * 100;

        // Redondear y devolver detalle
        return [
            'score' => round($score_percent, 1),
            'details' => $details
        ];
    }
}

