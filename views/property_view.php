<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title><?= htmlspecialchars($property->title) ?></title>
    <style>
        :root {
            --border: #e0e0e0;
            --bg-light: #fafafa;
            --success: #4caf50;
            --success-light: #c8e6c9;
            --error-light: #ffcdd2;
            --text-muted: #555;
        }

        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            margin: 2rem auto;
            max-width: 900px;
            line-height: 1.5;
            color: #222;
        }

        h1 {
            font-size: 1.8rem;
            margin-bottom: 0.2rem;
        }

        h2 {
            font-size: 1.3rem;
            margin-top: 2rem;
            border-bottom: 1px solid var(--border);
            padding-bottom: 0.3rem;
        }

        .client {
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 1rem;
            margin: 1rem 0;
            background: white;
        }

        .client strong {
            font-size: 1.1rem;
        }

        .affinity-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .affinity-bar {
            height: 8px;
            background: #eee;
            border-radius: 6px;
            margin-top: 0.3rem;
            overflow: hidden;
            width: 30%;
            float: right;
        }

        .affinity-bar > div {
            height: 8px;
            background: var(--success);
        }

        .score {
            font-weight: 600;
        }

        .toggle {
            cursor: pointer;
            color: #0074d9;
            margin-top: 0.5rem;
            display: inline-block;
        }

        .details {
            display: none;
            margin-top: 0.8rem;
            border-top: 1px solid var(--border);
            padding-top: 0.6rem;
            font-size: 0.9rem;
        }

        .details-item {
            margin-bottom: 0.4rem;
            padding: 0.3rem 0.4rem;
            border-radius: 6px;
        }

        .ok {
            background: var(--success-light);
        }

        .fail {
            background: var(--error-light);
        }

        .small {
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .info {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
    </style>
    <script>
        function toggleDetails(id) {
            const el = document.getElementById(id);
            el.style.display = el.style.display === 'none' ? 'block' : 'none';
        }
        /*$(document).ready(function() {
            $('#detailAffinity').on('click', function() {
                const id = $(this).attr('id');
                toggleDetails(id);
            });
        });*/

    </script>
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
                                <strong><?=ucfirst($k)?></strong> — <?=$pct?> % — <span class="detail"><?=htmlspecialchars($d['note'])?></span>
                                <?php if (isset($d['prop_price'])): ?>
                                    <div class="small">Precio inmueble: <?=number_format($d['prop_price'],0,',','.')?> €, rango cliente: <?=number_format($d['client_range'][0],0,',','.')?>-<?=number_format($d['client_range'][1],0,',','.')?> €</div>
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
