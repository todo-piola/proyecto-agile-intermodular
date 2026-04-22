<?php
require_once __DIR__ . "/../php/conexion.php";
$stmtCatalogo = $conexion->query("SELECT id, titulo, poster FROM peliculas WHERE poster LIKE '/%' ORDER BY RAND() LIMIT 6");
$catalogoPeliculas = $stmtCatalogo->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LABUTACASOCIAL</title>

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
    <link rel="icon" type="image/png" href="img/logo_invisible_butaca.png">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <script src="js/templates-js/templates-loader.js"></script>
</head>
<body>
    <main class="container-fluid bg-black">
        <?php include __DIR__ . "/../templates/header.php"; ?>

        <div class="container-fluid bg-black">

            <!-- Mensaje confirmación pedido -->
            <div id="msg-pedido" class="alert alert-success d-none position-fixed  m-4" style="z-index:9999">
                 ¡Pedido realizado con éxito! Disfruta tus películas.
            </div>

            <script>
                if (sessionStorage.getItem('pedido_ok')) {
                    sessionStorage.removeItem('pedido_ok');
                    document.getElementById('msg-pedido').classList.remove('d-none');
                    setTimeout(() => document.getElementById('msg-pedido').classList.add('d-none'), 6000);
                }
            </script>

            <div class="row d-md-none align-items-center p-5" id="hero-movil" style="background-image: url('img/img-landing-parasite.jpg')">
                <div class="col-12 col-md-4 text-start text-white p-1">
                    <h2 class="hero-cta">¡Sigue la evolución del cine, sin salir de tu casa!</h2>
                    <button type="button" class="btn btn-warning mt-2 " data-bs-toggle="modal" data-bs-target="#modal">
                        ¡Regístrate ahora! </button>
                </div>
            </div>

            <div class="row d-none d-md-flex align-items-center p-5" id="hero-escritorio" style="background-image: url('img/img-dune-landing.jpeg')">
                <div class="col-5 text-left text-white p-5">
                    <h2 class="hero-cta">¡Sigue la evolución del cine, sin salir de tu casa!</h2>
                    <button type="button" class="btn btn-warning mt-2 " data-bs-toggle="modal" data-bs-target="#modal">
                        ¡Regístrate ahora!
                    </button>
                </div>
            </div>

            <!-- Grid de funcionalidades - 6 tarjetas con iconos -->
            <div class="row g-4 mt-4 d-flex justify-content-center" id="funcionalidades">

                <div class="col-6 col-md-4 col-xl-4">
                    <div class="card h-100 shadow-sm p-3">
                        <div class="d-flex justify-content-center mt-2">
                            <img src="img/iconos/estrella.png" class="icono-grid" alt="icono estrella">
                        </div>
                        <div class="card-body d-flex flex-column p-1">
                            <h5 class="card-title text-center">VALORA</h5>
                            <p class="card-text text-center text-muted">tus películas favoritas y expresa tus gustos personales.</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-4">
                    <div class="card h-100 shadow-sm p-3">
                        <div class="d-flex justify-content-center mt-2">
                            <img src="img/iconos/teclado.png" class="icono-grid" alt="icono teclado">
                        </div>
                        <div class="card-body d-flex flex-column p-1">
                            <h5 class="card-title text-center">ESCRIBE</h5>
                            <p class="card-text text-center text-muted">reviews sinceras y comparte tus impresiones más auténticas.</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-4">
                    <div class="card h-100 shadow-sm p-3">
                        <div class="d-flex justify-content-center mt-2">
                            <img src="img/iconos/comparte.png" class="icono-grid" alt="icono comparte">
                        </div>
                        <div class="card-body d-flex flex-column p-1">
                            <h5 class="card-title text-center">COMPARTE</h5>
                            <p class="card-text text-center text-muted">listas personalizadas con otros usuarios mostrando tus gustos.</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-4">
                    <div class="card h-100 shadow-sm p-3">
                        <div class="d-flex justify-content-center mt-2">
                            <img src="img/iconos/diamante.png" class="icono-grid" alt="icono diamante">
                        </div>
                        <div class="card-body d-flex flex-column p-1">
                            <h5 class="card-title text-center">SEGUIMIENTO</h5>
                            <p class="card-text text-center text-muted">de tus películas favoritas y de las que planeas ver próximamente.</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-4">
                    <div class="card h-100 shadow-sm p-3">
                        <div class="d-flex justify-content-center mt-2">
                            <img src="img/iconos/corazon.png" class="icono-grid" alt="icono corazón">
                        </div>
                        <div class="card-body d-flex flex-column p-1">
                            <h5 class="card-title text-center">PUNTÚA</h5>
                            <p class="card-text text-center text-muted">listas y películas, valora y refleja tus sentimientos y sensaciones.</p>
                        </div>
                    </div>
                </div>

                <div class="col-6 col-md-4 col-xl-4">
                    <div class="card h-100 shadow-sm p-3">
                        <div class="d-flex justify-content-center mt-2">
                            <img src="img/iconos/lista.png" class="icono-grid" alt="icono lista">
                        </div>
                        <div class="card-body d-flex flex-column p-1">
                            <h5 class="card-title text-center">CREA</h5>
                            <p class="card-text text-center text-muted">un diario de películas guarda tus sensaciones más influyentes.</p>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Catálogo de películas esperadas -->
            <div class="row p-5 bg-black">
                <div class="col-12 d-md-none">
                    <h3 class="text-center text-md-start w-100 text-white peliculas-esperadas-movil">
                        Películas más esperadas de 2026
                    </h3>
                </div>
                <div class="d-none d-md-flex">
                    <h3 class="text-center text-md-start w-100 text-white peliculas-esperadas-escritorio">
                        Películas más esperadas de 2026
                    </h3>
                </div>
                <?php foreach ($catalogoPeliculas as $p): ?>
                    <div class="col-4 col-md-2">
                        <a href="index.php?route=pelicula&id=<?= $p['id'] ?>">
                            <div class="bg-dark">
                                <img src="https://image.tmdb.org/t/p/w500<?= $p['poster'] ?>"
                                    class="w-100 object-fit-cover"
                                    alt="<?= htmlspecialchars($p['titulo']) ?>">
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>            
            </div>

            <!-- CTA móvil - Solo visible en pantallas pequeñas -->
            <div class="row d-flex d-md-none align-items-center justify-content-center p-5">
                <div class="text-center text-white ">
                    <h3 class="hero-cta">Únete a la comunidad cinéfila del momento y consigue tu medalla distintiva antes que nadie</h3>
                    <button type="button" class="btn btn-warning mt-2 " data-bs-toggle="modal" data-bs-target="#modal">
                        Empieza tu aventura </button>
                </div>
            </div>

            <!-- CTA escritorio - Visible desde md en adelante -->
            <div class="row d-none d-md-flex align-items-center justify-content-center p-5">
                <div class="col-8 text-center text-white p-2">
                    <h2 class="hero-cta">Únete a la comunidad cinéfila del momento y  consigue tu medalla distintiva antes que nadie</h2>
                    <button type="button" class="btn btn-warning mt-2 " data-bs-toggle="modal" data-bs-target="#modal">
                        Empieza tu aventura </button>
                </div>
            </div>

        </div>

        <?php include __DIR__ . "/../templates/footer.html"; ?>
    </main>

    <script src="recursos/bootstrap.bundle.min.js"></script>
    <script type="module" src="js/main.js"></script>
</body>
</html>