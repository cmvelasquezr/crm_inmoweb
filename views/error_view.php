<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Error en Aplicación</title>
    <link rel="stylesheet" href="src/assets/css/style.css">
</head>

<body>
    <h1>Error en Aplicación</h1>
    <div class="error-box">
        <h2>⚠️ Se ha producido un error</h2>
        <p><?= htmlspecialchars($error_message) ?></p>
    </div>
    
</body>
</html>
