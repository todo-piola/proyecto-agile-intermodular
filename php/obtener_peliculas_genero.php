<?php

require_once "conexion.php";

header('Content-Type: application/json; charset=utf-8');

if (!isset($_GET['genero'])) {
    echo json_encode([
        "error" => "Género no especificado",
        "peliculas" => []
    ]);
    exit;
}

$genero = $_GET['genero'];

try {
    $stmt = $conexion->prepare("
        SELECT *
        FROM peliculas
        WHERE generos LIKE ?
    ");

    $stmt->execute(["%$genero%"]);
    $peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        "genero" => $genero,
        "peliculas" => $peliculas
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "error" => $e->getMessage(),
        "peliculas" => []
    ]);
}