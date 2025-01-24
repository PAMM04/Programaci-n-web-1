<?php
session_start();
require_once "conexion.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.html');
    exit;
}

// Verificar si se proporcionó un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}

$idnoticia = intval($_GET['id']);
$idusuario = $_SESSION['id_usuario'];
$esAdministrador = isset($_SESSION['rol_id']) && $_SESSION['rol_id'] === 1; // Verificar si el usuario es administrador

// Procesar eliminación de comentario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['eliminar_comentario']) && $esAdministrador) {
    $idComentarioEliminar = intval($_POST['eliminar_comentario']);
    $queryEliminarComentario = "DELETE FROM comentarios WHERE id_comentario = ?";
    $stmtEliminar = $conexion->prepare($queryEliminarComentario);

    if ($stmtEliminar) {
        $stmtEliminar->bind_param("i", $idComentarioEliminar);
        $stmtEliminar->execute();
        $stmtEliminar->close();
        $mensaje = "Comentario eliminado con éxito.";
    } else {
        $mensaje = "Error al intentar eliminar el comentario.";
    }
}

// Procesar el formulario de comentario o respuesta
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario']) && !isset($_POST['eliminar_comentario'])) {
    $comentario = trim($_POST['comentario']);
    $comentarioPadre = isset($_POST['comentario_padre']) && is_numeric($_POST['comentario_padre']) 
        ? intval($_POST['comentario_padre']) 
        : null;

    // Validación básica del comentario
    if (empty($comentario)) {
        $mensaje = "El comentario no puede estar vacío.";
    } else {
        $queryComentario = "INSERT INTO comentarios (id_noticia, id_usuario, id_comentario_padre, contenido, estado) 
                            VALUES (?, ?, ?, ?, ?)";
        $stmtComentario = $conexion->prepare($queryComentario);

        if ($stmtComentario) {
            $estado = 'activo'; // Estado predeterminado
            $stmtComentario->bind_param("iiiss", $idnoticia, $idusuario, $comentarioPadre, $comentario, $estado);
            if ($stmtComentario->execute()) {
                $stmtComentario->close();
                $mensaje = "Comentario enviado con éxito.";
            } else {
                $mensaje = "Error al enviar el comentario: " . $conexion->error;
            }
        } else {
            $mensaje = "Error al preparar la consulta de comentario: " . $conexion->error;
        }
    }
}

// Obtener los detalles de la noticia
$queryNoticia = "SELECT n.titulo, n.contenido, n.imagen, n.fecha_creacion, c.nombre AS categoria 
                 FROM noticias n
                 LEFT JOIN categoria c ON n.categoria_id = c.id_categoria
                 WHERE n.id_noticias = ?";
$stmtNoticia = $conexion->prepare($queryNoticia);

if (!$stmtNoticia) {
    die("Error en la preparación de la consulta de noticia: " . $conexion->error);
}

$stmtNoticia->bind_param("i", $idnoticia);
$stmtNoticia->execute();
$resultNoticia = $stmtNoticia->get_result();

if ($resultNoticia->num_rows === 0) {
    header('Location: dashboard.php');
    exit;
}

$noticia = $resultNoticia->fetch_assoc();

// Obtener noticias relacionadas
$queryRelacionadas = "SELECT id_noticias, titulo, SUBSTRING(contenido, 1, 100) AS resumen, imagen
                      FROM noticias 
                      WHERE id_noticias != ? 
                      ORDER BY RAND() LIMIT 3";
$stmtRelacionadas = $conexion->prepare($queryRelacionadas);

if (!$stmtRelacionadas) {
    die("Error en la preparación de la consulta de noticias relacionadas: " . $conexion->error);
}

$stmtRelacionadas->bind_param("i", $idnoticia);
$stmtRelacionadas->execute();
$resultRelacionadas = $stmtRelacionadas->get_result();

// Obtener comentarios aprobados (y sus respuestas)
$queryComentarios = "SELECT c.id_comentario, c.contenido, c.fecha_comen, c.id_comentario_padre, u.nombre AS autor 
                     FROM comentarios c
                     JOIN usuario u ON c.id_usuario = u.id_usuario
                     WHERE c.id_noticia = ? AND c.estado = 'activo'
                     ORDER BY c.id_comentario_padre ASC, c.fecha_comen ASC";
$stmtComentarios = $conexion->prepare($queryComentarios);

if (!$stmtComentarios) {
    die("Error en la preparación de la consulta de comentarios: " . $conexion->error);
}

$stmtComentarios->bind_param("i", $idnoticia);
$stmtComentarios->execute();
$resultComentarios = $stmtComentarios->get_result();

// Agrupar comentarios y respuestas
$comentarios = [];
while ($comentario = $resultComentarios->fetch_assoc()) {
    if ($comentario['id_comentario_padre'] === null) {
        $comentarios[$comentario['id_comentario']] = [
            'info' => $comentario,
            'respuestas' => []
        ];
    } else {
        $comentarios[$comentario['id_comentario_padre']]['respuestas'][] = $comentario;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($noticia['titulo']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Mi Sitio</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="dashboard.php">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="logout.php">Cerrar sesión</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="text-center"><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
        <p class="text-muted text-center">Publicado el: <?php echo htmlspecialchars($noticia['fecha_creacion']); ?></p>

        <?php if ($noticia['imagen']): ?>
            <img src="<?php echo htmlspecialchars($noticia['imagen']); ?>" class="imagen-noticia" alt="Imagen de la noticia">
        <?php endif; ?>

        <p class="mt-4"><?php echo nl2br(htmlspecialchars($noticia['contenido'])); ?></p>
        <hr>

        <h2>Comentarios</h2>
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>

        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label for="comentario" class="form-label">Deja tu comentario</label>
                <textarea class="form-control" id="comentario" name="comentario" rows="3" required></textarea>
                <input type="hidden" name="comentario_padre" value="">
            </div>
            <button type="submit" class="btn btn-primary">Enviar comentario</button>
        </form>

        <ul class="list-unstyled">
            <?php foreach ($comentarios as $comentarioId => $data): ?>
                <li class="mb-4">
                    <strong><?php echo htmlspecialchars($data['info']['autor']); ?></strong>
                    <small class="fecha-comentario">(<?php echo htmlspecialchars($data['info']['fecha_comen']); ?>)</small>
                    <p><?php echo nl2br(htmlspecialchars($data['info']['contenido'])); ?></p>

                    <?php if ($esAdministrador): ?>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="eliminar_comentario" value="<?php echo $data['info']['id_comentario']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    <?php endif; ?>

                    <form method="POST" class="mt-3">
                        <div class="mb-2">
                            <textarea class="form-control" name="comentario" rows="2" placeholder="Responder a este comentario..." required></textarea>
                            <input type="hidden" name="comentario_padre" value="<?php echo $data['info']['id_comentario']; ?>">
                        </div>
                        <button type="submit" class="btn btn-secondary btn-sm">Responder</button>
                    </form>

                    <?php if (!empty($data['respuestas'])): ?>
                        <ul class="list-unstyled ms-4 mt-3">
                            <?php foreach ($data['respuestas'] as $respuesta): ?>
                                <li>
                                    <strong><?php echo htmlspecialchars($respuesta['autor']); ?></strong>
                                    <small class="fecha-comentario">(<?php echo htmlspecialchars($respuesta['fecha_comen']); ?>)</small>
                                    <p><?php echo nl2br(htmlspecialchars($respuesta['contenido'])); ?></p>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <footer class="text-center mt-4">
        <p>&copy; 2025 Mi Sitio. Todos los derechos reservados.</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
