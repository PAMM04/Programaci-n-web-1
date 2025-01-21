<?php
session_start();
require_once "conexion.php";

if (!isset($_SESSION['idusuario']) || $_SESSION['rol_id'] != 1) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM usuarios WHERE idusuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    } else {
        header('Location: manage_users.php');
        exit;
    }
} elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['idusuario']);
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $rol_id = intval($_POST['rol_id']);

    $query = "UPDATE usuarios SET nombre = ?, email = ?, rol_id = ? WHERE idusuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("ssii", $nombre, $email, $rol_id, $id);
    $stmt->execute();

    header('Location: manage_users.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Editar Usuario</h1>
        <form method="POST" action="editar_usuario.php">
            <input type="hidden" name="idusuario" value="<?php echo $usuario['idusuario']; ?>">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="rol_id" class="form-label">Rol</label>
                <select class="form-select" id="rol_id" name="rol_id" required>
                    <option value="1" <?php echo $usuario['rol_id'] == 1 ? 'selected' : ''; ?>>Administrador</option>
                    <option value="2" <?php echo $usuario['rol_id'] == 2 ? 'selected' : ''; ?>>Usuario</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="manage_users.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
