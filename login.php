<?php
session_start(); // Iniciar la sesión
require_once "conexion.php"; // Archivo con la conexión a la base de datos

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Usar una consulta preparada para evitar inyección SQL
        $query = "SELECT * FROM usuario WHERE email = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();
            
            // Verificar la contraseña
            if (password_verify($password, $usuario['password'])) {
                // Guardar información en la sesión
                $_SESSION['id_usuario'] = $usuario['id_usuario'];
                $_SESSION['rol_id'] = $usuario['rol_id'];
                
                // Redirigir al dashboard
                header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Usuario o contraseña incorrectos.';
            }
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    } else {
        $error = 'Por favor, complete todos los campos.';
    }
}
?>
