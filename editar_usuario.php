<?php
session_start();
require_once "conexion.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

// Verificar que el usuario tiene permisos de administrador
if ($_SESSION['rol_id'] != 1) {
    header('Location: manage_users.php');
    exit;
}

// Inicializar variables
$usuario = [
    'id_usuario' => '',
    'nombre' => '',
    'email' => '',
    'genero' => '',
    'direccion' => '',
    'nacionalidad' => '',
    'num_telefono' => '',
    'fecha_nacimiento' => '',
    'rol_id' => '',
    'estado' => ''
];

// Manejar la solicitud GET para obtener datos del usuario
if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM usuario WHERE id_usuario = ?";
    $stmt = $conexion->prepare($query);

    if (!$stmt) {
        die("Error en la consulta: " . $conexion->error);
    }

    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $usuario = $result->fetch_assoc();
    } else {
        header('Location: manage_users.php');
        exit;
    }
}

// Manejar la solicitud POST para actualizar los datos del usuario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id_usuario']);
    $nombre = trim($_POST['nombre']);
    $email = trim($_POST['email']);
    $genero = $_POST['genero'];
    $direccion = trim($_POST['direccion']);
    $nacionalidad = trim($_POST['nacionalidad']);
    $num_telefono = trim($_POST['num_telefono']);
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $estado = $_POST['estado'];
    $rol_id = intval($_POST['rol_id']);
    $motivo_suspension = isset($_POST['motivo_suspension']) ? trim($_POST['motivo_suspension']) : '';

    // Validar los datos
    if (empty($nombre) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Por favor, ingrese un nombre y un correo válido.";
        exit;
    }

    // Actualizar el estado del usuario
    $query = "UPDATE usuario 
              SET nombre = ?, email = ?, genero = ?, direccion = ?, nacionalidad = ?, 
                  num_telefono = ?, fecha_nacimiento = ?, estado = ?, rol_id = ? 
              WHERE id_usuario = ?";
    $stmt = $conexion->prepare($query);

    if (!$stmt) {
        die("Error en la consulta: " . $conexion->error);
    }

    $stmt->bind_param(
        "ssssssssii",
        $nombre, $email, $genero, $direccion, $nacionalidad,
        $num_telefono, $fecha_nacimiento, $estado, $rol_id, $id
    );
    $stmt->execute();

    // Si el estado es suspendido, agregar el registro a la tabla `suspenciones`
    if ($estado === 'suspendido' && !empty($motivo_suspension)) {
        $query = "INSERT INTO suspenciones (id_usuario, fecha_suspension, motivo) 
                  VALUES (?, NOW(), ?)";
        $stmt = $conexion->prepare($query);

        if (!$stmt) {
            die("Error al insertar en suspenciones: " . $conexion->error);
        }

        $stmt->bind_param("is", $id, $motivo_suspension);
        $stmt->execute();
    }

    header('Location: manage_users.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="forms.css">
    <title>Editar Usuario</title>
</head>
<body>
    <div class="highlight-section">
        <h1 class="card-title">Editar Usuario</h1>
        <form method="post" action="editar_usuario.php">
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($usuario['nombre']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($usuario['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="genero">Género</label>
                <select class="form-control" id="genero" name="genero" required>
                    <option value="M" <?php echo ($usuario['genero'] === 'M') ? 'selected' : ''; ?>>Masculino</option>
                    <option value="F" <?php echo ($usuario['genero'] === 'F') ? 'selected' : ''; ?>>Femenino</option>
                    <option value="O" <?php echo ($usuario['genero'] === 'O') ? 'selected' : ''; ?>>Otro</option>
                </select>
            </div>
            <div class="form-group">
                <label for="direccion">Dirección</label>
                <textarea class="form-control" id="direccion" name="direccion"><?php echo htmlspecialchars($usuario['direccion']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="nacionalidad">Nacionalidad</label>
                <input type="text" class="form-control" id="nacionalidad" name="nacionalidad" value="<?php echo htmlspecialchars($usuario['nacionalidad']); ?>">
            </div>
            <div class="form-group">
                <label for="num_telefono">Teléfono</label>
                <input type="text" class="form-control" id="num_telefono" name="num_telefono" value="<?php echo htmlspecialchars($usuario['num_telefono']); ?>">
            </div>
            <div class="form-group">
                <label for="fecha_nacimiento">Fecha de Nacimiento</label>
                <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="<?php echo htmlspecialchars($usuario['fecha_nacimiento']); ?>">
            </div>
            <div class="form-group">
                <label for="estado">Estado</label>
                <select class="form-control" id="estado" name="estado" required>
                    <option value="activo" <?php echo ($usuario['estado'] === 'activo') ? 'selected' : ''; ?>>Activo</option>
                    <option value="inactivo" <?php echo ($usuario['estado'] === 'inactivo') ? 'selected' : ''; ?>>Inactivo</option>
                    <option value="suspendido" <?php echo ($usuario['estado'] === 'suspendido') ? 'selected' : ''; ?>>Suspendido</option>
                </select>
            </div>
            <div class="form-group motivo-suspension" style="display: none;">
                <label for="motivo_suspension">Motivo de Suspensión</label>
                <textarea class="form-control" id="motivo_suspension" name="motivo_suspension"></textarea>
            </div>
            <div class="form-group">
                <label for="rol_id">Rol</label>
                <select class="form-control" id="rol_id" name="rol_id" required>
                    <option value="1" <?php echo ($usuario['rol_id'] === 1) ? 'selected' : ''; ?>>Administrador</option>
                    <option value="2" <?php echo ($usuario['rol_id'] === 2) ? 'selected' : ''; ?>>Usuario</option>
                </select>
            </div>
            <input type="hidden" name="id_usuario" value="<?php echo $usuario['id_usuario']; ?>">
            <div class="form-group-buttons">
                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                <button type="button" class="btn btn-secondary" onclick="window.location.href='manage_users.php';">Cancelar</button>
            </div>
        </form>
    </div>
    <script>
        const estadoSelect = document.getElementById('estado');
        const motivoSuspension = document.querySelector('.motivo-suspension');

        estadoSelect.addEventListener('change', () => {
            if (estadoSelect.value === 'suspendido') {
                motivoSuspension.style.display = 'block';
            } else {
                motivoSuspension.style.display = 'none';
            }
        });
    </script>
</body>
</html>
