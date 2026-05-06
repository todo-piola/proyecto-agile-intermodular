<?php
session_start();
require_once __DIR__ . "/../php/conexion.php";

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
    <meta name="app-base" content="<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Películas</title>

    <script type="module">
        import RefreshRuntime from 'http://localhost:5173/@react-refresh'
        RefreshRuntime.injectIntoGlobalHook(window)
        window.$RefreshReg$ = () => {}
        window.$RefreshSig$ = () => (type) => type
        window.__vite_plugin_react_preamble_installed__ = true
    </script>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
    <link href="css/cartStyle.css" rel="stylesheet">
    <link href="css/estilo-cine.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link rel="icon" type="image/png" href="img/logo_invisible_butaca.png">
    
    <script src="js/templates-js/templates-loader.js"></script>
</head>
<body>
  <!-- HEADER -->
  <?php include __DIR__ . "/../templates/header.php"; ?>

  <!-- FONDO FIJO CON IMAGEN -->
  <div class="contenedor-fondo-peliculas">
      <img id="fondo-peliculas" src="img/view-peliculas-fondo.webp" class="d-block">
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
                <a href="index.php?route=pelicula&id=<?= $pelicula['id'] ?>">
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
                <a href="index.php?route=pelicula&id=<?= $pelicula['id'] ?>">
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

    </div>

    </main>

    <?php include __DIR__ . "/../templates/footer.html"; ?>

<script src="recursos/bootstrap.bundle.min.js"></script>
<script type="module" src="js/main.js"></script>
</body>
</html>