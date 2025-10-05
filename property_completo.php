<?php
// property.php
// Ejemplo autónomo: abre http://localhost:8000/property.php?id=1

// -----------------------------
// Datos de ejemplo
// -----------------------------
$properties = [
    1 => [
        'id' => 1,
        'title' => 'Piso luminoso con terraza en Chamberí',
        'zone' => 'Chamberí',
        'price' => 420000,
        'rooms' => 3,
        'area_m2' => 95,
        'has_garage' => true,
        'has_terrace' => true,
        'floor' => 3
    ],
    2 => [
        'id' => 2,
        'title' => 'Apartamento céntrico, ideal inversión',
        'zone' => 'Centro',
        'price' => 250000,
        'rooms' => 1,
        'area_m2' => 45,
        'has_garage' => false,
        'has_terrace' => false,
        'floor' => 1
    ]
];

$clients = [
    [
        'id' => 101,
        'name' => 'Ana Pérez',
        'preferred_zones' => ['Chamberí', 'Ríos Rosas'],
        'budget_min' => 350000,
        'budget_max' => 450000,
        'rooms_min' => 2,
        'rooms_max' => 4,
        'needs_garage' => true,
        'wants_terrace' => true,
        'priority' => 'alta' // atributo meta (no usado directamente)
    ],
    [
        'id' => 102,
        'name' => 'Carlos Ruiz',
        'preferred_zones' => ['Centro', 'Lavapiés'],
        'budget_min' => 200000,
        'budget_max' => 300000,
        'rooms_min' => 1,
        'rooms_max' => 2,
        'needs_garage' => false,
        'wants_terrace' => false,
        'priority' => 'media'
    ],
    [
        'id' => 103,
        'name' => 'María López',
        'preferred_zones' => ['Chamberí'],
        'budget_min' => 400000,
        'budget_max' => 480000,
        'rooms_min' => 3,
        'rooms_max' => 5,
        'needs_garage' => false,
        'wants_terrace' => true,
        'priority' => 'alta'
    ],
    [
        'id' => 104,
        'name' => 'Javier Martín',
        'preferred_zones' => ['Salamanca'],
        'budget_min' => 500000,
        'budget_max' => 800000,
        'rooms_min' => 3,
        'rooms_max' => 5,
        'needs_garage' => true,
        'wants_terrace' => false,
        'priority' => 'baja'
    ]
];

// -----------------------------
// Lógica de cálculo de afinidad
// -----------------------------
// Cada criterio tiene un peso (suma ideal 100)
// Ajusta pesos según relevancia de tu negocio
$WEIGHTS = [
    'zone' => 30,
    'price' => 30,
    'rooms' => 15,
    'garage' => 10,
    'terrace' => 10,
    'area' => 5
];

/**
 * Normaliza una puntuación entre 0 y 1
 */
function clamp01($v) {
    if ($v < 0) return 0;
    if ($v > 1) return 1;
    return $v;
}

/**
 * Calcula afinidad entre propiedad y cliente.
 * Devuelve array con 'score' (0-100) y 'details' (array de razones)
 */
function compute_affinity($property, $client, $weights) {
    $details = [];

    // Zona: exact match en preferred_zones
    $zone_match = in_array($property['zone'], $client['preferred_zones']) ? 1.0 : 0.0;
    $details['zone'] = [
        'value' => $zone_match,
        'weight' => $weights['zone'],
        'note' => $zone_match ? "Zona preferida" : "Zona fuera de preferencias"
    ];

    // Precio: puntuación basada en distancia al rango
    if ($property['price'] >= $client['budget_min'] && $property['price'] <= $client['budget_max']) {
        $price_score = 1.0;
        $price_note = "Dentro del presupuesto";
    } else {
        // penaliza proporcionalmente a la distancia relativa
        $diff = 0;
        if ($property['price'] < $client['budget_min']) {
            $diff = ($client['budget_min'] - $property['price']) / max(1, $client['budget_min']);
        } else {
            $diff = ($property['price'] - $client['budget_max']) / max(1, $client['budget_max']);
        }
        // mapear a score: si la diferencia es grande -> 0; si pequeña -> cercano a 1
        $price_score = clamp01(1.0 - $diff*2); // factor 2 para mayor sensibilidad
        $price_note = "Fuera de presupuesto (diferencia relativa: " . round($diff, 2) . ")";
    }
    $details['price'] = [
        'value' => $price_score,
        'weight' => $weights['price'],
        'note' => $price_note,
        'prop_price' => $property['price'],
        'client_range' => [$client['budget_min'], $client['budget_max']]
    ];

    // Habitaciones: si está dentro del rango -> 1, si no penaliza según distancia
    if ($property['rooms'] >= $client['rooms_min'] && $property['rooms'] <= $client['rooms_max']) {
        $rooms_score = 1.0;
        $rooms_note = "Número de habitaciones dentro del rango";
    } else {
        $rooms_diff = min(
            abs($property['rooms'] - $client['rooms_min']),
            abs($property['rooms'] - $client['rooms_max'])
        );
        $rooms_score = clamp01(1.0 - ($rooms_diff / max(1, $client['rooms_max'])) );
        $rooms_note = "Fuera de rango de habitaciones (diferencia: $rooms_diff)";
    }
    $details['rooms'] = [
        'value' => $rooms_score,
        'weight' => $weights['rooms'],
        'note' => $rooms_note,
        'prop_rooms' => $property['rooms'],
        'client_rooms' => [$client['rooms_min'], $client['rooms_max']]
    ];

    // Garaje: si cliente necesita y propiedad tiene -> 1; si cliente necesita y propiedad no tiene -> 0
    $garage_score = 1.0;
    $garage_note = "No es un requisito";
    if ($client['needs_garage']) {
        $garage_score = $property['has_garage'] ? 1.0 : 0.0;
        $garage_note = $property['has_garage'] ? "Cumple requisito de garaje" : "Cliente necesita garaje";
    } else {
        // cliente no necesita: leve preferencia si tiene
        $garage_score = $property['has_garage'] ? 0.9 : 1.0;
        $garage_note = $property['has_garage'] ? "Tiene garaje (no necesario)" : "No tiene garaje (no necesario)";
    }
    $details['garage'] = [
        'value' => $garage_score,
        'weight' => $weights['garage'],
        'note' => $garage_note
    ];

    // Terraza: parecido a garaje, pero menos estricto
    $terrace_weight = $weights['terrace'];
    if ($client['wants_terrace']) {
        $terrace_score = $property['has_terrace'] ? 1.0 : 0.3; // si no tiene, no es 0 pero baja mucho
        $terrace_note = $property['has_terrace'] ? "Tiene terraza" : "Cliente desea terraza";
    } else {
        $terrace_score = $property['has_terrace'] ? 0.8 : 1.0;
        $terrace_note = $property['has_terrace'] ? "Tiene terraza (no deseada)" : "No tiene terraza (ok)";
    }
    $details['terrace'] = [
        'value' => $terrace_score,
        'weight' => $terrace_weight,
        'note' => $terrace_note
    ];

    // Superficie (area): damos una puntuación sencilla: si mayor que rooms*20 m2 se considera bueno
    $ideal_area_score = clamp01($property['area_m2'] / max(1, ($property['rooms'] * 25))); // 25 m2 por habitación ideal
    $area_score = clamp01($ideal_area_score);
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

// -----------------------------
// Render: seleccionar propiedad por id y mostrar clientes ordenados por afinidad
// -----------------------------
$id = isset($_GET['id']) ? intval($_GET['id']) : 1;
$property = isset($properties[$id]) ? $properties[$id] : null;

if (!$property) {
    echo "<h2>Inmueble no encontrado (id={$id})</h2>";
    exit;
}

// Calcular afinidades
$matches = [];
foreach ($clients as $c) {
    $res = compute_affinity($property, $c, $WEIGHTS);
    $matches[] = [
        'client' => $c,
        'score' => $res['score'],
        'details' => $res['details']
    ];
}

// Ordenar por score descendente
usort($matches, function($a, $b) {
    return $b['score'] <=> $a['score'];
});

// -----------------------------
// HTML: vista sencilla
// -----------------------------
?>
<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ficha inmueble — Afinidad clientes</title>
    <style>
        body { font-family: system-ui, Arial; margin: 24px; max-width: 900px; }
        .prop { border-bottom: 1px solid #ddd; padding-bottom: 12px; margin-bottom: 18px; }
        .client { border: 1px solid #eee; padding: 12px; margin-bottom: 10px; border-radius: 8px; }
        .score { font-weight: 700; font-size: 1.15rem; }
        .detail { font-size: 0.9rem; color: #444; }
        .bar { height: 10px; background: #f1f1f1; border-radius: 6px; overflow: hidden; margin-top: 6px; }
        .bar > i { display: block; height: 10px; background: linear-gradient(90deg,#4caf50,#8bc34a); }
        .small { font-size: 0.85rem; color:#666; }
        .kpi { display:inline-block; margin-right: 10px; padding:6px 10px; background:#f7f7f7; border-radius:6px; }
        details { margin-top: 8px; }
    </style>
</head>
<body>
    <div class="prop">
        <h1><?=htmlspecialchars($property['title'])?></h1>
        <div class="small">
            Zona: <?=htmlspecialchars($property['zone'])?> — Precio: <?=number_format($property['price'],0,',','.')?> € — 
            Habitaciones: <?=$property['rooms']?> — Superficie: <?=$property['area_m2']?> m²
        </div>
    </div>

    <h2>Clientes potenciales (ordenados por afinidad)</h2>

    <?php foreach ($matches as $m): 
        $c = $m['client'];
        $score = $m['score'];
        ?>
        <div class="client">
            <div style="display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <strong><?=htmlspecialchars($c['name'])?></strong>
                    <div class="small">ID cliente: <?=$c['id']?> — Preferencias: <?=htmlspecialchars(implode(', ', $c['preferred_zones']))?></div>
                </div>
                <div style="text-align:right;">
                    <div class="score"><?=$score?> %</div>
                    <div class="small">Afinidad estimada</div>
                    <div class="bar" style="width:180px;">
                        <i style="width:<?=max(0,min(100,$score))?>%;"></i>
                    </div>
                </div>
            </div>

            <details>
                <summary style="cursor:pointer; margin-top:8px;">Ver desglose</summary>
                <div style="margin-top:8px;">
                    <?php foreach ($m['details'] as $k => $d): 
                        $pct = round($d['value'] * 100, 0);
                        ?>
                        <div style="margin-bottom:6px;">
                            <strong><?=ucfirst($k)?></strong> — <?=$pct?> % — <span class="detail"><?=htmlspecialchars($d['note'])?></span>
                            <?php if (isset($d['prop_price'])): ?>
                                <div class="small">Precio inmueble: <?=number_format($d['prop_price'],0,',','.')?> €, rango cliente: <?=number_format($d['client_range'][0],0,',','.')?>-<?=number_format($d['client_range'][1],0,',','.')?> €</div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </details>
        </div>
    <?php endforeach; ?>

    <p class="small">Notas: algoritmo demo. Ajusta pesos, reglas y la normalización según tus datos reales. Para muchos clientes inmensos, precalcula índices o limita la búsqueda a clientes "activos" para rendimiento.</p>
</body>
</html>
