<?php
session_start();
require_once "../bd/conexion.php";

if (!isset($_GET['genero'])) {
    die("Género no especificado");
}

$genero = $_GET['genero'];

$stmt = $conexion->prepare("SELECT * FROM peliculas WHERE generos LIKE ?");
$stmt->execute(["%$genero%"]);
$peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Películas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <div class="container mt-5">
        <h1 class="mb-4">Películas de <?= htmlspecialchars($genero) ?></h1>
        <div class="row">
            <?php foreach ($peliculas as $p): ?>
                <div class="col-md-3 mb-4">
                    <a href="pelicula.php?id=<?= $p['id'] ?>" style="text-decoration:none;color:inherit;">
                        <div class="card h-100 shadow">
                            <img src="https://image.tmdb.org/t/p/w500<?= $p['poster'] ?>" class="card-img-top">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($p['titulo']) ?></h5>
                                <p class="card-text">⭐ <?= $p['puntuacion'] ?>/10</p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="listas.php" class="btn btn-light mt-3">Volver a listas</a>
    </div>
</body>
</html>