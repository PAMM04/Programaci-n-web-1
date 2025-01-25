<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noticias Destacadas</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .news-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
            justify-content: center;
        }
        .news-card {
            position: relative;
            width: 300px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background: #fff;
        }
        .news-card .background-holder {
            height: 180px;
            background-size: cover;
            background-position: center;
        }
        .news-card .content {
            padding: 15px;
        }
        .news-card .content .category {
            font-size: 12px;
            color: #ff6b6b;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .news-card .content .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .news-card .content .title:hover {
            color: #ff6b6b;
        }
        .news-card .content .meta {
            font-size: 12px;
            color: #777;
        }
        .news-card .social-list {
            display: flex;
            gap: 10px;
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .news-card .social-list a {
            color: #fff;
            background: rgba(0, 0, 0, 0.6);
            padding: 8px;
            border-radius: 50%;
            text-decoration: none;
            transition: background 0.3s;
        }
        .news-card .social-list a:hover {
            background: #ff6b6b;
        }
    </style>
</head>
<body>
    <h1 style="text-align: center; padding: 20px 0; background: #ff6b6b; color: white;">Noticias Destacadas</h1>
    <div class="news-container">
        <!-- Primera noticia -->
        <div class="news-card">
            <div class="background-holder" style="background-image: url('https://la-razon.com/wp-content/uploads/2025/01/21/17/Sin-titulo.png');"></div>
            <div class="content">
                <div class="category">Nacional</div>
                <a href="https://la-razon.com/nacional/2025/01/25/del-castillo-dice-que-no-quiere-heridos-en-la-aprehension-de-morales/" class="title">Del Castillo dice que no quiere heridos en la aprehensión de Morales</a>
                <div class="meta">Por Mauricio Diaz Saravia / 25 de enero de 2025</div>
            </div>
            <ul class="social-list">
                <li><a href="https://twitter.com/intent/tweet?url=https://la-razon.com/nacional/2025/01/25/del-castillo-dice-que-no-quiere-heridos-en-la-aprehension-de-morales/" target="_blank"><i class="fab fa-twitter"></i></a></li>
                <li><a href="https://facebook.com/sharer.php?u=https://la-razon.com/nacional/2025/01/25/del-castillo-dice-que-no-quiere-heridos-en-la-aprehension-de-morales/" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
            </ul>
        </div>

        <!-- Segunda noticia -->
        <div class="news-card">
            <div class="background-holder" style="background-image: url('https://la-razon.com/wp-content/uploads/2025/01/25/13/aduana.jpg');"></div>
            <div class="content">
                <div class="category">Economía</div>
                <a href="https://la-razon.com/economia/2025/01/25/caen-dos-personas-por-intentar-evitar-con-violencia-un-operativo-anticontrabando/" class="title">Caen dos personas por intentar evitar con violencia un operativo anticontrabando</a>
                <div class="meta">Por Erika Ibáñez / 25 de enero de 2025</div>
            </div>
            <ul class="social-list">
                <li><a href="https://twitter.com/intent/tweet?url=https://la-razon.com/economia/2025/01/25/caen-dos-personas-por-intentar-evitar-con-violencia-un-operativo-anticontrabando/" target="_blank"><i class="fab fa-twitter"></i></a></li>
                <li><a href="https://facebook.com/sharer.php?u=https://la-razon.com/economia/2025/01/25/caen-dos-personas-por-intentar-evitar-con-violencia-un-operativo-anticontrabando/" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
            </ul>
        </div>
    </div>
</body>
</html>
