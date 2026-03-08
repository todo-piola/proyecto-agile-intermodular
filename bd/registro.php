<?php
require "conexion.php";
header('Content-Type: application/json');

$respuesta = ['success' => false, 'errores' => []];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $password = $_POST['password'] ?? '';

    // Validaciones
    if ($nombre === '' || !preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $nombre)) {
        $respuesta['errores'][] = 'Nombre inválido';
    }

    if ($correo === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $respuesta['errores'][] = 'Correo inválido';
    }

    if ($password === '' || strlen($password) < 8) {
        $respuesta['errores'][] = 'Contraseña inválida';
    }

    // Verificar correo duplicado
    if (empty($respuesta['errores'])) {
        $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo=?");
        $stmt->execute([$correo]);
        if ($stmt->fetch()) $respuesta['errores'][] = "Correo ya registrado";
    }

    // Insertar usuario
    if (empty($respuesta['errores'])) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conexion->prepare("INSERT INTO usuarios (nombre, correo, contrasenia) VALUES (?, ?, ?)");
        if ($stmt->execute([$nombre, $correo, $hash])) {
            $respuesta['success'] = true;
        } else {
            $respuesta['errores'][] = 'Error al registrar usuario';
        }
    }
}

echo json_encode($respuesta);
?>