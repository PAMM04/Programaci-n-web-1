<?php
require_once "conexion.php"; // Archivo que conecta con la base de datos

$sql = "SELECT p.*, r.nombre AS Rol FROM usuarios p JOIN roles r ON p.rol_id = r.idrol;"; 
$query = $conexion->query($sql);

if ($query && $query->num_rows > 0) { 
    echo "<table border='1' cellspacing='0' cellpadding='5'>";

    // Obtener las columnas dinámicamente
    $columns = array_keys($query->fetch_assoc());
    $query->data_seek(0); // Regresar el puntero al inicio para iterar de nuevo

    // Mostrar encabezados dinámicamente
    echo "<tr>";
    foreach ($columns as $column) {
        echo "<th>" . htmlspecialchars($column) . "</th>";
    }
    echo "</tr>";

    // Mostrar filas dinámicamente
    while ($row = $query->fetch_assoc()) { 
        echo "<tr>";
        foreach ($row as $value) {
            echo "<td>" . htmlspecialchars($value) . "</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
} else { 
    echo "No hay resultados.";
}

// Cerrar la conexión
$conexion->close();
?>
