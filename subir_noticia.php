<?php
session_start();
require_once "conexion.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

// Obtener información del usuario
$idusuario = $_SESSION['id_usuario'];
$queryUsuario = "SELECT nombre, email, rol_id FROM usuario WHERE id_usuario = ?";
$stmtUsuario = $conexion->prepare($queryUsuario);
$stmtUsuario->bind_param("i", $idusuario);
$stmtUsuario->execute();
$resultUsuario = $stmtUsuario->get_result();

if ($resultUsuario->num_rows > 0) {
    $usuario = $resultUsuario->fetch_assoc();
} else {
    header('Location: login.php');
    exit;
}

// Cargar los permisos del usuario
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

// Validar permisos
if (!in_array('crear_noticia', $permisos)) {
    echo "<div class='alert alert-danger'>No tienes permiso para crear noticias.</div>";
    exit;
}

// Obtener categorías
$queryCategorias = "SELECT id_categoria, nombre FROM categoria";
$resultCategorias = $conexion->query($queryCategorias);
if (!$resultCategorias) {
    die("Error al obtener categorías: " . $conexion->error);
}

// Manejar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = trim($_POST['titulo']);
    $contenido = trim($_POST['contenido']);
    $categoria_id = intval($_POST['categoria_id']);
    $destacado = isset($_POST['destacado']) ? 1 : 0;

    // Validar y procesar la imagen
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreImagen = $_FILES['imagen']['name'];
        $tipoImagen = $_FILES['imagen']['type'];
        $rutaTemporal = $_FILES['imagen']['tmp_name'];

        $extensionesPermitidas = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($tipoImagen, $extensionesPermitidas)) {
            echo "<div class='alert alert-danger'>Solo se permiten imágenes JPEG, PNG o GIF.</div>";
            exit;
        }

        $carpetaDestino = 'uploads/';
        if (!file_exists($carpetaDestino)) {
            mkdir($carpetaDestino, 0777, true);
        }
        $rutaImagen = $carpetaDestino . uniqid() . "_" . basename($nombreImagen);
        if (!move_uploaded_file($rutaTemporal, $rutaImagen)) {
            echo "<div class='alert alert-danger'>Error al guardar la imagen en el servidor.</div>";
            exit;
        }
    } else {
        echo "<div class='alert alert-danger'>Debe subir una imagen válida.</div>";
        exit;
    }

    // Validar los campos obligatorios
    if ($titulo && $contenido && $categoria_id && $rutaImagen) {
        $query = "INSERT INTO noticias (titulo, contenido, imagen, categoria_id, id_usuario, destacado) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("sssiii", $titulo, $contenido, $rutaImagen, $categoria_id, $idusuario, $destacado);

        if ($stmt->execute()) {
            header('Location: lista_noticias.php');
            exit;
        } else {
            echo "<div class='alert alert-danger'>Error al guardar la noticia: " . $stmt->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Todos los campos son obligatorios.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subir Noticia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: rgb(201, 201, 201);
        }
        .container {
            margin-top: 30px;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="dashboard.php">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo htmlspecialchars($usuario['nombre']); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                        <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container">
    <h1>Subir Nueva Noticia</h1>
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" class="form-control" id="titulo" name="titulo" placeholder="Escribe el título de la noticia" required>
        </div>
        <div class="mb-3">
            <label for="contenido" class="form-label">Contenido</label>
            <textarea class="form-control" id="contenido" name="contenido" rows="5" placeholder="Escribe el contenido de la noticia" required></textarea>
        </div>
        <div class="mb-3">
            <label for="categoria_id" class="form-label">Categoría</label>
            <select class="form-select" id="categoria_id" name="categoria_id" required>
                <option value="">Seleccione una categoría</option>
                <?php while ($categoria = $resultCategorias->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($categoria['id_categoria']) ?>">
                        <?= htmlspecialchars($categoria['nombre']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="imagen" class="form-label">Imagen (obligatoria)</label>
            <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
        </div>
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="destacado" name="destacado">
            <label class="form-check-label" for="destacado">Destacar esta noticia</label>
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-primary">Subir Noticia</button>
            <a href="manage_users.php" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
