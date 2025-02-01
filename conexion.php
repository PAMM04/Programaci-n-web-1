<?php
$host="localhost";
$usuario="root";
$password="";
$db="db_proyecto";
$conexion = new mysqli($host,$usuario,$password,$db);


// Verificar conexión
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}

$conexion->set_charset("utf8");
?>
