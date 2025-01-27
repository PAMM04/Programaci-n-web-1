<?php
session_start();
require_once "conexion.php";

// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    // Redirigir al inicio de sesión si no está autenticado
    header('Location: login.php');
    exit;
}

// Obtener información del usuario autenticado
$idusuario = $_SESSION['id_usuario'];
$queryUsuario = "SELECT nombre, email, rol_id FROM usuario WHERE id_usuario = ?";
$stmtUsuario = $conexion->prepare($queryUsuario);
$stmtUsuario->bind_param("i", $idusuario);
$stmtUsuario->execute();
$resultUsuario = $stmtUsuario->get_result();
$usuario = $resultUsuario->fetch_assoc();

// Cargar los permisos del rol del usuario
$queryPermisos = "
    SELECT p.nombre 
    FROM permisos p
    INNER JOIN roles_permisos rp ON p.id_permisos = rp.id_permisos
    WHERE rp.id_rol = ?";
$stmtPermisos = $conexion->prepare($queryPermisos);
$stmtPermisos->bind_param("i", $usuario['rol_id']);
$stmtPermisos->execute();
$resultPermisos = $stmtPermisos->get_result();

$permisos = [];
while ($row = $resultPermisos->fetch_assoc()) {
    $permisos[] = $row['nombre'];
}

// Validar permisos específicos

$puedeGestionarNoticias =  in_array('crear_noticia', $permisos);

if (!$puedeGestionarNoticias) {
    header('Location: dashboard.php');
    exit;
}

// Obtener las noticias de la base de datos
$query = "SELECT n.id_noticias, n.titulo, n.fecha_creacion, n.destacado, c.nombre AS categoria 
          FROM noticias n
          LEFT JOIN categoria c ON n.categoria_id = c.id_categoria
          ORDER BY n.fecha_creacion DESC";
$result = $conexion->query($query);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Noticias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
         body {
            background-color:rgb(201, 201, 201);

        }
        .table-dark th, .table-dark td {
            color: #fff;
        }
        .btn-primary {
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

        .btn-primary:hover {
            background-color:rgb(90, 90, 90); /* Verde más oscuro al pasar el cursor */
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
        <h1>Noticias</h1>
        <a href="subir_noticia.php" class="btn-primary">Nueva Noticia</a>
        <a href="dashboard.php" class="btn_dashboard">Volver</a>
        <h2>.</h2>
        <table class="table table-dark table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Destacado</th>
                    <th>Fecha de Publicación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($noticia = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $noticia['id_noticias']; ?></td>
                    <td><?php echo htmlspecialchars($noticia['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($noticia['categoria'] ?? 'Sin categoría'); ?></td>
                    <td><?php echo $noticia['destacado'] ? 'Sí' : 'No'; ?></td>
                    <td><?php echo $noticia['fecha_creacion']; ?></td>
                    <td>
                        <a href="editar_noticia.php?id=<?php echo $noticia['id_noticias']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="eliminar_noticia.php?id=<?php echo $noticia['id_noticias']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar esta noticia?');">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
