<?php
session_start();
require "conexion.php";

/* =========================
   SEGURIDAD: USUARIO LOGEADO
========================= */
if (!isset($_SESSION['usuario_id'])) {
    die("Debes iniciar sesión");
}

/* =========================
   DATOS POST
========================= */
$comentario = trim($_POST['comentario'] ?? '');
$id_pelicula = (int)($_POST['id_pelicula'] ?? 0);
$id_usuario = (int)$_SESSION['usuario_id'];

/* =========================
   VALIDACIONES
========================= */
if (empty($comentario)) {
    die("El comentario no puede estar vacío");
}

if ($id_pelicula <= 0) {
    die("Película no válida");
}

/* =========================
   INSERTAR RESEÑA
========================= */
try {
    $stmt = $conexion->prepare("
        INSERT INTO resenas (comentario, id_usuario, id_pelicula)
        VALUES (?, ?, ?)
    ");

    $stmt->execute([$comentario, $id_usuario, $id_pelicula]);

    header("Location: ../index.php?route=pelicula&id=" . $id_pelicula);
    exit;

} catch (PDOException $e) {
    die("Error al guardar la reseña: " . $e->getMessage());
}