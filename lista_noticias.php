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
        background-color: #f8f9fa; /* Blanco suave */
        color: #343a40; /* Gris oscuro para el texto */
        font-family: Arial, sans-serif;
        line-height: 1.6;
    }

    h1 {
        color: #212529; /* Negro intenso */
        margin-bottom: 10px; /* Espacio debajo del título */
        text-align: left    ;
        
    }

    .container {
        max-width: 1200px; /* Limitar el ancho máximo del contenido */
        margin: 0 auto; /* Centrar el contenido horizontalmente */
        padding: 20px; /* Espaciado interno del contenedor */
    }

    .table {
        background-color: #ffffff; /* Blanco puro para la tabla */
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        margin-top: 20px; /* Espacio superior */
    }

    .table th {
        background-color: #007bff; /* Azul profesional */
        color: #ffffff;
        text-align: center;
        padding: 15px; /* Añadir espaciado interno a las celdas del encabezado */
    }

    .table td {
        text-align: center;
        padding: 12px; /* Espaciado interno en las celdas */
        vertical-align: middle; /* Centrar verticalmente el contenido */
    }

    .btn-primary,
    .btn_dashboard {
        display: inline-block;
        font-weight: bold;
        font-size: 16px;
        padding: 10px 20px; /* Ajustar el tamaño de los botones */
        border: none;
        border-radius: 5px;
        text-align: center;
        text-decoration: none;
        transition: background-color 0.3s ease;
        margin: 10px 5px; /* Espaciado entre botones */
    }

    .btn-primary {
        background-color: #007bff; /* Azul profesional */
        color: white;
    }

    .btn-primary:hover {
        background-color: #0056b3; /* Azul más oscuro */
    }

    .btn_dashboard {
        background-color: #6c757d; /* Gris profesional */
        color: white;
    }

    .btn_dashboard:hover {
        background-color: #5a6268; /* Gris más oscuro */
    }

    .btn-container {
        display: flex;
        justify-content: space-between; /* Separar los botones */
        align-items: center;
        margin-top: 30px; /* Añadir espaciado superior */
        margin-bottom: 20px; /* Añadir espaciado inferior */
    }

    table {
        width: 100%; /* Ocupa todo el ancho del contenedor */
    }

    .table th,
    .table td {
        border: 1px solid #dee2e6; /* Bordes ligeros */
    }

    .table-hover tbody tr:hover {
        background-color: #f1f3f5; /* Color de fondo al pasar el cursor */
    }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h1>Gestión de Noticias</h1>
        <a href="subir_noticia.php" class="btn-primary">Nueva Noticia</a>
        <a href="dashboard.php" class="btn_dashboard">Volver</a>
        <table class="table table-hover">
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
