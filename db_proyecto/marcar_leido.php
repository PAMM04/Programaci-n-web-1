<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION['id_usuario']) || !isset($_GET['id'])) {
    header("Location: bandeja_entrada.php");
    exit();
}

$id_usuario = $_SESSION['id_usuario'];
$id_mensaje = intval($_GET['id']);

// Marcar mensaje como leído
$queryUpdate = "UPDATE mensajes SET leido = TRUE WHERE id_mensaje = ? AND id_usuario = ?";
$stmtUpdate = $conexion->prepare($queryUpdate);
$stmtUpdate->bind_param("ii", $id_mensaje, $id_usuario);
$stmtUpdate->execute();

header("Location: bandeja_entrada.php");
exit();
?>
