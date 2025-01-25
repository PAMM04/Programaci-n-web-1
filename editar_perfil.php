<?php
session_start();
require 'conexion.php'; // Asegúrate de que este archivo tiene tu configuración de conexión a la base de datos.

// Función para obtener los datos del usuario actual.
function obtenerUsuario($conexion, $idUsuario)
{
    $query = "SELECT * FROM usuario WHERE id_usuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_assoc();
}

// Procesar el formulario de edición.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_SESSION['id_usuario'] ?? 0; // Asegúrate de que la sesión tiene el ID del usuario.
    $nombre = $_POST['nombre'] ?? '';
    $email = $_POST['email'] ?? '';
    $direccion = $_POST['direccion'] ?? '';
    $nacionalidad = $_POST['nacionalidad'] ?? '';
    $numTelefono = $_POST['num_telefono'] ?? '';
    $fechaNacimiento = $_POST['fecha_nacimiento'] ?? '';
    $perfil = $_FILES['perfil']['name'] ?? '';

    if ($perfil) {
        $targetDir = "upload_perfil/";
        $targetFile = $targetDir . basename($perfil);
        move_uploaded_file($_FILES['perfil']['tmp_name'], $targetFile);
    } else {
        // Si no se sube una nueva foto, mantener la foto actual.
        $usuarioActual = obtenerUsuario($conexion, $idUsuario);
        $perfil = $usuarioActual['perfil'];
    }

    $query = "UPDATE usuario SET nombre = ?, email = ?, direccion = ?, nacionalidad = ?, num_telefono = ?, fecha_nacimiento = ?, perfil = ? WHERE id_usuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("sssssssi", $nombre, $email, $direccion, $nacionalidad, $numTelefono, $fechaNacimiento, $perfil, $idUsuario);
    $stmt->execute();

    header("Location: dashboard.php");
    exit;
}

// Obtener los datos del usuario para mostrar en el formulario.
$usuario = obtenerUsuario($conexion, $_SESSION['id_usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
<nav class="navbar">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Editar Perfil</a>
    </div>
</nav>
<div class="container mt-4">
    <form action="editar_perfil.php" method="POST" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($usuario['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <textarea class="form-control" id="direccion" name="direccion" required><?= htmlspecialchars($usuario['direccion']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="nacionalidad" class="form-label">Nacionalidad</label>
            <input type="text" class="form-control" id="nacionalidad" name="nacionalidad" value="<?= htmlspecialchars($usuario['nacionalidad']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="num_telefono" class="form-label">Número de Teléfono</label>
            <input type="text" class="form-control" id="num_telefono" name="num_telefono" value="<?= htmlspecialchars($usuario['num_telefono']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= htmlspecialchars($usuario['fecha_nacimiento']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="perfil" class="form-label">Foto de Perfil</label>
            <input type="file" class="form-control" id="perfil" name="perfil">
            <?php if (!empty($usuario['perfil'])): ?>
                <img src="upload_perfil/<?= htmlspecialchars($usuario['perfil']); ?>" alt="Foto de perfil" class="perfil mt-2" width="150">
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
