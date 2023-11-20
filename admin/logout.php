<?php
session_start();
session_destroy();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sesión cerrada por inactividad</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ee5e04;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        h1 {
            color: #000000;
        }

        p {
            color: #000000;
            margin-bottom: 20px;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sesión cerrada por inactividad</h1>
        <p>Su sesión se ha cerrado automáticamente debido a la inactividad.</p>
        <p><a href="../login.php">¿Iniciar sesión nuevamente?</a></p>
    </div>
</body>
</html>
