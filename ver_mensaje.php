<?php 
session_start();
require_once "conexion.php";

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Verificar si se ha pasado un ID de mensaje en la URL
if (isset($_GET['id'])) {
    $id_mensaje = intval($_GET['id']);
    
    // Obtener el mensaje con el ID proporcionado
    $queryMensaje = "SELECT m.id_mensaje, u.nombre AS remitente, m.contenido, m.fecha_envio, m.asunto, m.leido
                     FROM mensajes m
                     JOIN usuario u ON m.id_remitente = u.id_usuario
                     WHERE m.id_destinatario = ? AND m.id_mensaje = ?";
    $stmtMensaje = $conexion->prepare($queryMensaje);
    $stmtMensaje->bind_param("ii", $id_usuario, $id_mensaje);
    $stmtMensaje->execute();
    $resultMensaje = $stmtMensaje->get_result();
    
    if ($resultMensaje->num_rows == 0) {
        // Si no se encuentra el mensaje o el usuario no es el destinatario, redirigir
        header("Location: bandeja_entrada.php");
        exit();
    }

    // Obtener el mensaje
    $mensaje = $resultMensaje->fetch_assoc();

    // Marcar mensaje como leído si no lo está
    if ($mensaje['leido'] == 0) {
        $queryMarcarLeido = "UPDATE mensajes SET leido = 1 WHERE id_mensaje = ? AND id_destinatario = ?";
        $stmtMarcarLeido = $conexion->prepare($queryMarcarLeido);
        $stmtMarcarLeido->bind_param("ii", $id_mensaje, $id_usuario);
        $stmtMarcarLeido->execute();
    }
} else {
    // Si no se pasa un ID de mensaje, redirigir
    header("Location: bandeja_entrada.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ver Mensaje</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <a href="bandeja_entrada.php" class="btn btn-primary mb-3">Volver a la Bandeja de Entrada</a>
        <h2>Mensaje</h2>
        <div class="card">
            <div class="card-header">
                <strong>Asunto:</strong> <?= htmlspecialchars($mensaje['asunto']); ?>
            </div>
            <div class="card-body">
                <p><strong>De:</strong> <?= htmlspecialchars($mensaje['remitente']); ?></p>
                <p><strong>Fecha:</strong> <?= $mensaje['fecha_envio']; ?></p>
                <hr>
                <p><strong>Contenido:</strong></p>
                <p><?= nl2br(htmlspecialchars($mensaje['contenido'])); ?></p>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
