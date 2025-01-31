<?php
session_start();
require_once "conexion.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

// Obtener datos del usuario
$idusuario = $_SESSION['id_usuario'];
$queryUsuario = "SELECT nombre, email, rol_id FROM usuario WHERE id_usuario = ?";
$stmtUsuario = $conexion->prepare($queryUsuario);
$stmtUsuario->bind_param("i", $idusuario);
$stmtUsuario->execute();
$resultUsuario = $stmtUsuario->get_result();
$usuario = $resultUsuario->fetch_assoc();

if (!$usuario) {
    echo "Usuario no encontrado.";
    exit();
}

// Verificar permisos
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

if (!in_array('gestionar_comentarios', $permisos)) {
    echo "No tienes permisos para gestionar comentarios.";
    exit();
}

// Manejo de acciones sobre los comentarios
$mensaje = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $idComentario = intval($_POST['id_comentario'] ?? 0);

    if ($idComentario > 0) {
        if ($accion === 'eliminar') {
            $queryEliminar = "DELETE FROM comentarios WHERE id_comentario = ?";
            $stmtEliminar = $conexion->prepare($queryEliminar);
            $stmtEliminar->bind_param("i", $idComentario);
            $stmtEliminar->execute();

            $mensaje = $stmtEliminar->affected_rows > 0
                ? "Comentario eliminado correctamente."
                : "Error al eliminar el comentario.";
        } elseif ($accion === 'ocultar') {
            $queryAprobar = "UPDATE comentarios SET estado = 'inactivo' WHERE id_comentario = ?";
            $stmtAprobar = $conexion->prepare($queryAprobar);
            $stmtAprobar->bind_param("i", $idComentario);
            $stmtAprobar->execute();

            $mensaje = $stmtAprobar->affected_rows > 0
                ? "Comentario ocultado correctamente."
                : "Error al ocultar el comentario.";
        }elseif ($accion === 'mostrar') {
            $queryAprobar = "UPDATE comentarios SET estado = 'activo' WHERE id_comentario = ?";
            $stmtAprobar = $conexion->prepare($queryAprobar);
            $stmtAprobar->bind_param("i", $idComentario);
            $stmtAprobar->execute();

            $mensaje = $stmtAprobar->affected_rows > 0
                ? "El comentario se mostrara correctamente."
                : "Error al aprobar el comentario.";
        }
    }
}

// Obtener lista de comentarios pendientes
$queryComentarios = "
    SELECT c.id_comentario, c.contenido, c.fecha_comen, c.estado, c.id_usuario ,u.nombre AS autor
    FROM comentarios c
    INNER JOIN usuario u ON c.id_usuario = u.id_usuario
    ORDER BY c.fecha_comen DESC";
$resultComentarios = $conexion->query($queryComentarios);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Comentarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand">Gestión de Comentarios</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Volver al Dashboard</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h1 class="mb-4">Gestión de Comentarios</h1>

    <?php if ($mensaje): ?>
        <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
    <?php endif; ?>

    <table class="table table-striped">
    <thead>
        <tr>
            <th>#</th>
            <th>Contenido</th>
            <th>Autor</th>
            <th>Fecha</th>
            <th>Estado</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($comentario = $resultComentarios->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($comentario['id_comentario']); ?></td>
                <td><?php echo htmlspecialchars($comentario['contenido']); ?></td>
                <td>
                    <?php if (!empty($comentario['id_usuario'])): ?>
                        <a href="editar_usuario.php?id=<?php echo htmlspecialchars($comentario['id_usuario']); ?>" class="text-decoration-none">
                            <?php echo htmlspecialchars($comentario['autor']); ?>
                        </a>
                    <?php else: ?>
                        <?php echo htmlspecialchars($comentario['autor']); ?>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($comentario['fecha_comen']); ?></td>
                <td><?php echo $comentario['estado'] === 'activo' ? 'Activo' : 'Inactivo'; ?></td>
                <td>
                    <form method="POST" class="d-inline">
                        
                        <?php if ($comentario['estado'] === 'inactivo'): ?>
                        <input type="hidden" name="id_comentario" value="<?php echo $comentario['id_comentario']; ?>">
                        <input type="hidden" name="accion" value="mostrar">
                        <button class="btn btn-success btn-sm" > Mostrar</button>
                        <?php else: ?>
                        <input type="hidden" name="id_comentario" value="<?php echo $comentario['id_comentario']; ?>">
                        <input type="hidden" name="accion" value="ocultar">
                        <button class="btn btn-success btn-sm" > Ocultar </button>
                        <?php endif; ?>
                        
                    </form>
                    <form method="POST" class="d-inline">
                        <input type="hidden" name="id_comentario" value="<?php echo $comentario['id_comentario']; ?>">
                        <input type="hidden" name="accion" value="eliminar">
                        <button class="btn btn-danger btn-sm">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </tbody>
</table>


</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
