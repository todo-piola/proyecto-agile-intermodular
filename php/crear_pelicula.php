<?php
session_start();
require_once "conexion.php";

if ($_SESSION['nombre_completo'] !== "Administrador") {
    exit("No autorizado");
}

$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
$generos = $_POST['generos'];
$director = $_POST['director'] ?? null;

$poster = null;

if (!empty($_FILES['poster']['name'])) {

    $nombrePoster = time() . "_" . basename($_FILES['poster']['name']);

    $ruta = "../img/" . $nombrePoster;

    move_uploaded_file($_FILES['poster']['tmp_name'], $ruta);

    $poster = $nombrePoster;
}

$stmt = $conexion->prepare("
INSERT INTO peliculas (titulo, descripcion, generos, poster, director)
VALUES (?, ?, ?, ?, ?)
");

$stmt->execute([$titulo, $descripcion, $generos, $poster, $director]);

$idNueva = $conexion->lastInsertId();

header("Location: ../views/pelicula.php?id=" . $idNueva);
exit;