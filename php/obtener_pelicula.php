<?php
require_once "conexion.php";

if (!isset($_GET['id'])) {
    die("Película no especificada");
}

$id = $_GET['id'];

/* =========================
   PELÍCULA
========================= */
$stmt = $conexion->prepare("SELECT * FROM peliculas WHERE id=?");
$stmt->execute([$id]);

$pelicula = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pelicula) {
    die("Película no encontrada");
}

/* =========================
   TRAILER
========================= */
$iframeUrl = null;

if (!empty($pelicula['trailer_url'])) {
    parse_str(parse_url($pelicula['trailer_url'], PHP_URL_QUERY), $youtubeParams);

    if (isset($youtubeParams['v'])) {
        $iframeUrl = "https://www.youtube.com/embed/" . $youtubeParams['v'];
    }
}

/* =========================
   RESEÑAS
========================= */
$stmtResenas = $conexion->prepare("
    SELECT r.*, u.nombre_completo
    FROM resenas r
    JOIN usuarios u ON r.id_usuario = u.id
    WHERE r.id_pelicula = ?
    ORDER BY r.fecha DESC
");

$stmtResenas->execute([$id]);
$resenas = $stmtResenas->fetchAll(PDO::FETCH_ASSOC);

/* =========================
   RETURN FINAL
========================= */
return [
    "pelicula" => $pelicula,
    "iframeUrl" => $iframeUrl,
    "resenas" => $resenas
];