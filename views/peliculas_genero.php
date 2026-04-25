<?php
session_start();

$genero = $_GET['genero'] ?? 'Todos';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Películas de <?= htmlspecialchars($genero) ?></title>

    <script type="module">
        import RefreshRuntime from 'http://localhost:5173/@react-refresh'
        RefreshRuntime.injectIntoGlobalHook(window)
        window.$RefreshReg$ = () => {}
        window.$RefreshSig$ = () => (type) => type
        window.__vite_plugin_react_preamble_installed__ = true
    </script>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/estilo.css" rel="stylesheet">
    <link href="css/estilo-cine.css" rel="stylesheet">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

    <link rel="icon" type="image/png" href="img/logo_invisible_butaca.png">
</head>

<body>

<div class="contenedor-fondo-peliculas">
    <img id="fondo-peliculas" src="img/view-peliculas-fondo.webp">
    <div class="capa-oscura"></div>
</div>

<?php include __DIR__ . "/../templates/header.php"; ?>

<main class="container py-5 position-relative">

    <h1 class="text-center titulo-cine-grande mb-5">
        Películas de <?= htmlspecialchars($genero) ?>
    </h1>

    <!-- AQUÍ REACT MONTA TODO -->
    <div
        id="movie-gallery"
        data-genero="<?= htmlspecialchars($genero) ?>">
    </div>

    <div class="text-center mt-5">
        <a href="index.php?route=listas" class="btn btn-cine">
            Volver a listas
        </a>
    </div>

</main>

<?php include __DIR__ . "/../templates/footer.html"; ?>

<script type="module" src="http://localhost:5173/src/main.jsx"></script>

<script src="recursos/bootstrap.bundle.min.js"></script>
</body>
</html>