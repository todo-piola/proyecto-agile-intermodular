<?php require_once(__DIR__ . "/../php/conexion.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estilo.css" rel="stylesheet">
    <link href="../css/cartStyle.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="../img/logo_invisible_butaca.png">
    <script src="../js/templates-js/templates-loader.js"></script>
</head>
<body>
    <main>
        <?php include(__DIR__ . "/../templates/header.php"); ?>

        <section class="bg-black">
            <!--Plantilla para cargar las películas que coinciden con la búsqueda del usuario-->
            <template id="template-pelicula">
                <div class="col-12 col-md-4 mb-4">
                    <div class="card h-100 shadow-sm bg-dark">
                        <!-- Versión escritorio (visible en md+) -->
                        <div class="d-none d-md-block">
                            <img class="card-img-top" src="" alt="Imagen película" style="height: 300px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title text-white"></h5>
                                <p class="card-text text-white"><strong>Año:</strong> <span class="anio"></span></p>
                                <p class="card-text text-white"><strong>Director:</strong> <span class="director"></span></p>
                                <p class="card-text small  text-white"><span class="descripcion"></span></p>
                                <div class="d-flex justify-content-between align-items-center align-content-end">
                                    <span class="h5 mb-0 precio text-white"></span>
                                    <button data-action="add-to-cart" class="btn btn-primary btn-alquilar">Alquilar</button>
                                </div>
                            </div>
                        </div>

                        <!-- Versión móvil (visible solo en sm-) -->
                        <div class="d-md-none">
                            <div class="row g-0">
                                <div class="col-4">
                                    <img class="img-fluid h-100" src="" alt="Imagen película" style="object-fit: cover;">
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h6 class="card-title fw-bold mb-1 text-white"></h6>
                                        <p class="card-text small mb-1 text-white">
                                            <span class="anio"></span> | <span class="director"></span>
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="small fw-bold precio text-white"></span>
                                            <button data-action="add-to-cart" class="btn btn-sm btn-primary btn-alquilar">Alquilar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <div class="container-fluid px-4">
                <div id="resultados" class="row gy-4"></div>
                <div id="sinResultados" class="text-warning"></div>
            </div>
        </section>

        <?php include(__DIR__ . "/../templates/footer.html"); ?>
    </main>

<script src="../recursos/bootstrap.bundle.min.js"></script>
<script type="module" src="../js/main.js"></script>
</body>
</html>