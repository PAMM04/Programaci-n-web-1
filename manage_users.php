<?php
session_start();
require_once "conexion.php";

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

$idusuario = $_SESSION['id_usuario'];
$query = "SELECT rol_id FROM usuario WHERE id_usuario = ?";
$stmt = $conexion->prepare($query);
$stmt->bind_param("i", $idusuario);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $usuario = $result->fetch_assoc();
    if ($usuario['rol_id'] != 1) {
        header('Location: dashboard.php');
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}

// Obtener la lista de usuarios
$queryUsuarios = "SELECT id_usuario, nombre, email, rol_id FROM usuario";
$resultUsuarios = $conexion->query($queryUsuarios);

// Obtener la lista de noticias
$queryNoticias = "SELECT id_noticias, titulo, SUBSTRING(contenido, 1, 100) AS resumen, fecha_creacion FROM noticias";
$resultNoticias = $conexion->query($queryNoticias);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios y Noticias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color:rgb(201, 201, 201);
            color: #fff;
        }
        .table-dark th, .table-dark td {
            color: #fff;
        }
        .btn-primary, .btn-danger, .btn-warning {
            border: none;
        }

        .btn_dashboard {
            font-weight: bold;
            background-color: #fc4a61; /* Color rojo sandía */
            color: white;
            font-size: 16px;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            text-decoration: none; /* Eliminar subrayado si se coloca en un enlace */
            transition: background-color 0.3s ease;
            margin-top: 20px; /* Espacio por encima del botón */
        }

        .btn_dashboard:hover {
            background-color: #f82e47; /* Rojo más oscuro al pasar el cursor */
        }

        .btn_crear {
            font-weight: bold;
            background-color:#212529; /* Color verde */
            color: white;
            font-size: 16px;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-align: center;
            text-decoration: none; /* Eliminar subrayado si se coloca en un enlace */
            transition: background-color 0.3s ease;
            margin-top: 20px; /* Espacio por encima del botón */
        }

        .btn_crear:hover {
            background-color:rgb(90, 90, 90); /* Verde más oscuro al pasar el cursor */
        }

        /* Alineación centrada del botón */
        .btn-container {
            display: flex;
            margin: 25px;
            margin-top: 30px; /* Espacio entre formulario y botón */
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Gestión de Usuarios</h1>
        <a href="registro_admin.html" class="btn_crear">Crear Nuevo Usuario</a>
        <a href="dashboard.php" class="btn_dashboard">Volver</a>
        
        <h2 class="mt-5">Usuarios</h2>
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $resultUsuarios->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_usuario']; ?></td>
                    <td><?php echo htmlspecialchars($row['nombre']); ?></td>
                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                    <td><?php echo $row['rol_id'] == 1 ? 'Administrador' : 'Usuario'; ?></td>
                    <td>
                        <a href="editar_usuario.php?id=<?php echo $row['id_usuario']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="eliminar_usuario.php?id=<?php echo $row['id_usuario']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?');">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>