<?php
session_start();

// Destruir la sesión
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión actual

// Redirigir al login
header("Location: dashboard.php");
exit;
?>
