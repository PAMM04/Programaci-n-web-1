<?php
require_once "conexion.php";

// Consultar datos de ejemplo.
$query = "SELECT idusuario AS id, nombre, rol_id AS rol FROM usuarios";
$result = $conexion->query($query);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

header('Content-Type: application/json');
echo json_encode($data);
?>
