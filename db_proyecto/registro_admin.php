<?php
session_start();
require_once "conexion.php";

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol_id'] != 1) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $genero = $_POST['genero'];
    $direccion = $_POST['direccion'];
    $nacionalidad = $_POST['nacionalidad'];
    $num_telefono = $_POST['num_telefono'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $rol_id = $_POST['rol_id'];

    // Insertar el nuevo usuario en la base de datos
    $query = "INSERT INTO usuario (nombre, email, password, genero, direccion, nacionalidad, num_telefono, fecha_nacimiento, rol_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ssssssssi", $nombre, $email, $password, $genero, $direccion, $nacionalidad, $num_telefono, $fecha_nacimiento, $rol_id);

    if ($stmt->execute()) {
        header('Location: manage_users.php');
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>