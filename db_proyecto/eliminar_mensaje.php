<?php
session_start();
require_once "conexion.php";

// Verificar si el usuario es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol_id'] != 1) {
    header("Location: index.php");
    exit();
}

// Verificar si se ha enviado el id del mensaje
if (isset($_GET['id'])) {
    $id_mensaje = intval($_GET['id']);
    
    // Eliminar el mensaje de la base de datos
    $queryEliminar = "DELETE FROM mensajes WHERE id_mensaje = ? AND id_remitente = ?";
    $stmtEliminar = $conexion->prepare($queryEliminar);
    $stmtEliminar->bind_param("ii", $id_mensaje, $_SESSION['id_usuario']);
    $stmtEliminar->execute();

    // Redirigir a mensajes_admins.php con mensaje de éxito
    header("Location: admin_mensajes.php?mensaje=eliminado");
    exit();
} else {
    // Redirigir si no se ha pasado el id del mensaje
    header("Location: mensajes_admins.php");
    exit();
}
?>
