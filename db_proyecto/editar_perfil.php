<?php
session_start();
require 'conexion.php';
// Verificar si el usuario está autenticado
if (!isset($_SESSION['id_usuario'])) {
    header('Location: login.php');
    exit;
}

function obtenerUsuario($conexion, $idUsuario) {
    $query = "SELECT * FROM usuario WHERE id_usuario = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $resultado = $stmt->get_result();
    return $resultado->fetch_assoc();
}

$usuario = obtenerUsuario($conexion, $_SESSION['id_usuario']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Perfil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="styles.css?v=<?= time(); ?>" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
        <a class="navbar-brand">Editar Perfil</a>
        <a class="navbar-item" href="dashboard.php" style="color: white;">Volver</a>
    </div>
</nav>
<div class="highlight-section">
<div class="container mt-4">
    <form id="form-perfil">
    <?php if (!empty($usuario['perfil'])): ?>
            <img id="foto-perfil" src="upload_perfil/<?= htmlspecialchars($usuario['perfil']); ?>" 
                 alt="Foto de perfil" class="perfil mt-2" width="150" style="cursor: pointer;">
        <?php else: ?>
            <img id="foto-perfil" src="upload_perfil/default.png" 
                 alt="Foto de perfil" class="perfil mt-2" width="150" style="cursor: pointer;">
        <?php endif; ?>
        <input type="file" id="input-perfil" name="perfil" style="display: none;" accept="image/*">
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" class="form-control auto-save" id="nombre" name="nombre" value="<?= htmlspecialchars($usuario['nombre']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" class="form-control auto-save" id="email" name="email" value="<?= htmlspecialchars($usuario['email']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="direccion" class="form-label">Dirección</label>
            <textarea class="form-control auto-save" id="direccion" name="direccion" required><?= htmlspecialchars($usuario['direccion']); ?></textarea>
        </div>
        <div class="mb-3">
            <label for="nacionalidad" class="form-label">Nacionalidad</label>
            <input type="text" class="form-control auto-save" id="nacionalidad" name="nacionalidad" value="<?= htmlspecialchars($usuario['nacionalidad']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="num_telefono" class="form-label">Número de Teléfono</label>
            <input type="text" class="form-control auto-save" id="num_telefono" name="num_telefono" value="<?= htmlspecialchars($usuario['num_telefono']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" class="form-control auto-save" id="fecha_nacimiento" name="fecha_nacimiento" value="<?= htmlspecialchars($usuario['fecha_nacimiento']); ?>" required>
        </div>
    </form>
    
</div>
</div>
<div class="highlight-section">
<div class="container mt-4">
    <a class="btn_rojo" href="logout.php">Cerrar sesión</a>
    <a class="btn_rojo" id="desactivar-cuenta" href="#">Desactivar Cuenta</a>
</div>
</div>
<script>
    const fotoPerfil = document.getElementById('foto-perfil');
    const inputPerfil = document.getElementById('input-perfil');

    // Abrir el selector de archivos al hacer clic en la imagen
    fotoPerfil.addEventListener('click', () => {
        inputPerfil.click();
    });

    // Manejar la subida de la imagen cuando se seleccione un archivo
    inputPerfil.addEventListener('change', () => {
        const file = inputPerfil.files[0];
        if (file) {
            const formData = new FormData();
            formData.append('perfil', file);

            fetch('actualizar_foto_perfil.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Actualizar la imagen en el navegador
                    fotoPerfil.src = `upload_perfil/${data.filename}`;
                    console.log('Foto de perfil actualizada correctamente.');
                } else {
                    console.error('Error al actualizar la foto de perfil:', data.message);
                }
            })
            .catch(error => console.error('Error en la solicitud:', error));
        }
    });
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.querySelectorAll('.auto-save').forEach((input) => {
    input.addEventListener('input', (e) => {
        const formData = new FormData();
        formData.append('campo', e.target.name);
        formData.append('valor', e.target.value);

        fetch('actualizar_usuario.php', {
            method: 'POST',
            body: formData
        }).then((response) => response.json())
          .then((data) => {
              if (data.success) {
                  console.log('Cambios guardados automáticamente.');
              } else {
                  console.error('Error al guardar los cambios.');
              }
          }).catch((error) => console.error('Error:', error));
    });
});
// Manejar la solicitud de desactivación de cuenta
document.getElementById('desactivar-cuenta').addEventListener('click', (e) => {
    e.preventDefault();
    
    // Mostrar modal de confirmación
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Tu cuenta será desactivada y no podrás acceder hasta reactivarla.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, desactivar cuenta',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Realizar solicitud para desactivar la cuenta
            fetch('desactivar_usuario.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id_usuario: <?= json_encode($_SESSION['id_usuario']); ?> })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        title: 'Cuenta desactivada',
                        text: 'Tu cuenta ha sido desactivada. Serás redirigido al inicio de sesión.',
                        icon: 'success',
                        confirmButtonText: 'Aceptar'
                    }).then(() => {
                        window.location.href = 'logout.php';
                    });
                } else {
                    Swal.fire({
                        title: 'Error',
                        text: 'Ocurrió un error al intentar desactivar tu cuenta.',
                        icon: 'error',
                        confirmButtonText: 'Aceptar'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    title: 'Error',
                    text: 'Ocurrió un problema con la solicitud. Intenta nuevamente.',
                    icon: 'error',
                    confirmButtonText: 'Aceptar'
                });
            });
        }
    });
});


</script>
</body>
</html>
