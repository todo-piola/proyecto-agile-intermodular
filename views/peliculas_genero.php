<?php
session_start();

$data = require "../php/obtener_peliculas_genero.php";

$genero = $data['genero'];
$peliculas = $data['peliculas'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Películas de <?= htmlspecialchars($genero) ?></title>

    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estilo.css" rel="stylesheet">
    <link href="../css/estilo-cine.css" rel="stylesheet">
    <link href="../css/cartStyle.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="../img/logo_invisible_butaca.png">
    
    <script src="../js/templates-js/templates-loader.js"></script>
</head>
<body>
    <div class="contenedor-fondo-peliculas">
        <img id="fondo-peliculas" src="../img/view-peliculas-fondo.webp">
        <div class="capa-oscura"></div>
    </div>
    <!-- HEADER -->
    <?php include "../templates/header.php"; ?>

    <!-- Lista -->
    <main class="container py-5 position-relative">
        <h1 class="text-center titulo-cine-grande mb-5">Películas de <?= htmlspecialchars($genero) ?></h1>
        <div class="row g-4 justify-content-center">
            <?php foreach ($peliculas as $p): ?>
                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="pelicula.php?id=<?= $p['id'] ?>" style="text-decoration:none;">
                        <div class="card-cine shadow h-100 d-flex flex-column">
                            <div style="flex:1;">
                            <?php $posterSrc = str_starts_with($p['poster'], '/') ? "https://image.tmdb.org/t/p/w500" . $p['poster'] : "../img/" . $p['poster']; ?>
                                <img src="<?= htmlspecialchars($posterSrc) ?>" class="poster-pelicula-peliculas" alt="<?= htmlspecialchars($p['titulo']) ?>">
                            </div>
                            <div class="card-body">
                                <h5 class="titulo-cine"><?= htmlspecialchars($p['titulo']) ?></h5>
                                <p class="texto-cine">⭐ <?= $p['puntuacion'] ?>/10</p>
                                <a href="pelicula.php?id=<?= $p['id'] ?>" class="btn btn-cine mt-2">Ver detalles</a>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="text-center mt-5">
            <a href="listas.php" class="btn btn-cine">Volver a listas</a>
        </div>
    </main>

    <!-- FOOTER -->
    <?php include "../templates/footer.html"; ?>

    <script src="../recursos/bootstrap.bundle.min.js"></script>
    <script type="module" src="../js/main.js"></script>
</body>
</html>