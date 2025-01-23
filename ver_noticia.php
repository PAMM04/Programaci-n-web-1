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
    $queryEliminarComentario = "DELETE FROM comentarios WHERE idcomentario = ?";
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
    $comentarioPadre = isset($_POST['comentario_padre']) ? intval($_POST['comentario_padre']) : null;

    // Validación básica del comentario
    if (empty($comentario)) {
        $mensaje = "El comentario no puede estar vacío.";
    } else {
        $queryComentario = "INSERT INTO comentarios (id_noticia, id_usuario, id_comentario_padre, contenido, estado) 
                            VALUES (?, ?, ?, ?, ?)";
        $stmtComentario = $conexion->prepare($queryComenario);

        if ($stmtComenario) {
            // Los parámetros deben coincidir en número y tipo con los "?" en la consulta
            // i: integer, s: string
            $estado = 'activo'; // Definir el estado como una variable para mayor claridad
            $stmtComenario->bind_param("iiiss", $idnoticia, $idusuario, $comentarioPadre, $comentario, $estado);
            if ($stmtComenario->execute()) {
                // Cerrar la sentencia preparada después de ejecutarla es buena práctica
                $stmtComenario->close();
                $mensaje = "Comentario enviado para revisión.";
            } else {
                // Manejar el error específico que ocurrió durante la ejecución
                $mensaje = "Error al enviar el comentario: " . mysqli_error($conexion);
            }
        } else {
            // Manejar el error específico que ocurrió al preparar la consulta
            $mensaje = "Error al preparar la consulta de comentario: " . mysqli_error($conexion);
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
                     WHERE c.id_noticia = ? AND c.estado = 'acrtivo'
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
    if ($comentario['idcomentario_padre'] === null) {
        $comentarios[$comentario['idcomentario']] = [
            'info' => $comentario,
            'respuestas' => []
        ];
    } else {
        $comentarios[$comentario['idcomentario_padre']]['respuestas'][] = $comentario;
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
    <style>
        .fecha-comentario {
            color: white;
        }
        
        <style>
    /* Contenedor de la imagen para mantener proporciones */
    .imagen-noticia {
        width: 80%; /* Ocupa el ancho completo del contenedor */
        max-width: 400px; /* Tamaño máximo de ancho */
        height: 200px; /* Altura fija */
        object-fit: cover; /* Recorta la imagen si es necesario */
        object-position: center; /* Centra la imagen dentro del contenedor */
        display: block; /* Asegura que se comporte como un bloque */
        margin: 0 auto; /* Centra la imagen horizontalmente */
        border-radius: 10px; /* Esquinas redondeadas opcionales */
    }

    /* Estilo adicional para los contenedores de las noticias relacionadas */
    .imagen-relacionada {
        width: 100%;
        height: 150px; /* Tamaño más pequeño para noticias relacionadas */
        object-fit: cover;
        object-position: center;
        border-radius: 5px; /* Esquinas redondeadas opcionales */
    }

    /* Ajustes generales para mantener consistencia */
    .card-img-top {
        width: 100%;
        height: auto; /* Asegura que las imágenes no se distorsionen */
    }
</style>

    </style>
</head>
<body class="bg-dark text-light">
    <div class="container mt-5">
        <h1><?php echo htmlspecialchars($noticia['titulo']); ?></h1>
        <p class="text-muted">Publicado el: <?php echo $noticia['fecha_creacion']; ?></p>
        
        <?php if ($noticia['imagen']): ?>
            <img src="<?php echo htmlspecialchars($noticia['imagen']); ?>" class="imagen-noticia" alt="Imagen de la noticia">
        <?php endif; ?>
        
        <p><?php echo nl2br(htmlspecialchars($noticia['contenido'])); ?></p>
        <hr>

        <h2>Comentarios</h2>
        <?php if (isset($mensaje)): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($mensaje); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="comentario" class="form-label">Deja tu comentario</label>
                <textarea class="form-control" id="comentario" name="comentario" rows="3" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar comentario</button>
        </form>
        <hr>
        <ul class="list-unstyled">
            <?php foreach ($comentarios as $comentarioId => $data): ?>
                <li class="mb-4">
                    <strong><?php echo htmlspecialchars($data['info']['autor']); ?></strong> 
                    <small class="fecha-comentario">(<?php echo $data['info']['fecha']; ?>)</small>
                    <p><?php echo nl2br(htmlspecialchars($data['info']['contenido'])); ?></p>
                    <?php if ($esAdministrador): ?>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="eliminar_comentario" value="<?php echo $data['info']['idcomentario']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                        </form>
                    <?php endif; ?>
                    <form method="POST" class="ms-4">
                        <input type="hidden" name="comentario_padre" value="<?php echo $comentarioId; ?>">
                        <div class="mb-3">
                            <textarea class="form-control" name="comentario" rows="2" placeholder="Responder a este comentario" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-secondary btn-sm">Responder</button>
                    </form>
                    <?php if (!empty($data['respuestas'])): ?>
                        <ul class="list-unstyled ms-4">
                            <?php foreach ($data['respuestas'] as $respuesta): ?>
                                <li>
                                    <strong><?php echo htmlspecialchars($respuesta['autor']); ?></strong> 
                                    <small class="fecha-comentario">(<?php echo $respuesta['fecha']; ?>)</small>
                                    <p><?php echo nl2br(htmlspecialchars($respuesta['contenido'])); ?></p>
                                    <?php if ($esAdministrador): ?>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="eliminar_comentario" value="<?php echo $respuesta['idcomentario']; ?>">
                                            <button type="submit" class="btn btn-danger btn-sm">Eliminar</button>
                                        </form>
                                    <?php endif; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <hr>
        <h2>Noticias Relacionadas</h2>
        <div class="row">
            <?php while ($relacionada = $resultRelacionadas->fetch_assoc()): ?>
            <div class="col-md-4 mb-3">
                <div class="card bg-secondary text-light h-100">
                    <?php if ($relacionada['imagen']): ?>
                        <img src="<?php echo htmlspecialchars($relacionada['imagen']); ?>" class="imagen-relacionada" alt="Imagen de la noticia relacionada">
                    <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($relacionada['titulo']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($relacionada['resumen']); ?>...</p>
                        <a href="ver_noticia.php?id=<?php echo $relacionada['id_noticias']; ?>" class="btn btn-primary">Leer más</a>
                    </div>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
        <a href="dashboard.php" class="btn btn-secondary mt-4">Volver al Dashboard</a>
    </div>
</body>
</html>
