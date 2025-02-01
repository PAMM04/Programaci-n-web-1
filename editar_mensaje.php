<?php
session_start();
require_once "conexion.php";

// Verificar si el usuario es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol_id'] != 1) {
    header("Location: index.php");
    exit();
}

if (isset($_GET['id'])) {
    $id_mensaje = intval($_GET['id']);
    // Obtener el mensaje actual
    $queryMensaje = "SELECT * FROM mensajes WHERE id_mensaje = ? AND id_remitente = ?";
    $stmtMensaje = $conexion->prepare($queryMensaje);
    $stmtMensaje->bind_param("ii", $id_mensaje, $_SESSION['id_usuario']);
    $stmtMensaje->execute();
    $result = $stmtMensaje->get_result();
    $mensaje = $result->fetch_assoc();

    if (!$mensaje) {
        // Si el mensaje no existe o no pertenece al administrador, redirigir
        header("Location: mensajes_admins.php");
        exit();
    }

    // Si se envía el formulario para actualizar el mensaje
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['asunto'], $_POST['contenido'])) {
        $asunto = trim($_POST['asunto']);
        $contenido = trim($_POST['contenido']);
        if (!empty($asunto) && !empty($contenido)) {
            $queryActualizar = "UPDATE mensajes SET asunto = ?, contenido = ? WHERE id_mensaje = ?";
            $stmtActualizar = $conexion->prepare($queryActualizar);
            $stmtActualizar->bind_param("ssi", $asunto, $contenido, $id_mensaje);
            $stmtActualizar->execute();

            
            
            // Redirigir a la página de administración de mensajes
            header("Location: admin_mensajes.php");
            exit();
        }
    }
} else {
    // Redirigir si no se ha pasado el id del mensaje
    header("Location: mensajes_admins.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Mensaje</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Editar Mensaje</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="asunto" class="form-label">Asunto:</label>
                <input type="text" name="asunto" class="form-control" value="<?php echo htmlspecialchars($mensaje['asunto']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="contenido" class="form-label">Contenido:</label>
                <textarea name="contenido" class="form-control" rows="4" required><?php echo htmlspecialchars($mensaje['contenido']); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Mensaje</button>
        </form>
        <a href="admin_mensajes.php" class="btn btn-secondary mt-3">Volver
            <a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
