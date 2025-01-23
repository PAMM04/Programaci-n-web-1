<?php
session_start();
require_once "conexion.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    // Asignar valores predeterminados para un visitante
    $usuario = [
        'id_usuario' => 3,
        'nombre' => 'Visitante',
        'email' => 'visitante@ejemplo.com',
        'rol_id' => 3
    ];
} else {
    $idusuario = $_SESSION['id_usuario'];

    // Obtener información del usuario registrado
    $queryUsuario = "SELECT nombre, email, rol_id FROM usuario WHERE id_usuario = ?";
    $stmtUsuario = $conexion->prepare($queryUsuario);
    $stmtUsuario->bind_param("i", $idusuario);
    $stmtUsuario->execute();
    $resultUsuario = $stmtUsuario->get_result();

    if ($resultUsuario->num_rows > 0) {
        $usuario = $resultUsuario->fetch_assoc();
    } else {
        // En caso de que no se encuentre el usuario, tratarlo como visitante
        $usuario = [
            'id_usuario' => 3,
            'nombre' => 'Visitante',
            'email' => 'visitante@ejemplo.com',
            'rol_id' => 3
        ];
    }
}
// Cargar los permisos del rol del usuario
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

// Validar permisos específicos
$puedeVerDashboard = in_array('ver_dashboard', $permisos);
$puedeGestionarUsuarios = in_array('gestionar_usuarios', $permisos);
$puedeCrearNoticia = in_array('crear_noticia', $permisos);

// Obtener noticias para el dashboard
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

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Bootstrap CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        body {
            background-color:rgb(201, 201, 201);
            color: #fff;
        }
        .navbar {
            background-color: #1f1f1f;
        }
        .table-dark th, .table-dark td {
            color: #fff;
        }
        .card {
            background-color: #212529;
            border: none;
        }
        .card h5 {
            color: #fff;
        }
        .card p {
            color: #fff;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .card-img-top {
            width: 100%;            /* Asegura que ocupe todo el ancho del contenedor */
            height: 200px;          /* Altura fija para uniformidad */
            object-fit: cover;      /* Recorta la imagen para llenar el espacio sin deformarse */
            object-position: center; /* Centra la parte visible de la imagen recortada */
        }


        .highlight-section {
            background-color:rgb(113, 113, 113); /* Color diferente para destacar */
            color: #fff;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top" data-bs-theme="dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Dashboard</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <form class="d-flex" role="search" method="GET">
    <input class="form-control me-2" type="search" name="search" placeholder="Buscar" aria-label="Buscar">
    <button class="btn btn-outline-success" type="submit">Buscar</button>
</form>

      <ul class="navbar-nav ms-auto">
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <?php echo htmlspecialchars($usuario['nombre']); ?>
        </a>
        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
            <li>
                <h6 class="dropdown-header">Bienvenido, <?php echo htmlspecialchars($usuario['nombre']); ?></h6>
            </li>
            <li>
                <span class="dropdown-item-text"><strong>Email:</strong> <?php echo htmlspecialchars($usuario['email']); ?></span>
            </li>
            <li><hr class="dropdown-divider"></li>
            <?php if ($puedeGestionarUsuarios): ?>
                <li><a class="dropdown-item" href="manage_users.php">Gestion de datos</a></li>
                <li><a class="dropdown-item" href="view_logs.php">Ver Registros</a></li>
                <li><a class="dropdown-item" href="settings.php">Configuraciones del Sistema</a></li>
            <?php endif; ?>
            <?php if (!isset($_SESSION['id_usuario'])): ?>
                <li><a class="dropdown-item" href="login.html">Iniciar sesión</a></li>
            <?php else: ?>
                <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
            <?php endif; ?>

            
        </ul>
    </li>
</ul>

    </div>
  </div>
</nav>

    <div class="container mt-4">
    <div class="row">
        <?php if ($puedeVerDashboard): ?>
            <div class="highlight-section">
                    
            </div>
            <h2>Noticias</h2>
            <div class="row">
                <?php while ($noticia = $resultNoticias->fetch_assoc()): ?>
                    <div class="col-md-4 mb-3">
                <div class="card bg-secondary text-light h-100">
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
        <?php endif; ?>
        
                        
        
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
</body>
</html>


