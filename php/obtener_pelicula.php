<?php
require_once "conexion.php";

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

$iframeUrl = null;

if (!empty($pelicula['trailer_url'])) {

    parse_str(parse_url($pelicula['trailer_url'], PHP_URL_QUERY), $youtubeParams);

    if (isset($youtubeParams['v'])) {
        $iframeUrl = "https://www.youtube.com/embed/" . $youtubeParams['v'];
    }
}

return [
    "pelicula" => $pelicula,
    "iframeUrl" => $iframeUrl
];