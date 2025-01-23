<?php
session_start();
require_once "conexion.php";

// Verificar si el usuario está autenticado y tiene permisos de administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol_id'] != 1) {
    header('Location: login.php');
    exit;
}

// Verificar si se recibió un ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: lista_noticias.php');
    exit;
}

$idnoticia = intval($_GET['id']);

// Obtener datos de la noticia actual
$queryNoticia = "SELECT titulo, contenido, categoria_id, destacado FROM noticias WHERE id_noticias = ?";
$stmtNoticia = $conexion->prepare($queryNoticia);

if (!$stmtNoticia) {
    die("Error en la preparación de la consulta: " . $conexion->error);
}

$stmtNoticia->bind_param("i", $idnoticia);
$stmtNoticia->execute();
$resultNoticia = $stmtNoticia->get_result();

if ($resultNoticia->num_rows === 0) {
    header('Location: lista_noticias.php');
    exit;
}

$noticia = $resultNoticia->fetch_assoc();

// Manejar el formulario de actualización
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = trim($_POST['titulo']);
    $contenido = trim($_POST['contenido']);
    $categoria_id = intval($_POST['categoria_id']);
    $destacado = isset($_POST['destacado']) ? 1 : 0;

    // Validar que todos los campos obligatorios estén presentes
    if ($titulo && $contenido && $categoria_id) {
        // Actualizar la noticia en la base de datos
        $queryActualizar = "UPDATE noticias SET titulo = ?, contenido = ?, categoria_id = ?, destacado = ? WHERE id_noticias = ?";
        $stmtActualizar = $conexion->prepare($queryActualizar);

        if ($stmtActualizar) {
            $stmtActualizar->bind_param("ssiii", $titulo, $contenido, $categoria_id, $destacado, $idnoticia);

            if ($stmtActualizar->execute()) {
                header('Location: lista_noticias.php?mensaje=noticia_actualizada');
                exit;
            } else {
                $error = "Error al actualizar la noticia: " . $stmtActualizar->error;
            }
        } else {
            $error = "Error al preparar la consulta de actualización.";
        }
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}

// Obtener las categorías para el formulario
$queryCategorias = "SELECT id_categoria, nombre FROM categoria";
$resultCategorias = $conexion->query($queryCategorias);

if (!$resultCategorias) {
    die("Error al obtener categorías: " . $conexion->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Noticia</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Noticia</h1>
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo htmlspecialchars($noticia['titulo']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="contenido" class="form-label">Contenido</label>
                <textarea class="form-control" id="contenido" name="contenido" rows="5" required><?php echo htmlspecialchars($noticia['contenido']); ?></textarea>
            </div>
            <div class="mb-3">
                <label for="categoria_id" class="form-label">Categoría</label>
                <select class="form-select" id="categoria_id" name="categoria_id" required>
                    <option value="">Seleccione una categoría</option>
                    <?php while ($categoria = $resultCategorias->fetch_assoc()): ?>
                        <option value="<?php echo $categoria['id_categoria']; ?>" 
                            <?php echo $categoria['id_categoria'] == $noticia['categoria_id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categoria['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="destacado" name="destacado" <?php echo $noticia['destacado'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="destacado">Destacar esta noticia</label>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Noticia</button>
            <a href="lista_noticias.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
