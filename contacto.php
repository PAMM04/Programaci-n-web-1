<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto - UPDS</title>
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
    </style>
</head>
<body>
    <header>Contacto - Santa Cruz</header>

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
                    <p><strong>WhatsApp:</strong> (591) 721-29166</p>
                    <p><strong>Email:</strong> info@upds.edu.bo</p>
                    <p><a href="https://facebook.com/UPDS.bo" target="_blank">Facebook Oficial</a></p>
                </div>

                <!-- Mapa alineado a la derecha -->
                <div class="map-container">
                    <iframe 
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3827.905861716468!2d-63.17260152640067!3d-17.79004098226819!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x935a321c524ddaa1%3A0xc88b73f0e5081a4b!2sUniversidad%20Privada%20Domingo%20Savio%20Santa%20Cruz!5e0!3m2!1ses!2sbo!4v1706171918976!5m2!1ses!2sbo"
                        allowfullscreen=""
                        loading="lazy">
                    </iframe>
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
