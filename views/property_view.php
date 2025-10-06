<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($property->title) ?></title>

    <!-- Enlaces a CSS y JS externos -->
    <link rel="stylesheet" href="src/assets/css/style.css">
    <script src="src/assets/js/script.js" defer></script>
</head>

<body>
    <h1><?= htmlspecialchars($property->title) ?></h1>
    <p class="info">
        Zona: <?= $property->zone ?> —
        Precio: <?= number_format($property->price, 0, ',', '.') ?> € —
        Habitaciones: <?= $property->rooms ?> —
        Superficie: <?= $property->areaM2 ?> m²
    </p>

    <h2>Clientes potenciales (ordenados por afinidad)</h2>

    <?php foreach ($matches as $m): ?>
        <?php 
            $client = $m['client']; 
            $score = $m['score'];
            $details = $m['details'];
            $detailsId = 'details-' . $client->id;
        ?>
        <div class="client">
            <div class="affinity-header">
                <div>
                    <strong><?= htmlspecialchars($client->name) ?></strong><br>
                    <span class="small">
                        ID cliente: <?= $client->id ?> — Preferencias: <?= implode(', ', $client->preferredZones) ?>
                    </span>
                </div>
                <div style="text-align:right">
                    <div class="score"><?= $score ?> %</div>
                    <div class="small">Afinidad estimada</div>
                </div>
            </div>

            <div class="affinity-bar"><div style="width: <?= $score ?>%;"></div></div>

            <details>
                <summary style="cursor:pointer; margin-top:8px; color: darkblue;">Ver desglose</summary>
                <div style="margin-top:8px;">
                    <?php foreach ($m['details'] as $k => $d):
                        $pct = round($d['value'] * 100, 0);
                    ?>
                        <div class="details-item <?= $d['value'] >= 0.5 ? 'ok' : 'fail' ?>">
                            <div style="margin-bottom:6px;">
                                <strong><?= ucfirst($k) ?></strong> — <?= $pct ?> % — 
                                <span class="detail"><?= htmlspecialchars($d['note']) ?></span>
                                <?php if (isset($d['prop_price'])): ?>
                                    <div class="small">
                                        Precio inmueble: <?= number_format($d['prop_price'], 0, ',', '.') ?> €,
                                        rango cliente: <?= number_format($d['client_range'][0], 0, ',', '.') ?> - <?= number_format($d['client_range'][1], 0, ',', '.') ?> €
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </details>
        </div>
    <?php endforeach; ?>
</body>
</html>
