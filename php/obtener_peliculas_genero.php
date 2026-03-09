<?php
require_once "conexion.php";

if (!isset($_GET['genero'])) {
    die("Género no especificado");
}

$genero = $_GET['genero'];

$stmt = $conexion->prepare("SELECT * FROM peliculas WHERE generos LIKE ?");
$stmt->execute(["%$genero%"]);

$peliculas = $stmt->fetchAll(PDO::FETCH_ASSOC);

return [
    "genero" => $genero,
    "peliculas" => $peliculas
];