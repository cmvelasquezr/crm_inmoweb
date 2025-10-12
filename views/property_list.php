<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Propiedades</title>
    <!-- Enlaces a CSS y JS externos -->
    <link rel="stylesheet" href="src/assets/css/style.css">
    <script src="src/assets/js/script.js" defer></script>
</head>
<body>
    <table>
        <caption>Inmuebles</caption>
        <thead>
            <tr>
                <th>Título</th>
                <th>Zona</th>
                <th>Precio</th>
                <th>Habitaciones</th>
                <th>Garaje</th>
                <th>Terraza</th>
                <th>Área (m²)</th>
                <th>Afinidad</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($properties as $property): ?>
                <tr class="property-row" data-id="<?= $property->id ?>">
                    <td><?= htmlspecialchars($property->title) ?></td>
                    <td><?= htmlspecialchars($property->zone) ?></td>
                    <td class="celda-centrada"><?= number_format($property->price, 0, ',', '.') ?> €</td>
                    <td class="celda-centrada"><?= htmlspecialchars($property->rooms) ?></td>
                    <td class="celda-centrada"><?= $property->hasGarage ? 'Sí' : 'No' ?></td>
                    <td class="celda-centrada"><?= $property->hasTerrace ? 'Sí' : 'No' ?></td>
                    <td class="celda-centrada"><?= htmlspecialchars($property->areaM2) ?></td>
                    <td class="celda-centrada"><button id="btnAfinidad" class="btn-afinidad">Ver Afinidad</button></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Modal -->
    <div id="propertyModal" class="modal">
        <div class="modal-content">
            <span class="close" id="closeModal">&times;</span>
            <h2 id="modalTitle"></h2>
            <p><strong>Zona:</strong> <span id="modalZone"></span></p>
            <p><strong>Precio:</strong> <span id="modalPrice"></span></p>
            <p><strong>Habitaciones:</strong> <span id="modalRooms"></span></p>
            <p><strong>Garaje:</strong> <span id="modalGarage"></span></p>
            <p><strong>Terraza:</strong> <span id="modalTerrace"></span></p>
            <p><strong>Área:</strong> <span id="modalArea"></span> m²</p>
        </div>
    </div>

</body>
</html>
