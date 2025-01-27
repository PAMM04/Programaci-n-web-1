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

    $queryUsuario = "SELECT nombre, email, rol_id, perfil FROM usuario WHERE id_usuario = ?";
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
$puedeGestionarUsuarios = in_array('gestionar_usuarios', $permisos);
$puedeCrearNoticia = in_array('crear_noticia', $permisos);

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
if ($searchTerm) {
    $queryNoticias = "SELECT id_noticias, titulo, SUBSTRING(contenido, 1, 100) AS resumen, imagen, fecha_creacion
                      FROM noticias 
                      WHERE titulo LIKE ? OR contenido LIKE ?
                      ORDER BY fecha_creacion DESC";
    $stmtNoticias = $conexion->prepare($queryNoticias);
    $searchTerm = '%' . $searchTerm . '%';
    $stmtNoticias->bind_param("ss", $searchTerm, $searchTerm);
    $stmtNoticias->execute();
    $resultNoticias = $stmtNoticias->get_result();
} else {
    $queryNoticias = "SELECT id_noticias, titulo, SUBSTRING(contenido, 1, 100) AS resumen, imagen, fecha_creacion
                      FROM noticias 
                      ORDER BY fecha_creacion DESC";
    $resultNoticias = $conexion->query($queryNoticias);
}


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
$queryNoticias .= "ORDER BY n.fecha_creacion DESC LIMIT 6";

$stmtNoticias = $conexion->prepare($queryNoticias);
if ($categoriaSeleccionada) {
    $stmtNoticias->bind_param("i", $categoriaSeleccionada);
}
$stmtNoticias->execute();
$resultNoticias = $stmtNoticias->get_result();


$categoriaNombre = '';
if ($categoriaSeleccionada) {
    $queryCategoriaNombre = "SELECT nombre FROM categoria WHERE id_categoria = ?";
    $stmtCategoriaNombre = $conexion->prepare($queryCategoriaNombre);
    $stmtCategoriaNombre->bind_param("i", $categoriaSeleccionada);
    $stmtCategoriaNombre->execute();
    $resultCategoriaNombre = $stmtCategoriaNombre->get_result();

    if ($resultCategoriaNombre->num_rows > 0) {
        $categoriaData = $resultCategoriaNombre->fetch_assoc();
        $categoriaNombre = $categoriaData['nombre'];
    }
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand" >Vortex News</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <form class="d-flex ms-auto" role="search" method="GET">
                <input class="form-control me-2" type="search" name="search" placeholder="Buscar" aria-label="Buscar">
                <button class="btn btn-primary" type="submit">Buscar</button>
            </form>
            <ul class="navbar-nav ms-3">
                <!-- Menú Gestión -->
                <?php if ($puedeGestionarUsuarios || $puedeCrearNoticia): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="gestionDropdown" role="button" data-bs-toggle="dropdown">
                            Gestión
                        </a>
                        <ul class="dropdown-menu">
                            <?php if ($puedeGestionarUsuarios): ?>
                                <li><a class="dropdown-item" href="manage_users.php">Usuarios</a></li>
                            <?php endif; ?>
                            <?php if ($puedeCrearNoticia): ?>
                                <li><a class="dropdown-item" href="lista_noticias.php">Noticias</a></li>
                            <?php endif; ?>
                            <?php if ($puedeGestionarUsuarios): ?>
                                <li><a class="dropdown-item" href="manage_users.php">Comentarios</a></li>
                            <?php endif; ?>
                            <?php if ($puedeGestionarUsuarios): ?>
                                <li><a class="dropdown-item" href="manage_users.php">Reportes</a></li>
                            <?php endif; ?>
                            <?php if ($puedeGestionarUsuarios): ?>
                                <li><a class="dropdown-item" href="manage_users.php">Mensajes y Notificaciones</a></li>
                            <?php endif; ?>
                            <?php if ($puedeGestionarUsuarios): ?>
                                <li><a class="dropdown-item" href="manage_users.php">Configuraciones</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                <?php endif; ?>

                <!-- Menú de Usuario -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                        <?php if (!isset($_SESSION['id_usuario'])): ?>
                        <?php else: ?>
                        <img src="upload_perfil/<?= htmlspecialchars($usuario['perfil'] ?? 'default.png'); ?>" alt="Perfil" class="perfil-circulo me-2">
                        <?php endif; ?>
                        <?php echo htmlspecialchars($usuario['nombre']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" style="right: 0; left: auto;">
                        <li><h6 class="dropdown-header">Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?></h6></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php if (!isset($_SESSION['id_usuario'])): ?>
                            <li><a class="dropdown-item" href="login.php">Iniciar sesión</a></li>
                        <?php else: ?>
                            <li><a class="dropdown-item" href="editar_perfil.php">Perfil</a></li>
                            <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                        <?php endif; ?>
                    </ul>
                </li>
            </ul>
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
    <a class="nav-link" href="contacto.php">Contacto</a>
</ul>


<div class="container mt-4">
    <?php if ($puedeVerDashboard): ?>
        <div class="highlight-section">
        <h2 class="mb-3">Noticias <?php echo $categoriaSeleccionada ? 'de ' . htmlspecialchars($categoriaNombre) : 'Recientes'; ?></h2>
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
