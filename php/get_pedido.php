<?php
session_start();
require_once __DIR__ . "/conexion.php";
header('Content-Type: application/json; charset=utf-8');

$orderId = $_GET['id'] ?? null;

if (!$orderId) {
    http_response_code(400);
    echo json_encode(['error' => 'Falta orderId']);
    exit;
}

try {
    $stmt = $conexion->prepare("
        SELECT dp.precio, dp.fecha_inicio, dp.fecha_fin, p.id, p.titulo, p.poster, p.director
        FROM detalles_pedido dp
        JOIN peliculas p ON dp.id_pelicula = p.id
        WHERE dp.id_pedido = :orderId
    ");
    $stmt->execute([':orderId' => $orderId]);
    $items = [];

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $posterUrl = !empty($row['poster']) ? (preg_match('/^https?:\/\//', $row['poster']) ? $row['poster'] : "https://image.tmdb.org/t/p/w500" . $row['poster']) : '../img/poster-prueba.jpg';

        $items[] = [
            'id' => (int)$row['id'],
            'titulo' => $row['titulo'],
            'director' => $row['director'] ?? 'Director desconocido',
            'imagen' => $posterUrl,
            'precio' => number_format((float)$row['precio'], 2, '.', ''),
            'fecha' => date('d/m/Y', strtotime($row['fecha_inicio']))
        ];
    }

    // Calcular total
    $total = array_sum(array_column($items, 'precio'));

    echo json_encode([
        'total' => number_format($total, 2, '.', ''),
        'items' => $items
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error obteniendo pedido: ' . $e->getMessage()]);
}
?>