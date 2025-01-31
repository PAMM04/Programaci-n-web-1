<?php
$host="sql204.infinityfree.com";
$usuario="if0_38184675";
$password="O6qe0ISbmECy";
$db="if0_38184675_db_proyecto";
$conexion = new mysqli($host,$usuario,$password,$db);


// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>
