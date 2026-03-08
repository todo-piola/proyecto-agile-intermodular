<?php
session_start();
require_once "../bd/conexion.php";

$stmt = $conexion->query("SELECT generos FROM peliculas");
$generosUnicos = [];

while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $lista = explode(",", $fila['generos']);
    foreach ($lista as $g) {
        $g = trim($g);
        if (!in_array($g, $generosUnicos)) {
            $generosUnicos[] = $g;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Géneros</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-dark text-white">
    <div class="container mt-5">
        <h1 class="mb-4">Géneros de Películas</h1>
        <div class="row">
            <?php foreach ($generosUnicos as $genero): ?>
                <div class="col-md-3 mb-4">
                    <div class="card text-center shadow">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($genero) ?></h5>
                            <a href="peliculas_genero.php?genero=<?= urlencode($genero) ?>" class="btn btn-primary">Ver películas</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <a href="../index.php" class="btn btn-light mt-3">Inicio</a>
    </div>
</body>
</html>