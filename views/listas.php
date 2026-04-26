<?php
session_start();
$generosUnicos = require __DIR__ . "/../php/obtener_generos.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="app-base" content="<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>">
    <title>Géneros de Películas</title>

    <script type="module">
        import RefreshRuntime from 'http://localhost:5173/@react-refresh'
        RefreshRuntime.injectIntoGlobalHook(window)
        window.$RefreshReg$ = () => {}
        window.$RefreshSig$ = () => (type) => type
        window.__vite_plugin_react_preamble_installed__ = true
    </script>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo-cine.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
    <link href="css/cartStyle.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="img/logo_invisible_butaca.png">
    <script src="js/templates-js/templates-loader.js"></script>
</head>
<body>

    <div class="contenedor-fondo-peliculas">
        <img id="fondo-peliculas" src="img/view-peliculas-fondo.webp">
        <div class="capa-oscura"></div>
    </div>

    <!-- HEADER -->
    <?php include __DIR__ . "/../templates/header.php"; ?>

    <!-- Listas -->
    <main class="container py-5 position-relative">
        <h1 class="text-center titulo-cine-grande mb-5">Géneros de Películas</h1>

        <div class="row g-4 justify-content-center">
            <?php foreach ($generosUnicos as $genero): ?>
                <div class="col-6 col-sm-4 col-md-3">
                    <a href="index.php?route=peliculas_genero&genero=<?= urlencode($genero) ?>" style="text-decoration:none;">
                        <div class="card-genero" style="background-image:url('img/generos/<?= strtolower($genero) ?>.jpg'); background-size:cover; background-position:center;">
                            <h5><?= htmlspecialchars($genero) ?></h5>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center my-4">
            <a href="index.php?route=home" class="btn btn-cine">Volver al inicio</a>
        </div>
    </main>

    <!-- FOOTER -->
    <?php include __DIR__ . "/../templates/footer.html"; ?>

    <script src="recursos/bootstrap.bundle.min.js"></script>
    <script type="module" src="js/main.js"></script>
</body>
</html>