<?php
session_start();

$data = require "../php/obtener_pelicula.php";

$pelicula = $data['pelicula'];
$iframeUrl = $data['iframeUrl'];

// Decodificar reparto y fotos de reparto
$reparto = json_decode($pelicula['reparto'], true);
$fotos_reparto = json_decode($pelicula['fotos_reparto'], true);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pelicula['titulo']) ?></title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estilo.css" rel="stylesheet">
    <link href="../css/estilo-cine.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="contenedor-fondo-peliculas">
        <img id="fondo-peliculas" src="../img/view-peliculas-fondo.webp">
        <div class="capa-oscura"></div>
    </div>

    <!-- HEADER -->
    <?php include "../templates/header.php"; ?>

    <main class="container py-5 position-relative">
        <div class="row g-4">
            <!-- Poster grande -->
            <div class="col-12 col-md-5 d-flex justify-content-center">
                <img src="https://image.tmdb.org/t/p/w500<?= $pelicula['poster'] ?>"
                     class="poster-pelicula-peliculas shadow"
                     style="height:auto; max-height:500px; border-radius:0px;"
                     alt="<?= htmlspecialchars($pelicula['titulo']) ?>">
            </div>

            <!-- Información -->
            <div class="col-12 col-md-7">
                <h1 class="titulo-cine-grande"><?= htmlspecialchars($pelicula['titulo']) ?></h1>
                <p class="texto-cine"><strong>Géneros:</strong> <?= htmlspecialchars($pelicula['generos']) ?></p>
                <p class="texto-cine"><strong>Duración:</strong> <?= $pelicula['duracion_minutos'] ?> min</p>
                <p class="texto-cine"><strong>Estreno:</strong> <?= $pelicula['fecha_estreno'] ?></p>
                <p class="texto-cine"><strong>Puntuación:</strong> ⭐ <?= $pelicula['puntuacion'] ?>/10</p>

                <?php if (!empty($pelicula['presupuesto']) && $pelicula['presupuesto'] > 0): ?>
                    <p class="texto-cine"><strong>Presupuesto:</strong> $<?= number_format($pelicula['presupuesto'],0,",",".") ?></p>
                <?php endif; ?>
                <?php if (!empty($pelicula['recaudacion']) && $pelicula['recaudacion'] > 0): ?>
                    <p class="texto-cine"><strong>Recaudación:</strong> $<?= number_format($pelicula['recaudacion'],0,",",".") ?></p>
                <?php endif; ?>

                <?php if (!empty($pelicula['director'])): ?>
                    <p class="texto-cine"><strong>Director:</strong> <?= htmlspecialchars($pelicula['director']) ?></p>
                <?php endif; ?>

                <?php if (!empty($pelicula['frase_promocional'])): ?>
                    <p class="texto-cine mt-3"><strong>Frase promocional:</strong> <?= htmlspecialchars($pelicula['frase_promocional']) ?></p>
                <?php endif; ?>

                <p class="texto-cine mt-2"><?= htmlspecialchars($pelicula['descripcion']) ?></p>

                <!-- BOTONES DE ACCIÓN -->
                <div class="mb-4">
                    <button class="btn-comprar">Comprar</button>
                    <button class="btn-alquilar">+Aquilar</button>
                </div>

                <!-- Trailer -->
                <?php if ($iframeUrl): ?>
                    <hr class="linea-blanca">
                    <h5 class="titulo-cine">Trailer</h5>
                    <div class="ratio ratio-16x9 card-cine shadow rounded">
                        <iframe src="<?= $iframeUrl ?>" title="Trailer de <?= htmlspecialchars($pelicula['titulo']) ?>" frameborder="0" allowfullscreen></iframe>
                    </div>
                <?php endif; ?>
                <hr class="linea-blanca">

                <!-- Carrusel reparto -->
                <?php if (!empty($reparto) && !empty($fotos_reparto)): ?>
                    <p class="texto-cine mt-3"><strong>Reparto principal:</strong></p>
                    <div id="carouselReparto" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $chunks = array_chunk($reparto, 3); // 3 actores por slide
                            foreach($chunks as $i => $grupo):
                            ?>
                            <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                                <div class="d-flex justify-content-center">
                                    <?php foreach($grupo as $j => $actor): ?>
                                    <div class="text-center mx-2" style="width: 120px; height: 250px;">
                                        <img src="<?= $fotos_reparto[$i*3+$j] ?? '../img/default-actor.png' ?>"
                                             class="rounded shadow mb-2"
                                             style="width:100%; height:140px; object-fit: cover;"
                                             alt="<?= htmlspecialchars($actor) ?>">
                                        <small class="texto-cine"><?= htmlspecialchars($actor) ?></small>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselReparto" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Anterior</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselReparto" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Siguiente</span>
                        </button>
                    </div>
                <?php endif; ?>

                <a href="javascript:history.back()" class="btn btn-cine mt-3">Volver</a>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <?php include "../templates/footer.html"; ?>
    <script src="../recursos/bootstrap.bundle.min.js"></script>
</body>
</html>