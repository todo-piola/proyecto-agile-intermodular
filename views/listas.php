<?php
session_start();
require_once "../bd/conexion.php";

// Obtener todos los géneros únicos
$stmt = $conexion->query("SELECT generos FROM peliculas");
$generosUnicos = [];

while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $lista = explode(",", $fila['generos']);
    foreach ($lista as $g) {
        $g = trim($g);
        if (!in_array($g, $generosUnicos) && $g !== "") {
            $generosUnicos[] = $g;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Géneros de Películas</title>
    <link href="../css/bootstrap.min.css" rel="stylesheet">
    <link href="../css/estilo-cine.css" rel="stylesheet">
</head>
<body>
<div class="contenedor-fondo-peliculas">
    <img id="fondo-peliculas" src="../img/view-peliculas-fondo.webp">
    <div class="capa-oscura"></div>
</div>

<main class="container py-5 position-relative">
    <h1 class="text-center titulo-cine-grande mb-5">Géneros de Películas</h1>

    <div class="row g-4 justify-content-center">
        <?php foreach ($generosUnicos as $genero): ?>
            <div class="col-6 col-sm-4 col-md-3">
                <a href="peliculas_genero.php?genero=<?= urlencode($genero) ?>" style="text-decoration:none;">
                    <div class="card-genero" style="background-image:url('../img/generos/<?= strtolower($genero) ?>.jpg'); background-size:cover; background-position:center;">
                        <h5><?= htmlspecialchars($genero) ?></h5>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center">
        <a href="../index.php" class="btn btn-cine">Volver al inicio</a>
    </div>
</main>

<script src="../recursos/bootstrap.bundle.min.js"></script>
</body>
</html>