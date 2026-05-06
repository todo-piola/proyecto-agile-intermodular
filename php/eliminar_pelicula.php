<?php
session_start();
require_once "conexion.php";


$esAdmin = isset($_SESSION['rol']) && $_SESSION['rol'] === 'administrador';

if (!$esAdmin) {
    exit("No autorizado");
}


$id = $_POST['id'];
$titulo = $_POST['titulo'];

$stmt = $conexion->prepare("DELETE FROM peliculas WHERE id=?");
$stmt->execute([$id]);

$_SESSION['mensaje'] = "La película '$titulo' fue eliminada correctamente";

header("Location: ../index.php?route=movies");
exit;