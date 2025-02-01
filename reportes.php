<?php
session_start();
require_once "conexion.php";
require_once "fpdf.php"; // Incluir FPDF

// Obtener usuario autenticado
function obtenerUsuario($conexion, $idUsuario) {
    $query = "SELECT id_usuario, nombre, email, rol_id FROM usuario WHERE id_usuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}

// Obtener permisos del usuario
function obtenerPermisos($conexion, $rolId) {
    $query = "SELECT p.nombre FROM permisos p
              INNER JOIN roles_permisos rp ON p.id_permisos = rp.id_permisos
              WHERE rp.id_rol = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $rolId);
    $stmt->execute();
    $result = $stmt->get_result();
    $permisos = [];
    while ($row = $result->fetch_assoc()) {
        $permisos[] = $row['nombre'];
    }
    return $permisos;
}

// Verificar permisos
$usuario = isset($_SESSION['id_usuario']) ? obtenerUsuario($conexion, $_SESSION['id_usuario']) : null;
$puedeVerReportes = $usuario && in_array('ver_reportes', obtenerPermisos($conexion, $usuario['rol_id']));

if (!$puedeVerReportes) {
    die("Acceso denegado");
}

// Filtros de fechas
$fechaInicio = $_GET['fecha_inicio'] ?? date('Y-m-01');
$fechaFin = $_GET['fecha_fin'] ?? date('Y-m-d');

// Obtener datos de reportes
$query = "SELECT id_noticias, titulo, fecha_creacion FROM noticias WHERE fecha_creacion BETWEEN ? AND ? ORDER BY fecha_creacion DESC";
$stmt = $conexion->prepare($query);
$stmt->bind_param("ss", $fechaInicio, $fechaFin);
$stmt->execute();
$result = $stmt->get_result();

// Exportar CSV
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="reporte.csv"');
    $output = fopen("php://output", "w");
    fputcsv($output, ["ID", "Título", "Fecha"]);
    while ($row = $result->fetch_assoc()) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit;
}

// Exportar PDF
if (isset($_GET['export']) && $_GET['export'] == 'pdf') {
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 10, 'ID');
    $pdf->Cell(100, 10, 'Título');
    $pdf->Cell(40, 10, 'Fecha', 0, 1);
    $pdf->SetFont('Arial', '', 10);
    while ($row = $result->fetch_assoc()) {
        $pdf->Cell(40, 10, $row['id_noticias']);
        $pdf->Cell(100, 10, $row['titulo']);
        $pdf->Cell(40, 10, $row['fecha_creacion'], 0, 1);
    }
    $pdf->Output("D", "reporte.pdf");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Reportes de Noticias</h2>
    <form method="GET" class="row g-3">
        <div class="col-md-4">
            <label>Fecha Inicio</label>
            <input type="date" name="fecha_inicio" class="form-control" value="<?php echo $fechaInicio; ?>">
        </div>
        <div class="col-md-4">
            <label>Fecha Fin</label>
            <input type="date" name="fecha_fin" class="form-control" value="<?php echo $fechaFin; ?>">
        </div>
        <div class="col-md-4">
            <button type="submit" class="btn btn-primary mt-4">Generar Reporte</button>
        </div>
    </form>
    <a href="?fecha_inicio=<?php echo $fechaInicio; ?>&fecha_fin=<?php echo $fechaFin; ?>&export=csv" class="btn btn-success mt-3">Exportar CSV</a>
    <a href="?fecha_inicio=<?php echo $fechaInicio; ?>&fecha_fin=<?php echo $fechaFin; ?>&export=pdf" class="btn btn-danger mt-3">Exportar PDF</a>
    <table class="table mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Título</th>
                <th>Fecha</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_noticias']; ?></td>
                    <td><?php echo htmlspecialchars($row['titulo']); ?></td>
                    <td><?php echo $row['fecha_creacion']; ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
