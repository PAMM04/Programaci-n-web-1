<?php
// Iniciar sesión y requerir la conexión a la base de datos
session_start();
require_once "conexion.php";

// Consultar las noticias de la categoría "Deportes" (ID 1)
$queryDeportes = "SELECT * FROM noticias WHERE categoria_id = 1";
$resultDeportes = $conexion->query($queryDeportes);

// Verificar si hay noticias disponibles
if (!$resultDeportes) {
    die("Error al obtener noticias de deportes: " . $conexion->error);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias - Deportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Inicio</a>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="deportes.php">Deportes</a></li>
            <li class="nav-item"><a class="nav-link" href="investigacion.php">Investigación</a></li>
            <li class="nav-item"><a class="nav-link" href="internacionalizacion.php">Internacionalización</a></li>
            <li class="nav-item"><a class="nav-link" href="convenios.php">Convenios</a></li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h1 class="mb-4">Noticias de Deportes</h1>

    <?php if ($resultDeportes->num_rows > 0): ?>
        <div class="row">
            <?php while ($noticia = $resultDeportes->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card">
                        <?php if (!empty($noticia['imagen'])): ?>
                            <img src="<?= htmlspecialchars($noticia['imagen']) ?>" class="card-img-top" alt="<?= htmlspecialchars($noticia['titulo']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($noticia['titulo']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars(substr($noticia['contenido'], 0, 100)) ?>...</p>
                            <a href="ver_noticia.php?id=<?= $noticia['id_noticias'] ?>" class="btn btn-primary">Leer más</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning">No hay noticias disponibles en la categoría de deportes.</div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
