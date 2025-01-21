<?php
require_once "conexion.php"; // Archivo con la conexión a la base de datos

// Verificar si los datos han sido enviados
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombres = trim($_POST["nombre"]);
    $email = trim($_POST["email"]);
    $password = trim($_POST["password"]);
    $rol_id = intval($_POST["rol_id"]); // Convertir a número

    // Validar campos obligatorios
    if (!empty($nombres) && !empty($email) && !empty($password) && $rol_id > 0) {
        // Verificar si el usuario ya está registrado
        $query = "SELECT * FROM usuarios WHERE email = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<script>alert('El usuario ya está registrado. Por favor, inicia sesión.');</script>";
            
        } else {
            // Hash de la contraseña
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insertar nuevo usuario
            $insert_query = "INSERT INTO usuarios (nombre, email, password, rol_id) VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($insert_query);
            $stmt->bind_param("ssss", $nombres, $email, $hashed_password, $rol_id);

            if ($stmt->execute()) {
                echo "<script>alert('Usuario registrado correctamente. Por favor, inicia sesión.');</script>";
                
            } else {
                echo "<script>alert('Error al registrar el usuario: " . $stmt->error . "');</script>";
            }
        }
    } else {
        echo "<script>alert('Por favor, completa todos los campos correctamente.');</script>";
    }
}
?>
