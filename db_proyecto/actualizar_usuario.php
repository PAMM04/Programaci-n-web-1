<?php
session_start();
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_SESSION['id_usuario'] ?? 0;
    $campo = $_POST['campo'] ?? '';
    $valor = $_POST['valor'] ?? '';

    $camposPermitidos = ['nombre', 'email', 'direccion', 'nacionalidad', 'num_telefono', 'fecha_nacimiento'];
    if (in_array($campo, $camposPermitidos)) {
        $query = "UPDATE usuario SET $campo = ? WHERE id_usuario = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("si", $valor, $idUsuario);
        $stmt->execute();
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Campo no permitido.']);
    }
    exit;
}
?>
