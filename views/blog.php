<?php require_once(__DIR__ . "/../php/conexion.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Blog</title>

    <script type="module">
        import RefreshRuntime from 'http://localhost:5173/@react-refresh'
        RefreshRuntime.injectIntoGlobalHook(window)
        window.$RefreshReg$ = () => {}
        window.$RefreshSig$ = () => (type) => type
        window.__vite_plugin_react_preamble_installed__ = true
    </script>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estilo-cine.css" rel="stylesheet">
    <link href="../css/estilo.css" rel="stylesheet">
    <link href="../css/estilo_about.css" rel="stylesheet">
    <link href="../css/cartStyle.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="../img/logo_invisible_butaca.png">
    
    <script src="../js/templates-js/templates-loader.js"></script>
</head>
<body>
    <main>
        <?php include(__DIR__ . "/../templates/header.php"); ?>
        <section class="container my-4">
            <div class="row align-items-center g-4">
                <div class="col-12 col-md-6">
                    <img src="../img/work_in_progress.webp" alt="" class="img-fluid w-100">
                </div>
                <div class="col-12 col-md-6">
                    <p>Hola, ahora mismo estamos trabajando para ofreceros los mejores artículos sobre cine.
                        Cuándo hayamos preparado cosas las publicaremos en esta sección, así que estad atentos.
                        Y por favor, tened cuidado con dar de comer a vuestro Gremblin después de medianoche.
                    </p>
                </div>
            </div>
            
            <div class="row align-items-start g-4 mt-1">
                <div class="col-6 col-md-4">
                    <img class="img-fluid w-100" src="../img/work_in_progress.webp" alt="Imagen de una mujer controlando el tráfico">
                    <p>Quizá algún día este sea un post sobre Timothy Chalamet perdiendo el oscar a mejor actor contra Michael B. Jordan</p>
                </div>
                <div class="col-6 col-md-4">
                    <img class="img-fluid w-100" src="../img/work_in_progress.webp" alt="Imagen de una mujer controlando el tráfico">
                    <p>Trabajando en un post sobre las películas más esperadas para la segunda mitad del 2026</p>
                </div>
                <div class="col-12 col-md-4">
                    <img class="img-fluid w-100" src="../img/work_in_progress.webp" alt="Imagen de una mujer controlando el tráfico">
                    <p>El equipo de La Butaca Social está escribiendo un post sobre Dune, ecologismo 
                        y los peligros de las figuras autoritarias con tintes religiosos y mesiánicos.
                    </p>
                </div>
            </div>
        </section>
        <?php include(__DIR__ . "/../templates/footer.html"); ?>
    </main>

<script src="../recursos/bootstrap.bundle.min.js"></script>
<script type="module" src="../js/main.js"></script>
</body>
</html>