<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Listado de Propiedades</title>
    <!-- Enlaces a CSS - Favicon y JS externos -->
    <link rel="stylesheet" href="src/assets/css/style.css">
    <link rel="icon" href="../public/favicon.ico" type="image/x-icon">
    <script src="src/assets/js/script.js" defer></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- jQuery (requerido por DataTables) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
</head>
<body>
    <table id="tablaPropiedades">
        <caption>Inmuebles</caption>
        <thead>
            <tr>
                <th>Título</th>
                <th class="celda-centrada">Zona</th>
                <th class="celda-centrada">Precio</th>
                <th class="celda-centrada">Habitaciones</th>
                <th class="celda-centrada">Garaje</th>
                <th class="celda-centrada">Terraza</th>
                <th class="celda-centrada">Área (m²)</th>
                <th class="celda-centrada">Afinidad</th>
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
