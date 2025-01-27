<?php
session_start();
require 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = $_SESSION['id_usuario'] ?? 0;

    // Validar que se haya subido un archivo
    if (isset($_FILES['perfil']) && $_FILES['perfil']['error'] === UPLOAD_ERR_OK) {
        $targetDir = "upload_perfil/";
        $filename = basename($_FILES['perfil']['name']);
        $targetFile = $targetDir . $filename;
        $fileType = pathinfo($targetFile, PATHINFO_EXTENSION);

        // Validar el tipo de archivo
        $tiposPermitidos = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array(strtolower($fileType), $tiposPermitidos)) {
            echo json_encode(['success' => false, 'message' => 'Tipo de archivo no permitido.']);
            exit;
        }

        // Verificar que la carpeta existe o crearla
        if (!is_dir($targetDir)) {
            if (!mkdir($targetDir, 0755, true)) {
                echo json_encode(['success' => false, 'message' => 'No se pudo crear el directorio de destino.']);
                exit;
            }
        }

        // Obtener la foto actual del usuario
        $queryFoto = "SELECT perfil FROM usuario WHERE id_usuario = ?";
        $stmtFoto = $conexion->prepare($queryFoto);
        $stmtFoto->bind_param("i", $idUsuario);
        $stmtFoto->execute();
        $resultadoFoto = $stmtFoto->get_result();
        $usuario = $resultadoFoto->fetch_assoc();
        $fotoActual = $usuario['perfil'];

        // Mover el archivo subido al directorio de destino
        if (move_uploaded_file($_FILES['perfil']['tmp_name'], $targetFile)) {
            // Eliminar la foto anterior si existe
            if (!empty($fotoActual) && file_exists($targetDir . $fotoActual)) {
                unlink($targetDir . $fotoActual); // Eliminar la foto anterior
            }

            // Actualizar el nombre del archivo en la base de datos
            $query = "UPDATE usuario SET perfil = ? WHERE id_usuario = ?";
            $stmt = $conexion->prepare($query);
            $stmt->bind_param("si", $filename, $idUsuario);
            $stmt->execute();

            echo json_encode(['success' => true, 'filename' => $filename]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al mover el archivo.']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'No se subió ningún archivo.']);
    }
    exit;
}
?>
