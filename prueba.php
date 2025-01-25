<?php
session_start();
require_once "conexion.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    $usuario = [
        'id_usuario' => 3,
        'nombre' => 'Visitante',
        'email' => 'visitante@ejemplo.com',
        'rol_id' => 3
    ];
} else {
    $idusuario = $_SESSION['id_usuario'];

    $queryUsuario = "SELECT nombre, email, rol_id FROM usuario WHERE id_usuario = ?";
    $stmtUsuario = $conexion->prepare($queryUsuario);
    $stmtUsuario->bind_param("i", $idusuario);
    $stmtUsuario->execute();
    $resultUsuario = $stmtUsuario->get_result();

    $usuario = $resultUsuario->num_rows > 0 ? $resultUsuario->fetch_assoc() : [
        'id_usuario' => 3,
        'nombre' => 'Visitante',
        'email' => 'visitante@ejemplo.com',
        'rol_id' => 3
    ];
}

$queryPermisos = "
    SELECT p.nombre 
    FROM permisos p
    INNER JOIN roles_permisos rp ON p.id_permisos = rp.id_permisos
    WHERE rp.id_rol = ?";
$stmtPermisos = $conexion->prepare($queryPermisos);
$stmtPermisos->bind_param("i", $usuario['rol_id']);
$stmtPermisos->execute();
$resultPermisos = $stmtPermisos->get_result();

$permisos = [];
while ($row = $resultPermisos->fetch_assoc()) {
    $permisos[] = $row['nombre'];
}

$puedeVerDashboard = in_array('ver_dashboard', $permisos);

// Obtener categoría seleccionada (vacío por defecto)
$categoriaSeleccionada = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : 0;

// Obtener lista de categorías
$queryCategorias = "SELECT id_categoria, nombre FROM categoria ORDER BY nombre";
$resultCategorias = $conexion->query($queryCategorias);

// Construir consulta para las noticias
$queryNoticias = "SELECT n.id_noticias, n.titulo, SUBSTRING(n.contenido, 1, 100) AS resumen, n.imagen, n.fecha_creacion 
                  FROM noticias n ";
if ($categoriaSeleccionada) {
    $queryNoticias .= "WHERE n.categoria_id = ? ";
}
$queryNoticias .= "ORDER BY n.fecha_creacion DESC";

$stmtNoticias = $conexion->prepare($queryNoticias);
if ($categoriaSeleccionada) {
    $stmtNoticias->bind_param("i", $categoriaSeleccionada);
}
$stmtNoticias->execute();
$resultNoticias = $stmtNoticias->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <form class="d-flex ms-auto" role="search" method="GET">
                <input class="form-control me-2" type="search" name="search" placeholder="Buscar" aria-label="Buscar">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </form>
        </div>
    </div>
</nav>

<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link <?php echo $categoriaSeleccionada === 0 ? 'active' : ''; ?>" href="?">Todas</a>
    </li>
    <?php while ($categoria = $resultCategorias->fetch_assoc()): ?>
        <li class="nav-item">
            <a class="nav-link <?php echo $categoriaSeleccionada === $categoria['id_categoria'] ? 'active' : ''; ?>" 
               href="?categoria_id=<?php echo $categoria['id_categoria']; ?>">
                <?php echo htmlspecialchars($categoria['nombre']); ?>
            </a>
        </li>
    <?php endwhile; ?>
</ul>

<div class="container mt-4">
    <?php if ($puedeVerDashboard): ?>
        <div class="highlight-section">
            <h2 class="mb-3">Noticias <?php echo $categoriaSeleccionada ? 'de ' . htmlspecialchars($categoria['nombre']) : 'Recientes'; ?></h2>
            <div class="row">
                <?php while ($noticia = $resultNoticias->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <?php if ($noticia['imagen']): ?>
                                <img src="<?php echo htmlspecialchars($noticia['imagen']); ?>" class="card-img-top" alt="Imagen de la noticia">
                            <?php else: ?>
                                <img src="path/to/default-image.jpg" class="card-img-top" alt="Imagen predeterminada">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($noticia['titulo']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($noticia['resumen']); ?>...</p>
                                <a href="ver_noticia.php?id=<?php echo $noticia['id_noticias']; ?>" class="btn btn-primary">Leer más</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<footer>
    <div class="container">
        <p>&copy; <?php echo date('Y'); ?> Noticias. Todos los derechos reservados.</p>
    </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
