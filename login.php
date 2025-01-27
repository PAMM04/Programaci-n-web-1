<?php
session_start();
require_once "conexion.php"; // Archivo con la conexión a la base de datos

// Inicializamos la variable de error
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    if (!empty($email) && !empty($password)) {
        // Consulta preparada para evitar inyección SQL
        $query = "SELECT * FROM usuario WHERE email = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $usuario = $result->fetch_assoc();

            // Verificar si el usuario está activo
            if ($usuario['estado'] === 'activo') {
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
                $error = 'El usuario no está activo. Por favor, contacte al administrador.';
            }
        } else {
            $error = 'Usuario o contraseña incorrectos.';
        }
    } else {
        $error = 'Por favor, complete todos los campos.';
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="forms.css">
</head>
<body>
    <div class="highlight-section">
        <h2 class="card-title">Inicio de Sesión</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Escribe tu correo electrónico" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Escribe tu contraseña" required>
            </div>
            <div class="form-group-buttons">
                <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
            </div>
        </form>
        <div class="text-center mt-3">
            <h5>¿No tienes una cuenta? 
                <a href="registro.html" class="text-primary" style="text-decoration: none; font-weight: bold;">Regístrate</a>
            </h5>
        </div>
    </div>

    <!-- Mostrar alerta si hay un error -->
    <?php if (!empty($error)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?= $error ?>',
                    confirmButtonColor: '#007bff',
                });
            });
        </script>
    <?php endif; ?>

</body>
</html>
