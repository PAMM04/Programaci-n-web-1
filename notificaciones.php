<?php

require_once "conexion.php"; // Verificar que el archivo está en la ubicación correcta

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['id_usuario'])) {
    echo '<span class="badge bg-secondary">0</span>'; // Mostrar 0 si no está autenticado
    exit;
}

$idusuario = $_SESSION['id_usuario'];

// Consulta para contar los mensajes no leídos
$queryNotificaciones = "SELECT COUNT(*) AS total FROM mensajes WHERE id_destinatario = ? AND leido = 0";
$stmtNotificaciones = $conexion->prepare($queryNotificaciones);

if (!$stmtNotificaciones) {
    die("Error en la preparación de la consulta: " . $conexion->error);
}

$stmtNotificaciones->bind_param("i", $idusuario);
$stmtNotificaciones->execute();
$resultNotificaciones = $stmtNotificaciones->get_result();

$totalMensajesNoLeidos = 0;
if ($row = $resultNotificaciones->fetch_assoc()) {
    $totalMensajesNoLeidos = $row['total'];
}

// Mostrar el número de mensajes no leídos
if ($totalMensajesNoLeidos > 0) {
    echo '<span class="badge bg-danger">' . $totalMensajesNoLeidos . '</span>';
} else {
    echo '<span class="badge bg-secondary">0</span>';
}

$stmtNotificaciones->close();
$conexion->close();
?>
