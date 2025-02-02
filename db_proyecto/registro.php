<?php
require_once "conexion.php"; // Archivo con la conexión a la base de datos

// Verificar si los datos han sido enviados
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombres = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $genero = trim($_POST["genero"]);
    $direccion = trim($_POST["direccion"]);
    $nacionalidad = trim($_POST["nacionalidad"]);
    $num_telefono = trim($_POST["num_telefono"]);
    $fecha_nacimiento = trim($_POST["fecha_nacimiento"]);
    $rol_id = intval($_POST["rol_id"]); // Convertir a número

    // Validar campos obligatorios
    if (!empty($nombres) && !empty($email) && !empty($password) && !empty($genero) && !empty($direccion) && !empty($nacionalidad) && !empty($num_telefono) && !empty($fecha_nacimiento) && $rol_id > 0) {
        // Verificar si el usuario ya está registrado
        $query = "SELECT * FROM usuario WHERE email = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('El usuario ya está registrado. Por favor, inicia sesión.');</script>";
            echo "<script>window.location.href = 'login.php';</script>";
            exit;
        } else {
            // Hash de la contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insertar nuevo usuario
            $insert_query = "INSERT INTO usuario (nombre, email, password, genero, direccion, nacionalidad, num_telefono, fecha_nacimiento, rol_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conexion->prepare($insert_query);
            $stmt->bind_param("ssssssssi", $nombres, $email, $hashed_password, $genero, $direccion, $nacionalidad, $num_telefono, $fecha_nacimiento, $rol_id);

            if ($stmt->execute()) {
                echo "<script>alert('Usuario registrado correctamente. Por favor, inicia sesión.');</script>";
                echo "<script>window.location.href = 'login.php';</script>";
                exit;
            } else {
                echo "<script>alert('Error al registrar el usuario: " . $stmt->error . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Por favor, completa todos los campos correctamente.');</script>";
    }
}
?>
