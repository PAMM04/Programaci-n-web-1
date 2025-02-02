<?php
session_start();
require 'conexion.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    echo json_encode(['success' => false, 'message' => 'No autorizado.']);
    exit;
}

// Obtener los datos enviados
$data = json_decode(file_get_contents('php://input'), true);

if (!empty($data['id_usuario'])) {
    $id_usuario = $data['id_usuario'];

    // Actualizar el estado del usuario en la base de datos
    $query = "UPDATE usuario SET estado = 'inactivo' WHERE id_usuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id_usuario);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Cuenta desactivada exitosamente.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al desactivar la cuenta.']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
}

$conexion->close();
?>
