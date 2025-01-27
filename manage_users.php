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
    background-color: #f8f9fa; /* Blanco suave */
    color:rgb(255, 255, 255); /* Gris oscuro para el texto */
    font-family: Arial, sans-serif;
    line-height: 1.6;
}

h1 {
    color: #212529; /* Negro intenso */
    margin-bottom: 0px; /* Espacio debajo del título */
    text-align: left;
    
    
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
.btn_dashboard,
.btn_crear {
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

.btn_crear {
    background-color: #007bff; /* Verde profesional */
    color: white;
}

.btn_crear:hover {
    background-color: #0056b3; /* Verde más oscuro */
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
        <h1 class="mb-4">Gestión de Usuarios</h1>
        <a href="registro_admin.html" class="btn_crear">Crear Nuevo Usuario</a>
        <a href="dashboard.php" class="btn_dashboard">Volver</a>
        <table class="table table table-hover">
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