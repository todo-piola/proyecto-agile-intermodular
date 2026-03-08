<?php
session_start();
require_once "../bd/conexion.php";

if (!isset($_GET['id'])) {
    die("Película no especificada");
}

$id = $_GET['id'];

$stmt = $conexion->prepare("SELECT * FROM peliculas WHERE id=?");
$stmt->execute([$id]);
$pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pelicula) {
    die("Película no encontrada");
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pelicula['titulo']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <img src="https://image.tmdb.org/t/p/w500<?= $pelicula['poster'] ?>" class="img-fluid rounded">
            </div>
            <div class="col-md-8">
                <h1><?= htmlspecialchars($pelicula['titulo']) ?></h1>
                <p><strong>Título original:</strong> <?= htmlspecialchars($pelicula['titulo_original']) ?></p>
                <p><strong>Géneros:</strong> <?= htmlspecialchars($pelicula['generos']) ?></p>
                <p><strong>Duración:</strong> <?= $pelicula['duracion'] ?> minutos</p>
                <p><strong>Fecha de estreno:</strong> <?= $pelicula['fecha_salida'] ?></p>
                <p><strong>Puntuación:</strong> ⭐ <?= $pelicula['puntuacion'] ?>/10</p>
                <hr>
                <p><?= htmlspecialchars($pelicula['descripcion']) ?></p>
                <a href="javascript:history.back()" class="btn btn-light mt-3">Volver</a>
            </div>
        </div>
    </div>
</body>
</html>