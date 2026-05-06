<?php
session_start();

$data = require __DIR__ . "/../php/obtener_pelicula.php";

$pelicula = $data['pelicula'];
$iframeUrl = $data['iframeUrl'];

// Decodificar reparto y fotos de reparto
$reparto = json_decode($pelicula['reparto'], true);
$fotos_reparto = json_decode($pelicula['fotos_reparto'], true);

// Comprobamos que usuario es admin
$esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador';

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="app-base" content="<?= rtrim(dirname($_SERVER['SCRIPT_NAME']), '/') ?>">
    <title><?= htmlspecialchars($pelicula['titulo']) ?></title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
    <link href="css/estilo-cine.css" rel="stylesheet">
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

    <main class="container py-5 position-relative">
        <div class="row g-4">
            <!-- Poster grande -->
            <div class="col-12 col-md-5 d-flex justify-content-center">
                <?php
                    $poster = $pelicula['poster'];
                    // Si empieza por "/" es ruta TMDB, si no, es archivo local
                    $posterSrc = str_starts_with($poster, '/')
                        ? "https://image.tmdb.org/t/p/w500" . $poster
                        : "img/" . $poster;
                ?>
                <img src="<?= htmlspecialchars($posterSrc) ?>"
                    class="poster-pelicula-peliculas shadow"
                     style="height:auto; max-height:500px; border-radius:0px;"
                     alt="<?= htmlspecialchars($pelicula['titulo']) ?>">
            </div>

            <!-- Información -->
            <div class="col-12 col-md-7">
                <?php if ($esAdmin): ?>
                <div class="mt-3 mb-4">
                    <p class="fs-3 text-white">Panel CRUD</p>
                    <button class="btn-modificar" data-bs-toggle="modal" data-bs-target="#modalEditar"> Modificar </button>
                    <button class="btn-eliminar" data-bs-toggle="modal" data-bs-target="#modalEliminar"> Eliminar </button>
                    <button class="btn-agregar" data-bs-toggle="modal" data-bs-target="#modalAgregar"> Agregar nueva película </button>
                </div>
                <?php endif; ?>

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

                <!-- Aquí el botón para el carrito -->
                <div id="cart-root"></div>
                <div id="react-pelicula"></div>

                <script>
                    window.PELICULA_DATA = <?= json_encode($pelicula) ?>;
                </script>

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
                    <h5 class="titulo-cine">Reparto</h5>
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
                                        <img src="<?= $fotos_reparto[$i*3+$j] ?? 'img/default-actor.png' ?>"
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
        <!-- Reseñas -->
        <hr class="linea-blanca mt-5">

        <h4 class="text-white">Reseñas</h4>

        <?php if (!empty($resenas)): ?>
            <?php foreach ($resenas as $r): ?>
                <div class="seccion-peliculas mb-3 p-3">
                    <div class="d-flex justify-content-between">
                        <strong class="texto-cine"><?= htmlspecialchars($r['nombre_completo']) ?></strong>
                        <small class="texto-cine"><?= $r['fecha'] ?></small>
                    </div>

                    <p class="mt-2 mb-0 texto-cine">
                        <?= htmlspecialchars($r['comentario']) ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-warning">Todavía no hay reseñas.</p>
        <?php endif; ?>
        <?php if (isset($_SESSION['usuario_id'])): ?>

            <form method="POST" action="php/guardar_resena.php" class="mt-4">

                <input type="hidden" name="id_pelicula" value="<?= $pelicula['id'] ?>">

                <textarea name="comentario" class="form-control mb-2"
                          placeholder="Escribe tu reseña..." required></textarea>

                <button class="btn btn-warning">Publicar reseña</button>
            </form>

        <?php else: ?>
            <p class="text-warning">Inicia sesión para escribir una reseña</p>
        <?php endif; ?>
    </main>

    <!-- Modal para modificar campos (descripcion, presupuesto y recaudación) en película -->
    <?php if ($esAdmin): ?>
        <div class="modal fade" id="modalEditar">
        <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="php/actualizar_pelicula.php" enctype="multipart/form-data">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">Editar película</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" value="<?= $pelicula['id'] ?>">

                    <label>Descripción</label>
                    <textarea class="form-control" name="descripcion"><?= $pelicula['descripcion'] ?></textarea>

                    <label class="mt-3">Presupuesto</label>
                    <input class="form-control" type="number" name="presupuesto" value="<?= $pelicula['presupuesto'] ?>">

                    <label class="mt-3">Recaudación</label>
                    <input class="form-control" type="number" name="recaudacion" value="<?= $pelicula['recaudacion'] ?>">

                    <label class="mt-3">Poster (dejar vacío para no cambiar)</label>
                    <input class="form-control" type="file" name="poster">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button class="btn btn-warning">Guardar cambios</button>
                </div>
            </form>

        </div>
        </div>
        </div>
    <?php endif; ?>

    <!-- Modal para eliminar registro de película -->
    <?php if ($esAdmin): ?>
        <div class="modal fade" id="modalEliminar">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="php/eliminar_pelicula.php">
                        <div class="modal-header bg-danger text-white">
                            <h5 class="modal-title">Eliminar película</h5>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id" value="<?= $pelicula['id'] ?>">
                            <input type="hidden" name="titulo" value="<?= htmlspecialchars($pelicula['titulo']) ?>">

                            <p>¿Seguro que quieres eliminar <strong><?= $pelicula['titulo'] ?></strong>?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-danger">Eliminar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <!-- Modal para añadir nueva película a la bbdd -->
    <?php if ($esAdmin): ?>
        <div class="modal fade" id="modalAgregar">
        <div class="modal-dialog">
        <div class="modal-content btn-agregar">

            <form method="POST" action="php/crear_pelicula.php" enctype="multipart/form-data">
                <div class="modal-header text-white">
                    <h5 class="modal-title">Agregar nueva película</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <label>Título *</label>
                    <input class="form-control" name="titulo" required>

                    <label class="mt-3">Descripción *</label>
                    <textarea class="form-control" name="descripcion" required></textarea>

                    <label class="mt-3">Géneros *</label>
                    <input class="form-control" name="generos" required placeholder="Acción, Drama...">

                    <label class="mt-3">Poster</label>
                    <input type="file" class="form-control" name="poster">

                    <label class="mt-3">Director</label>
                    <input class="form-control" name="director">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light text-black" data-bs-dismiss="modal"> Cancelar </button>
                    <button class="btn btn-warning text-black"> Crear película </button>
                </div>
            </form>

        </div>
        </div>
        </div>
    <?php endif; ?>
    
    <script> 
        window.PELICULA_ID = <?= $pelicula['id'] ?>;
        window.PELICULA_DATA = <?= json_encode($pelicula) ?>;
    </script>


    <!-- FOOTER -->
    <?php include __DIR__ . "/../templates/footer.html"; ?>

    <script src="recursos/bootstrap.bundle.min.js"></script>
</body>
</html>