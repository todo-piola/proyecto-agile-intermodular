<?php require_once(__DIR__ . "/../php/conexion.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link href="../css/estilo.css" rel="stylesheet">
    <link href="../css/cartStyle.css" rel="stylesheet">
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="../img/logo_invisible_butaca.png">
    <script src="../js/templates-js/templates-loader.js"></script>
</head>
<body>
    <main>
        <?php include(__DIR__ . "/../templates/header.php"); ?>

            <section class="d-flex gap-3">

                <!--Lista de productos-->
                <div id="product-list" class="col col-md-9 d-flex flex-column gap-3">

                    <!--Template como el de search PHP para clonar en JS-->
                    <div class="templateResume" style="display: none;">
                        <div class="row align-items-center">
                            <!-- Columna foto -->
                            <div class="col col-md-3">
                                <img src="" alt="" class="img-fluid">
                            </div>
                            <!-- Columna información -->
                            <div class="col col-md-6">
                                <h3 class="card-title"></h3>
                                <p class="card-director-name"></p>
                                <p class="rent-date"></p>
                                <p class="price-rent"></p>
                            </div>
                            <!--Botón eliminar
                            <div class="col col-md-3 d-flex justify-content-end align-items-center">
                                <button class="delete-button btn btn-danger">Eliminar</button>
                            </div>-->
                        </div>
                    </div>
                </div>

                <!--Resumen-->
                <div class="col col-md-3 align-self-start">
                    <div class="card p-3">
                        <h5>Resumen</h5>
                        <p>Total: <strong id="total-price"></strong></p>
                        <a href="/proyecto-agile-intermodular/index.php" class="btn btn-warning">
                        Seguir alquilando
                        </a>
                    </div>
                </div>
            </section>


        <?php include(__DIR__ . "/../templates/footer.html"); ?>
    </main>

<script src="../recursos/bootstrap.bundle.min.js"></script>
<script type="module" src="../js/main.js"></script>
</body>
</html>