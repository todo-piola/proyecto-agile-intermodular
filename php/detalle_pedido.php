<?php
session_start();
require_once __DIR__ . "/conexion.php";
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$orderId = $data['orderId'] ?? null;
$itemsData = $data['itemsData'] ?? [];

if (!$orderId || !is_array($itemsData) || count($itemsData) === 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Datos inválidos']);
    exit;
}

try {
    $stmt = $conexion->prepare("INSERT INTO detalles_pedido (id_pedido, id_pelicula, precio, fecha_inicio, fecha_fin) VALUES (:id_pedido, :id_pelicula, :precio, NOW(), DATE_ADD(NOW(), INTERVAL 14 DAY))");

    foreach ($itemsData as $item) {
        $stmt->execute([
            ':id_pedido' => $orderId,
            ':id_pelicula' => $item['movieId'],
            ':precio' => $item['precio'] ?? 0
        ]);
    }

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error guardando detalles: ' . $e->getMessage()]);
}
?>