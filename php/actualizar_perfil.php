<?php
session_start();
require_once "conexion.php";

// Solo usuarios autenticados
if (!isset($_SESSION['usuario_id'])) {
    exit("No autorizado");
}

$id              = (int) $_SESSION['usuario_id']; // Siempre el usuario de la sesión, nunca del POST
$nombre_completo = trim($_POST['nombre_completo']);
$correo          = trim($_POST['correo']);
$sexo            = $_POST['sexo'] ?? null;
$contrasenia     = trim($_POST['contrasenia']);

// Validación mínima
if (!$nombre_completo || !$correo) {
    header("Location: ../index.php?route=perfil&error=Nombre+y+correo+son+obligatorios");
    exit;
}

// Comprobar que el correo no lo usa ya otro usuario
$stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ? AND id != ?");
$stmt->execute([$correo, $id]);
if ($stmt->fetch()) {
    header("Location: ../index.php?route=perfil&error=Ese+correo+ya+está+en+uso");
    exit;
}

// Actualizar con o sin contraseña nueva
if ($contrasenia !== "") {
    $hash = password_hash($contrasenia, PASSWORD_DEFAULT);
    $stmt = $conexion->prepare("
        UPDATE usuarios
        SET nombre_completo = ?, correo = ?, sexo = ?, contrasenia = ?
        WHERE id = ?
    ");
    $stmt->execute([$nombre_completo, $correo, $sexo ?: null, $hash, $id]);
} else {
    $stmt = $conexion->prepare("
        UPDATE usuarios
        SET nombre_completo = ?, correo = ?, sexo = ?
        WHERE id = ?
    ");
    $stmt->execute([$nombre_completo, $correo, $sexo ?: null, $id]);
}

// Actualizar el nombre en sesión para que el header lo refleje al momento
$_SESSION['nombre_completo'] = $nombre_completo;

header("Location: ../index.php?route=perfil&ok=1");
exit;