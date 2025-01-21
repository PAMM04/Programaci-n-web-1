<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION['idusuario']) || $_SESSION['rol_id'] != 1) {
    header('Location: login.php');
    exit;
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM usuarios WHERE idusuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header('Location: manage_users.php');
exit;
?>
