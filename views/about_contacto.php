<?php require_once(__DIR__ . "/../php/conexion.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Contacto</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estilo.css" rel="stylesheet">
    <link href="../css/estilo_about.css" rel="stylesheet">
    <link href="../css/cartStyle.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="../img/logo_invisible_butaca.png">
    <script src="../js/templates-js/templates-loader.js"></script>
</head>
<body>
    <main>
        <?php include(__DIR__ . "/../templates/header.php"); ?>
            <section>
                <div>
                    <h2 class="mt-4 ms-5 text-warning">¿Qué es la Butaca Social?</h2>
                    <div class="linea-centrada mb-4"></div>
                    <p class="fs-5 mx-5 text-justify">
                        La Butaca Social nace como un proyecto de fin de grado. El primer objetivo de esta aplicación web es el de ser un espacio de encuentro entre cinefilos,
                        donde poder compartir opiniones, recomendaciones y críticas sobre las últimas películas que han visto. Queremos crear un ágora donde la gente pueda reunirse
                        y debatir de una manera sana y constructiva sobre el séptimo arte. No importa si eres un amante del cine indie, del cine de super héroes o películas címicas.
                        En La Butaca Social todo el mundo podrá encontrar un rincón donde sentirse representado y compartir su pasión por el cine. <br>
                        El proyecto nace bajo la filosofía de representar un espacio donde reivindicar el cine como un objeto de arte político, social y de crítica. Desde el equipo
                        de desarrollo creemos que el arte es política, y por tanto, buscamos crear un espacio reivindicativo en una red donde no siempre es posible encontrar espacios
                        lgtbq+, feministas, antirracistas o de crítica social.  
                    </p>
                </div>

                <div>
                    <h2 class="mt-4 ms-5 text-warning">¿Quiénes somos?</h2>
                    <div class="linea-centrada mb-4"></div>
                    <p class="fs-5 mx-5 text-justify">
                        El desarrollo de La Butaca Social se ha llevado a cabo por un equipo de estudiantes del Grado Superior de Desarrollo Web
                        del IES Infanta Elena de Galapagar. El grupo lo forman Alberto Llera Balbuena, Franco Benavides y Rafael Cosquillo que 
                        actualmente cursan el segundo año de este grado, por tanto, la Butaca Social es parte de su proyecto intermodular. 
                    </p>
                </div>

                <div>
                    <h2 class="mt-4 ms-5 text-warning">Carácteristicas principales de La Butaca Social</h2>
                    <div class="linea-centrada mb-4"></div>
                    <p class="fs-5 mx-5 text-justify">
                        La Butaca Social destaca por su dieseño moderno que conbina una interfaz intuitiva que mezcla leguajes de marcado
                        como HTML y CSS apoyados en el framework BootStrap. En la parte de la programación se optado por utilizar PHP para el desarrollo backend
                        mientras que para el frontend se ha utilizado JavaScript. En cuanto a la base de datos se ha optado por MySQL, 
                        utilizando el gestor de bases de datos phpMyAdmin para su administración.<br>
                        En cuanto a las funcionalidades, La Butaca Social cuenta con un sistema de registro e inicio de sesión
                        que permite a los usuarios crear una cuenta personalizada. Por otro lado, los usuarios pueden crear un perfil donde compartir sus películas favoritas,
                        sus críticas y recomendaciones, además de poder seguir a otros usuarios y ver sus actividades. Por último, la butaca social cuenta con un blog donde se publicarán
                        artículos haciendo recomendaciones y reivindicando películas con un caracter social, político y ecologísta. 
                    </p>
                </div>

                <div>
                    <h2 class="mt-4 ms-5 text-warning">¿Tienes dudas o sugerencias? Pues pregúntanos sin compromiso</h2>
                    <div class="linea-centrada mb-4"></div>
                    <div class="row justify-content-center">
                        <div class="col-12 col-md-8 col-lg-6">
                            <form action="POST">
                                <div class="mb-3">
                                    <label for="exampleFormControlInput1" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="nombre@ejemplo.com">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="5"></textarea>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        <?php include(__DIR__ . "/../templates/footer.html"); ?>
    </main>

<script src="../recursos/bootstrap.bundle.min.js"></script>
<script type="module" src="../js/main.js"></script>
</body>
</html>