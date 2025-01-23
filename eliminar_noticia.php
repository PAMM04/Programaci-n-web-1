<?php
session_start();
require_once "conexion.php";

// Verificar si el usuario está autenticado y tiene permisos de administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol_id'] != 1) {
    header('Location: login.php');
    exit;
}

// Verificar si se recibió el ID de la noticia
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: lista_noticias.php');
    exit;
}

$idnoticia = intval($_GET['id']);

// Eliminar la noticia de la base de datos
$queryEliminar = "DELETE FROM noticias WHERE id_noticias = ?";
$stmtEliminar = $conexion->prepare($queryEliminar);

if ($stmtEliminar) {
    $stmtEliminar->bind_param("i", $idnoticia);

    if ($stmtEliminar->execute()) {
        header('Location: lista_noticias.php?mensaje=noticia_eliminada');
        exit;
    } else {
        die("Error al eliminar la noticia: " . $stmtEliminar->error);
    }
} else {
    die("Error al preparar la consulta: " . $conexion->error);
}
?>
