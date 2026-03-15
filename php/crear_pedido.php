<?php
session_start();
require_once __DIR__ . "/conexion.php";
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Método no permitido']);
    exit;
}

// Verificar usuario logueado
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Usuario no autenticado']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$total = $data['total'] ?? 0;

if ($total <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Total inválido']);
    exit;
}

try {
    $stmt = $conexion->prepare("INSERT INTO pedidos (id_usuario, total, fecha_pedido) VALUES (:id_usuario, :total, NOW())");
    $stmt->execute([
        ':id_usuario' => $_SESSION['usuario_id'],
        ':total' => $total
    ]);

    $orderId = $conexion->lastInsertId();
    echo json_encode(['orderId' => $orderId]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error creando pedido: ' . $e->getMessage()]);
}
?>