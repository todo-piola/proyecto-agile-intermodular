<?php
session_start();
require_once "../php/conexion.php";

// Obtener 1 película aleatoria para el póster destacado
$stmtPoster = $conexion->query("SELECT id, titulo, trailer_url FROM peliculas WHERE trailer_url IS NOT NULL AND trailer_url != '' ORDER BY RAND() LIMIT 1");
$peliculaDestacada = $stmtPoster->fetch(PDO::FETCH_ASSOC);

// Obtener 16 películas aleatorias SOLO de TMDB (poster empieza por /)
$stmt = $conexion->query("SELECT id, titulo, poster FROM peliculas WHERE poster LIKE '/%' ORDER BY RAND() LIMIT 16");
$peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Dividir en dos grupos de 8
$peliculasSemana = array_slice($peliculas, 0, 8);
$peliculasGustadas = array_slice($peliculas, 8, 8);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LABUTACASOCIAL</title>
    <link href="../css/estilo.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="icon" type="image/png" href="../img/logo_invisible_butaca.png">
    <script src="../js/templates-js/templates-loader.js"></script>
</head>
<body>
  <!-- HEADER -->
  <?php include "../templates/header.php"; ?>

  <!-- FONDO FIJO CON IMAGEN -->
  <div class="contenedor-fondo-peliculas">
      <img id="fondo-peliculas" src="../img/view-peliculas-fondo.webp" class="d-block">
      <div class="capa-oscura"></div> 
  </div>

  <main class="container-fluid p-5 position-relative">

    <?php if (isset($_SESSION['mensaje'])): ?>
      <div class="alert alert-success text-center"> <?= $_SESSION['mensaje'] ?> </div>
      <?php unset($_SESSION['mensaje']); ?>
    <?php endif; ?>

    <!-- ===================== GRID DE PELÍCULAS ================= -->
    <div class="row g-5 justify-content-around">

      <!-- SECCIÓN 1: Mejores películas de la semana -->
      <section class="col-12 col-xl-5 seccion-peliculas">
          <p class="fs-3 text-white text-center mt-3 cuerpo animate__animated animate__rubberBand animate__delay-2s">
              Mejores películas semana
          </p>

          <div class="row row-cols-2 row-cols-md-4 gy-3 justify-content-center">
            <?php foreach ($peliculasSemana as $pelicula): ?>
              <div class="col text-center">
                <a href="pelicula.php?id=<?= $pelicula['id'] ?>">
                  <img src="https://image.tmdb.org/t/p/w500<?= $pelicula['poster'] ?>" 
                  class="img-fluid rounded shadow poster-grid" 
                  alt="<?= htmlspecialchars($pelicula['titulo']) ?>" >
                </a>
              </div>
            <?php endforeach; ?>
          </div>

      </section>

      <!-- SECCIÓN 2: Películas mejor valoradas -->
      <section class="col-12 col-xl-5 seccion-peliculas">
          <p class="fs-3 text-white text-center mt-3 cuerpo animate__animated animate__rubberBand animate__delay-2s">
              Películas que más gustan
          </p>
          <div class="row row-cols-2 row-cols-md-4 gy-3 justify-content-center">
          <?php foreach ($peliculasGustadas as $pelicula): ?>
              <div class="col text-center">
                <a href="pelicula.php?id=<?= $pelicula['id'] ?>">
                  <img src="https://image.tmdb.org/t/p/w500<?= $pelicula['poster'] ?>" 
                  class="img-fluid rounded shadow poster-grid" 
                  alt="<?= htmlspecialchars($pelicula['titulo']) ?>" >
                </a>
              </div>
            <?php endforeach; ?>
          </div>

      </section>
    </div>

    <!-- FILA INFERIOR: reseñas a la izquierda, trailer a la derecha -->
    <div class="row pt-5 gx-2 justify-content-center">

        <p class="fs-3 text-white text-center cuerpo d-xl-none">Últimas reseñas</p>

        <!-- COLUMNA RESEÑAS: visible siempre, ocupa todo en móvil y mitad en tamaños pequeños -->
        <div class="col-12 col-xl-6 d-flex flex-column flex-sm-row gap-3 flex-xl-column align-items-center">
            <p class="fs-3 text-white text-center cuerpo d-none d-xl-flex">Últimas reseñas</p>
            <div class="card mb-3 border-0 rounded-4" style="max-width: 540px;">
                <div class="row g-0">
                  <div class="col-md-4 peliculas-reseñas">
                    <img src="../img/poster-prueba.jpg" class="img-fluid object-fit-cover h-100 w-100 rounded-top poster-peliculas-reseñas">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title mt-3">Reseña de Fulanito</h5>
                      <p class="card-text">En el Sprint 3, la idea es crear un campo reseñas asociado a una tabla intermedia Reseñas_Pelicula donde se relacionen Usuario y Película con Reseña </p>
                      <div class="likes-comentarios d-flex gap-2 justify-content-center">
                        <p class="text-danger">Aquí se mostrará la cantidad de likes que tiene la reseña de tal usuario y si el usuario logeado interactúa clicando en el botón, aumentará en uno la cantidad de likes</p>
                        <i class="bi bi-heart fs-5 text-danger corazon"></i>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="card mb-3 border-0 rounded-4 align-content-center" style="max-width: 540px;">
                <div class="row g-0">
                  <div class="col-md-4 peliculas-reseñas">
                    <img src="../img/poster-prueba.jpg" class="img-fluid object-fit-cover h-100 w-100 rounded-top poster-peliculas-reseñas">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title mt-3">Reseña de Fulanito</h5>
                      <p class="card-text">En el Sprint 3, la idea es crear un campo reseñas asociado a una tabla intermedia Reseñas_Pelicula donde se relacionen Usuario y Película con Reseña</p>
                      <div class="likes-comentarios d-flex gap-2 justify-content-center">
                        <p class="text-danger">Aquí se mostrará la cantidad de likes que tiene la reseña de tal usuario y si el usuario interactúa clicando en el botón, aumentará en uno la cantidad de likes</p>
                        <i class="bi bi-heart fs-5 text-danger corazon"></i>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
        
        <!-- COLUMNA TRAILER: solo visible en xl -->
        <div class="col-xl-6 d-none d-xl-flex flex-column">
            <p class="fs-3 text-white text-center cuerpo">Tráiler destacado</p>
            <div class="ratio ratio-16x9 rounded overflow-hidden flex-grow-1">
                <?php if ($peliculaDestacada): ?>
                    <?php
                        $url = $peliculaDestacada['trailer_url'];
                        // Convierte watch?v=XXXX o youtu.be/XXXX a formato embed
                        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches)) {
                            $videoId = $matches[1];
                            $embedUrl = "https://www.youtube.com/embed/{$videoId}?autoplay=1&mute=1&loop=1&playlist={$videoId}";
                        }
                    ?>
                    <iframe 
                        src="<?= htmlspecialchars($embedUrl) ?>"
                        class="w-100 h-100 rounded-4"
                        frameborder="0"
                        allow="autoplay; encrypted-media"
                        allowfullscreen>
                    </iframe>
                <?php endif; ?>
            </div>
        </div>

        <!-- COLUMNA RESEÑAS EXTRA para visualizaciones pequeñas, desaparece en xl -->
        <div class="col-12 col-xl-6 d-none d-sm-flex flex-sm-row gap-3 d-xl-none align-items-center">
            <div class="card mb-3 border-0 rounded-4" style="max-width: 540px;">
                <div class="row g-0">
                  <div class="col-md-4 peliculas-reseñas">
                    <img src="../img/poster-prueba.jpg" class="img-fluid object-fit-cover h-100 w-100 rounded-top poster-peliculas-reseñas">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title mt-3">Card title</h5>
                      <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                      <div class="likes-comentarios d-flex gap-2 justify-content-center">
                        <p class="text-danger">Deja tu like</p>
                        <i class="bi bi-heart fs-5 text-danger corazon"></i>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
            <div class="card mb-3 border-0 rounded-4 align-content-center" style="max-width: 540px;">
                <div class="row g-0">
                  <div class="col-md-4 peliculas-reseñas">
                    <img src="../img/poster-prueba.jpg" class="img-fluid object-fit-cover h-100 w-100 rounded-top poster-peliculas-reseñas">
                  </div>
                  <div class="col-md-8">
                    <div class="card-body">
                      <h5 class="card-title mt-3">Card title</h5>
                      <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                      <div class="likes-comentarios d-flex gap-2 justify-content-center">
                        <p class="text-danger">Deja tu like</p>
                        <i class="bi bi-heart fs-5 text-danger corazon"></i>
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>

    </div>

    </main>

    <?php include "../templates/footer.html"; ?>

<script src="../recursos/bootstrap.bundle.min.js"></script>
<script type="module" src="../js/main.js"></script>
</body>
</html>