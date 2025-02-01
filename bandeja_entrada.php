<?php
session_start();
require_once "conexion.php";

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    header("Location: login.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Obtener mensajes recibidos
$queryMensajes = "SELECT m.id_mensaje, u.nombre AS remitente, m.contenido, m.fecha_envio, m.asunto, m.leido
                  FROM mensajes m
                  JOIN usuario u ON m.id_remitente = u.id_usuario
                  WHERE m.id_destinatario = ? 
                  ORDER BY m.fecha_envio DESC";
$stmtMensajes = $conexion->prepare($queryMensajes);
$stmtMensajes->bind_param("i", $id_usuario);
$stmtMensajes->execute();
$resultMensajes = $stmtMensajes->get_result();

// Marcar mensaje como leído
if (isset($_GET['id'])) {
    $id_mensaje = intval($_GET['id']);
    
    // Actualizar el estado del mensaje a "leído"
    $queryMarcarLeido = "UPDATE mensajes SET leido = 1 WHERE id_mensaje = ? AND id_destinatario = ?";
    $stmtMarcarLeido = $conexion->prepare($queryMarcarLeido);
    $stmtMarcarLeido->bind_param("ii", $id_mensaje, $id_usuario);
    $stmtMarcarLeido->execute();

    // Redirigir para evitar reenvíos en el refresco
    header("Location: bandeja_entrada.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bandeja de Entrada</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="bandeja.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
    <a href="dashboard.php" class="btn btn-primary mb-3">Volver al Dashboard</a>
        <h2>Bandeja de Entrada</h2>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>Enviado por</th>
                    <th>Asunto</th>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Ver</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($mensaje = $resultMensajes->fetch_assoc()): ?>
                    <tr class="<?= $mensaje['leido'] ? 'table-light' : 'table-warning'; ?>">
                        <td><strong><?= htmlspecialchars($mensaje['remitente']); ?></strong></td>
                        <td><?= htmlspecialchars($mensaje['asunto']); ?></td>
                        <td><?= htmlspecialchars(substr($mensaje['contenido'], 0, 50)); ?>...</td>
                        <td><?= $mensaje['fecha_envio']; ?></td>
                        <td>
                            <?php if (!$mensaje['leido']): ?>
                                <a href="bandeja_entrada.php?id=<?= $mensaje['id_mensaje']; ?>" class="btn btn-sm btn-success">Marcar como leído</a>
                            <?php else: ?>
                                <span class="text-muted">Leído</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="ver_mensaje.php?id=<?= $mensaje['id_mensaje']; ?>" class="btn btn-sm btn-info">Ver mensaje</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
