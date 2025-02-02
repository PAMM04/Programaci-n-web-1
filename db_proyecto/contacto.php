<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contacto y Soporte - UPDS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #1a1a1a;
            color: white;
            padding: 15px 0;
            text-align: center;
            font-size: 24px;
        }

        /* Carrusel */
        .carousel-container {
            width: 100%;
            max-width: 900px;
            margin: 20px auto;
        }

        #universityCarousel {
            width: 100%;
            max-height: 350px;
            overflow: hidden;
            border-radius: 10px;
        }

        .carousel-item img {
            width: 100%;
            height: 350px;
            object-fit: cover;
        }

        .container {
            max-width: 800px;
            margin: auto;
            padding: 2rem;
        }

        /* Formulario */
        .form-section {
            background-color: #e9ecef;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        /* Estilos del mapa */
        .map-container {
            margin-top: 20px;
            text-align: center;
        }

        iframe {
            width: 100%;
            height: 300px;
            border: none;
            border-radius: 10px;
        }

        /* Estilos de la sección de contacto */
        .contact-info h4 {
            margin-top: 20px;
            font-size: 20px;
            font-weight: bold;
        }

        .contact-info p {
            font-size: 16px;
            margin-bottom: 10px;
        }

        .contact-info a {
            color: #007bff;
        }

        .contact-info a:hover {
            color: #0056b3;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">Vortex News</a>
            <header> - Contacto y Soporte Técnico -</header>
        </div>
    </nav>

    <!-- Carrusel -->
    <div class="carousel-container">
        <div id="universityCarousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="upds-building.jpg" alt="UPDS Santa Cruz">
                </div>
                <div class="carousel-item">
                    <img src="campus.jpg" alt="Campus UPDS">
                </div>
                <div class="carousel-item">
                    <img src="upds-campus-3.jpg" alt="Facultad de Ingeniería UPDS">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#universityCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#universityCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>
        </div>
    </div>

    <!-- Información de contacto -->
    <div class="container mt-4">
        <div class="contact-info">
            <h4>Información de Contacto</h4>
            <p><strong>Dirección:</strong> Av. Beni y Tercer Anillo Externo</p>
            <p><strong>Teléfono:</strong> (591) 3 342 - 6600</p>
            <p><strong>WhatsApp:</strong> (591) 658-84086</p>
            <p><strong>Email:</strong> <a href="mailto:infoupds.santacruz@upds.edu.bo">infoupds.santacruz@upds.edu.bo</a></p>
            <p><strong>Facebook:</strong> <a href="https://www.facebook.com/UPDSSantaCruz" target="_blank">Facebook Oficial</a></p>
        </div>
    </div>

    <!-- Formulario de Contacto y Soporte -->
    <div class="container">
        <div class="form-section">
            <h3>Soporte Técnico y Contacto</h3>
            <form action="send_support.php" method="POST">
                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="email">Correo Electrónico</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="phone">Teléfono</label>
                    <input type="text" id="phone" name="phone">
                </div>
                <div class="form-group">
                    <label for="category">Categoría del Problema</label>
                    <select id="category" name="category" required>
                        <option value="Problemas de Conexión">Problemas de Conexión</option>
                        <option value="Acceso al Portal">Acceso al Portal</option>
                        <option value="Correo Institucional">Correo Institucional</option>
                        <option value="Inscripción en Línea">Inscripción en Línea</option>
                        <option value="Problemas con Notas">Problemas con Notas</option>
                        <option value="Pagos y Facturación">Pagos y Facturación</option>
                        <option value="Errores del Sistema">Errores del Sistema</option>
                        <option value="Otro">Otro</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="message">Descripción del Problema</label>
                    <textarea id="message" name="message" rows="4" required></textarea>
                </div>
                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>

    <!-- Mapa de ubicación -->
    <div class="container">
        <div class="map-container">
            <h3>Ubicación de UPDS</h3>
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1950.9872137410243!2d-63.1574318892845!3d-17.78633100127912!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x6a68f9d2e1f739e5!2sUPDS%20Santa%20Cruz!5e0!3m2!1ses!2sbo!4v1618895797165!5m2!1ses!2sbo"></iframe>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
