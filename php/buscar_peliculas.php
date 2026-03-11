<?php
ob_start();
require_once "conexion.php";
ob_end_clean();

header('Content-Type: application/json; charset=utf-8');

$query = trim($_GET['query'] ?? '');
$page = max(1, (int)($_GET['page'] ?? 1));
$perPage = 20;
$offset = ($page - 1) * $perPage;

if ($query === '' || strlen($query) < 2) {
    echo json_encode(['results' => []], JSON_UNESCAPED_UNICODE);
    exit;
}

try {
    $search = '%' . $query . '%';

    $directorColumnStmt = $conexion->prepare("SHOW COLUMNS FROM peliculas LIKE 'director'");
    $directorColumnStmt->execute();
    $hasDirectorColumn = (bool)$directorColumnStmt->fetch(PDO::FETCH_ASSOC);

    $selectDirector = $hasDirectorColumn ? ', director' : '';
    $whereDirector = $hasDirectorColumn ? ' OR director LIKE :search' : '';

    $sql = "SELECT id, titulo, fecha_estreno, descripcion, poster{$selectDirector}
            FROM peliculas
            WHERE titulo LIKE :search
               OR descripcion LIKE :search
               {$whereDirector}
            ORDER BY fecha_estreno DESC
            LIMIT :limit OFFSET :offset";

    $stmt = $conexion->prepare($sql);
    $stmt->bindValue(':search', $search, PDO::PARAM_STR);
    $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $results = array_map(function ($movie) {
        $poster = $movie['poster'] ?? '';
        $director = $movie['director'] ?? '';
        $imagen = '../img/poster-prueba.jpg';

        if (!empty($poster)) {
            if (preg_match('/^https?:\/\//i', $poster)) {
                $imagen = $poster;
            } else {
                $imagen = 'https://image.tmdb.org/t/p/w500' . $poster;
            }
        }

        return [
            'id' => (int)$movie['id'],
            'titulo' => $movie['titulo'] ?: 'Título desconocido',
            'fecha' => $movie['fecha_estreno'] ?: 'Fecha desconocida',
            'descripcion' => $movie['descripcion'] ?: 'Sin descripcion',
            'imagen' => $imagen,
            'precio' => '3,99 EUR',
            'director' => $director !== '' ? $director : 'Director desconocido'
        ];
    }, $rows);

    echo json_encode(['results' => $results], JSON_UNESCAPED_UNICODE);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'results' => [],
        'error' => 'Error interno del servidor'
    ], JSON_UNESCAPED_UNICODE);
}
