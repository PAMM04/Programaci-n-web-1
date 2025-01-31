<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - UPDS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">


    <style>
        /* Estilos generales */
        body {
            background-color: #f5f5f5;
            color: #333;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        /* Encabezado */
        header {
            background-color: #1a1a1a;
            color: white;
            padding: 15px 0;
            font-size: 24px;
        }

        /* Contenedor principal */
        .container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: flex-start;
            gap: 20px;
            padding: 2rem;
        }

        /* Sección de contacto */
        .contact-section {
            display: flex;
            flex-wrap: wrap;
            width: 100%;
            max-width: 1000px;
            justify-content: space-between;
        }

        /* Imagen grande a la izquierda */
        .contact-image {
            width: 48%;
            max-width: 480px;
        }

        .contact-image img {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }

        /* Contenedor derecho (info + mapa) */
        .contact-right {
            width: 48%;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Información de contacto */
        .contact-details {
            text-align: left;
            padding: 15px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        /* Mapa */
        .map-container {
            width: 100%;
            height: 300px;
            border-radius: 10px;
            overflow: hidden;
        }

        iframe {
            width: 100%;
            height: 100%;
            border: none;
        }

        /* Formulario */
        .form-section {
            width: 100%;
            max-width: 600px;
            background-color: #e9ecef;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 1rem;
            text-align: left;
        }

        label {
            display: block;
            font-weight: bold;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            font-size: 15px;
            font-weight: bold;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Responsivo */
        @media (max-width: 768px) {
            .contact-section {
                flex-direction: column;
                align-items: center;
            }

            .contact-image, .contact-right {
                width: 100%;
                text-align: center;
            }

            .map-container {
                height: 300px;
            }
        }

        /* Contenedor del mapa */
.map-container {
    position: relative;
    text-align: right; /* Alinear a la derecha */
    width: 100%; /* Ocupa el ancho necesario */
    max-width: 400px; /* Tamaño máximo del mapa */
    margin: 20px auto; /* Espaciado superior e inferior, centrado horizontalmente */
    cursor: pointer; /* Cambiar el cursor a "pointer" para indicar que es clicable */
}

/* Estilo del iframe o la imagen */
.map-container iframe,
.map-container img {
    width: 100%; /* Ajusta al ancho del contenedor */
    height: auto; /* Mantiene la proporción de aspecto */
    border-radius: 12px; /* Bordes redondeados */
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2); /* Sombra suave */
    border: none; /* Sin bordes adicionales */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Efecto de hover */
}

/* Hover para iframe o imagen */
.map-container iframe:hover,
.map-container img:hover {
    transform: scale(1.05); /* Aumenta ligeramente el tamaño */
    box-shadow: 0 6px 14px rgba(0, 0, 0, 0.3); /* Incrementa la sombra */
}

    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Vortex News</a>
            <header>    - Contacto - Santa Cruz</header>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- Sección de contacto -->
        <div class="contact-section">
            <!-- Imagen grande a la izquierda -->
            <div class="contact-image">
                <img src="upds-building.jpg" alt="UPDS Santa Cruz">
            </div>

            <!-- Información + mapa -->
            <div class="contact-right">
                <div class="contact-details">
                    <p><strong>Dirección:</strong> Av. Beni y Tercer Anillo Externo</p>
                    <p><strong>Teléfono:</strong> (591) 3 342 - 6600</p>
                    <p><strong>WhatsApp:</strong> 
                    <a href="https://wa.me/59165884086" target="_blank" style="text-decoration: none; color: inherit;">(591) 658-84086</a></p>

                    <p><strong>Email:</strong><a href="mailto:infoupds.santacruz@upds.edu.bo">infoupds.santacruz@upds.edu.bo</a></p>
                    <p><a href="https://facebook.com/UPDS.bo" target="_blank">Facebook Oficial</a></p>
                </div>

                <div class="map-container" style="text-align: right;">
                    <a href="https://maps.app.goo.gl/hyQfw281UCQdVtDy9" target="_blank">
                    <img src="imgs.jpg" alt="Mapa" style="width: 300px; height: 200px;">
                    </a>
                </div>

            </div>
        </div>

        <!-- Formulario -->
        <div class="form-section">
            <h2>Envíanos un mensaje</h2>
            <form action="contacto.php" method="POST">
                <div class="form-group">
                    <label for="nombre">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" required>
                </div>

                <div class="form-group">
                    <label for="email">Correo Electrónico:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="mensaje">Mensaje:</label>
                    <textarea id="mensaje" name="mensaje" required></textarea>
                </div>

                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>
</body>
</html>
