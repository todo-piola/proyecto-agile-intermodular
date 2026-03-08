<?php
session_start();
require_once "../bd/conexion.php";
if (!isset($_GET['id'])) die("Película no especificada");

$id = $_GET['id'];
$stmt = $conexion->prepare("SELECT * FROM peliculas WHERE id=?");
$stmt->execute([$id]);
$pelicula = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$pelicula) die("Película no encontrada");

$iframeUrl = null;
if (!empty($pelicula['trailer_url'])) {
    parse_str(parse_url($pelicula['trailer_url'], PHP_URL_QUERY), $youtubeParams);
    if (isset($youtubeParams['v'])) $iframeUrl = "https://www.youtube.com/embed/" . $youtubeParams['v'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pelicula['titulo']) ?></title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estilo-cine.css" rel="stylesheet">
    <link href="../css/estilo.css" rel="stylesheet">
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
                     style="height:auto; max-height:500px; border-radius:15px;"
                     alt="<?= htmlspecialchars($pelicula['titulo']) ?>">
            </div>

            <!-- Información -->
            <div class="col-12 col-md-7">
                <h1 class="titulo-cine-grande"><?= htmlspecialchars($pelicula['titulo']) ?></h1>
                <p class="texto-cine"><strong>Géneros:</strong> <?= htmlspecialchars($pelicula['generos']) ?></p>
                <p class="texto-cine"><strong>Duración:</strong> <?= $pelicula['duracion_minutos'] ?> min</p>
                <p class="texto-cine"><strong>Estreno:</strong> <?= $pelicula['fecha_estreno'] ?></p>
                <p class="texto-cine"><strong>Puntuación:</strong> ⭐ <?= $pelicula['puntuacion'] ?>/10</p>
                <p class="texto-cine"><strong>Presupuesto:</strong> $<?= number_format($pelicula['presupuesto'],0,",",".") ?></p>
                <p class="texto-cine"><strong>Recaudación:</strong> $<?= number_format($pelicula['recaudacion'],0,",",".") ?></p>
                <?php if (!empty($pelicula['frase_promocional'])): ?>
                    <p class="texto-cine"><strong>Frase promocional:</strong> <?= htmlspecialchars($pelicula['frase_promocional']) ?></p>
                <?php endif; ?>

                <?php if ($iframeUrl): ?>
                    <hr class="linea-blanca">
                    <h5 class="titulo-cine">Trailer</h5>
                    <div class="ratio ratio-16x9 card-cine shadow rounded">
                        <iframe src="<?= $iframeUrl ?>" title="Trailer de <?= htmlspecialchars($pelicula['titulo']) ?>" frameborder="0" allowfullscreen></iframe>
                    </div>
                <?php endif; ?>

                <hr class="linea-blanca">
                <p class="texto-cine"><?= htmlspecialchars($pelicula['descripcion']) ?></p>
                <a href="javascript:history.back()" class="btn btn-cine mt-3">Volver</a>
            </div>
        </div>
    </main>

    <!-- FOOTER -->
    <?php include "../templates/footer.html"; ?>
    <script src="../recursos/bootstrap.bundle.min.js"></script>
</body>
</html>