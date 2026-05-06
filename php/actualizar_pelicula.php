<?php
session_start();
require_once "conexion.php";

$esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador';

if (!$esAdmin) {
    exit("No autorizado");
}


$id = $_POST['id'];
$descripcion = $_POST['descripcion'];
$presupuesto = !empty($_POST['presupuesto']) ? $_POST['presupuesto'] : 0;
$recaudacion = !empty($_POST['recaudacion']) ? $_POST['recaudacion'] : 0;

// Poster: solo actualiza si se sube uno nuevo
$posterSql = "";
$params = [$descripcion, $presupuesto, $recaudacion];

if (!empty($_FILES['poster']['name'])) {
    $nombrePoster = time() . "_" . basename($_FILES['poster']['name']);
    $rutaDestino = __DIR__ . "/../img/" . $nombrePoster;
    move_uploaded_file($_FILES['poster']['tmp_name'], $rutaDestino);
    $posterSql = ", poster=?";
    $params[] = $nombrePoster;
}

$params[] = $id;

$stmt = $conexion->prepare("
    UPDATE peliculas
    SET descripcion=?, presupuesto=?, recaudacion=? $posterSql
    WHERE id=?
");
$stmt->execute($params);

header("Location: ../index.php?route=pelicula&id=" . $id);
exit;