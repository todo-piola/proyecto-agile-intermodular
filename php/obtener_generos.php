<?php
require_once "conexion.php";

$stmt = $conexion->query("SELECT generos FROM peliculas");
$generosUnicos = [];

while ($fila = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $lista = explode(",", $fila['generos']);
    foreach ($lista as $g) {
        $g = trim($g);
        if (!in_array($g, $generosUnicos) && $g !== "") {
            $generosUnicos[] = $g;
        }
    }
}

return $generosUnicos;