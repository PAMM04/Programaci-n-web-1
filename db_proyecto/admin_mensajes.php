<?php 
session_start();
require_once "conexion.php";

// Verificar si el usuario es administrador
if (!isset($_SESSION['id_usuario']) || $_SESSION['rol_id'] != 1) {
    header("Location: index.php");
    exit();
}

// Eliminar mensaje
if (isset($_GET['eliminar_id'])) {
    $id_mensaje = intval($_GET['eliminar_id']);
    $queryEliminar = "DELETE FROM mensajes WHERE id_mensaje = ? AND id_remitente = ?";
    $stmtEliminar = $conexion->prepare($queryEliminar);
    $stmtEliminar->bind_param("ii", $id_mensaje, $_SESSION['id_usuario']);
    $stmtEliminar->execute();
    header("Location: mensajes_admins.php?mensaje=eliminado"); // Redirigir después de la eliminación
    exit();
}

// Editar mensaje
if (isset($_GET['editar_id'])) {
    $id_mensaje = intval($_GET['editar_id']);
    $queryMensaje = "SELECT * FROM mensajes WHERE id_mensaje = ? AND id_remitente = ?";
    $stmtMensaje = $conexion->prepare($queryMensaje);
    $stmtMensaje->bind_param("ii", $id_mensaje, $_SESSION['id_usuario']);
    $stmtMensaje->execute();
    $result = $stmtMensaje->get_result();
    $mensaje = $result->fetch_assoc();

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['asunto'], $_POST['contenido'])) {
        $asunto = trim($_POST['asunto']);
        $contenido = trim($_POST['contenido']);
        if (!empty($asunto) && !empty($contenido)) {
            $queryActualizar = "UPDATE mensajes SET asunto = ?, contenido = ? WHERE id_mensaje = ?";
            $stmtActualizar = $conexion->prepare($queryActualizar);
            $stmtActualizar->bind_param("ssi", $asunto, $contenido, $id_mensaje);
            $stmtActualizar->execute();
            header("Location: admin_mensajes.php?mensaje=actualizado"); // Redirigir después de la actualización
            exit();
        }
    }
}

// Obtener la lista de todos los usuarios
$queryUsuarios = "SELECT id_usuario, nombre FROM usuario ORDER BY nombre";
$resultUsuarios = $conexion->query($queryUsuarios);


// Enviar mensaje
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_destinatario'], $_POST['contenido'], $_POST['asunto'])) {
    $remitente_id = $_SESSION['id_usuario'];
    $destinatario_id = intval($_POST['id_destinatario']);
    $contenido = trim($_POST['contenido']);
    $asunto = trim($_POST['asunto']);
    
    if (!empty($contenido) && !empty($asunto)) {
        $queryMensaje = "INSERT INTO mensajes (id_remitente, id_destinatario, contenido, asunto) VALUES (?, ?, ?, ?)";
        $stmtMensaje = $conexion->prepare($queryMensaje);
        $stmtMensaje->bind_param("iiss", $remitente_id, $destinatario_id, $contenido, $asunto);
        $stmtMensaje->execute();
    }
}

// Obtener mensajes enviados
$queryMensajes = "SELECT m.id_mensaje, u.nombre AS destinatario, m.contenido, m.fecha_envio, m.asunto 
                  FROM mensajes m 
                  JOIN usuario u ON m.id_destinatario = u.id_usuario 
                  WHERE m.id_remitente = ? 
                  ORDER BY m.fecha_envio DESC";
$stmtMensajes = $conexion->prepare($queryMensajes);
$stmtMensajes->bind_param("i", $_SESSION['id_usuario']);
$stmtMensajes->execute();
$resultMensajes = $stmtMensajes->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Mensajes</title>
    <link rel="stylesheet" href="mensajes.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script>
        // Función para ocultar el mensaje después de 3 segundos
        window.onload = function() {
            const mensaje = document.getElementById('mensajeExito');
            if (mensaje) {
                setTimeout(function() {
                    mensaje.style.display = 'none'; // Ocultar el mensaje después de 3 segundos
                }, 3000); // 3000ms = 3 segundos
            }
        };
    </script>
</head>
<body>
    <div class="container mt-4">
        <!-- Mensaje de éxito para mensaje actualizado o eliminado -->
        <?php if (isset($_GET['mensaje'])): ?>
            <?php if ($_GET['mensaje'] == 'eliminado'): ?>
                <div id="mensajeExito" class="alert alert-success" role="alert">¡Mensaje eliminado con éxito!</div>
            <?php elseif ($_GET['mensaje'] == 'actualizado'): ?>
                <div id="mensajeExito" class="alert alert-success" role="alert">¡Mensaje actualizado con éxito!</div>
            <?php endif; ?>
        <?php endif; ?>
 <!-- Botón Volver al Dashboard -->
 <a href="dashboard.php" class="btn btn-primary mb-3">Volver al Dashboard</a>
        <h2>Enviar Mensaje</h2>
        <form method="POST">
            <div class="mb-3">
                <label for="destinatario" class="form-label">Seleccionar Usuario:</label>
                <select name="id_destinatario" class="form-control" required>
                    <?php while ($usuario = $resultUsuarios->fetch_assoc()): ?>
                        <option value="<?php echo $usuario['id_usuario']; ?>">
                            <?php echo htmlspecialchars($usuario['nombre']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="asunto" class="form-label">Asunto:</label>
                <input type="text" name="asunto" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="contenido" class="form-label">Mensaje:</label>
                <textarea name="contenido" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>

        <h2 class="mt-4">Mensajes Enviados</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Destinatario</th>
                    <th>Asunto</th>
                    <th>Mensaje</th>
                    <th>Fecha</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($mensaje = $resultMensajes->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($mensaje['destinatario']); ?></td>
                        <td><?php echo htmlspecialchars($mensaje['asunto']); ?></td>
                        <td><?php echo htmlspecialchars(substr($mensaje['contenido'], 0, 50)); ?>...</td>
                        <td><?php echo $mensaje['fecha_envio']; ?></td>
                        <td>
                            <a href="editar_mensaje.php?id=<?php echo $mensaje['id_mensaje']; ?>" class="btn btn-sm btn-warning">Editar</a>

                            <a href="eliminar_mensaje.php?id=<?php echo $mensaje['id_mensaje']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que deseas eliminar este mensaje?')">Eliminar</a>

                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <?php if (isset($_GET['editar_id'])): ?>
            <?php if (isset($mensaje)): ?>
                <h3>Editar Mensaje</h3>
                <form method="POST">
                    <div class="mb-3">
                        <label for="asunto" class="form-label">Asunto:</label>
                        <input type="text" name="asunto" class="form-control" value="<?php echo htmlspecialchars($mensaje['asunto']); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="contenido" class="form-label">Mensaje:</label>
                        <textarea name="contenido" class="form-control" rows="4" required><?php echo htmlspecialchars($mensaje['contenido']); ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
