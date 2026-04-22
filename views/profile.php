<?php require_once(__DIR__ . "/../php/conexion.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <title>Perfil</title>

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
    <link href="css/estilo_perfil.css" rel="stylesheet">
    <link href="css/estilo-cine.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="icon" type="image/png" href="img/logo_invisible_butaca.png">

    <script src="js/templates-js/templates-loader.js"></script>
</head>
<body>
    <main>
        <?php include(__DIR__ . "/../templates/header.php"); ?>

        <section>

        </section>

            <section class="mt-5">
                <div class="row">
                    <div class="col col-md">
                        <div class="d-flex justify-content-center">
                            <img src="img/icono_perfil.webp" alt="Foto de perfil" class="border rounded-circle" style="width: 12%;">
                            <p id="nomUsuario" class="d-flex align-self-end">Nombre del usuario que luego sacaré con JS</p>
                        </div>
                    </div>
                </div>
            </section>
            <section class="mt-5">
                <h3 class="ms-5">Actividad reciente</h3>
                <div class="linea-centrada mb-4"></div> 
                
                <div class="container-fluid px-5"> 
                    <div class="row g-4 justify-content-center">
                        <div class="col-6 col-md-3">
                            <img src="img/poster-prueba.jpg" alt="" class="img-fluid rounded"> 
                        </div>
                        <div class="col-6 col-md-3">
                            <img src="img/poster-prueba.jpg" alt="" class="img-fluid rounded">
                        </div>
                        <div class="col-6 col-md-3">
                            <img src="img/poster-prueba.jpg" alt="" class="img-fluid rounded">
                        </div>
                        <div class="col-6 col-md-3">
                            <img src="img/poster-prueba.jpg" alt="" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </section>

            <section class="my-5">
                <h3 class="ms-5">Actividad reciente</h3>
                <div class="linea-centrada mb-4"></div> 
                
                <div class="container-fluid px-5"> 
                    <div class="row g-4 justify-content-center"> 
                        <div class="col-6 col-md-3">
                            <img src="img/poster-prueba.jpg" alt="" class="img-fluid rounded">
                        </div>
                        <div class="col-6 col-md-3">
                            <img src="img/poster-prueba.jpg" alt="" class="img-fluid rounded">
                        </div>
                        <div class="col-6 col-md-3">
                            <img src="img/poster-prueba.jpg" alt="" class="img-fluid rounded">
                        </div>
                        <div class="col-6 col-md-3">
                            <img src="img/poster-prueba.jpg" alt="" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </section>

            <section class="my-4">
                <div class="linea-sin-centrar mb-4"></div>
                
                <div>
                    <h3 class="ms-5 mb-4">Amigos</h3>
                    
                    <div class="container-fluid">

                        <div class="row g-4 justify-content-center mb-4">
                            <div class="col-4 col-md-4">
                                <div class="d-flex justify-content-center">
                                    <img src="img/icono_perfil.webp" alt="Foto de perfil de amigo" 
                                        class="rounded-circle img-fluid" 
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-4 col-md-4">
                                <div class="d-flex justify-content-center">
                                    <img src="img/icono_perfil.webp" alt="Foto de perfil de amigo" 
                                        class="rounded-circle img-fluid" 
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-4 col-md-4">
                                <div class="d-flex justify-content-center">
                                    <img src="img/icono_perfil.webp" alt="Foto de perfil de amigo" 
                                        class="rounded-circle img-fluid" 
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                        

                        <div class="row g-4 justify-content-center">
                            <div class="col-4 col-md-4">
                                <div class="d-flex justify-content-center">
                                    <img src="img/icono_perfil.webp" alt="Foto de perfil de amigo" 
                                        class="rounded-circle img-fluid" 
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-4 col-md-4">
                                <div class="d-flex justify-content-center">
                                    <img src="img/icono_perfil.webp" alt="Foto de perfil de amigo" 
                                        class="rounded-circle img-fluid" 
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                            </div>
                            <div class="col-4 col-md-4">
                                <div class="d-flex justify-content-center">
                                    <img src="img/icono_perfil.webp" alt="Foto de perfil de amigo" 
                                        class="rounded-circle img-fluid" 
                                        style="width: 120px; height: 120px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        <?php include(__DIR__ . "/../templates/footer.html"); ?>
    </main>

<script src="recursos/bootstrap.bundle.min.js"></script>
<script type="module" src="js/main.js"></script>
</body>
</html>