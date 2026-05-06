
<?php
session_start();
require_once "conexion.php";
 
// Solo el admin puede ejecutar esta acción
if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'administrador') {
    exit("No autorizado");
}
 
$id              = (int) $_POST['id'];
$nombre_completo = trim($_POST['nombre_completo']);
$correo          = trim($_POST['correo']);
$sexo            = $_POST['sexo'] ?? null;
$contrasenia     = trim($_POST['contrasenia']);
$rol             = $_POST['rol'] ?? 'usuario';
 
// Asegurarse de que el rol es un valor válido
if (!in_array($rol, ['usuario', 'administrador'])) {
    $rol = 'usuario';
}
 
// Validación mínima
if (!$id || !$nombre_completo || !$correo) {
    header("Location: ../index.php?route=perfil&error=Datos+incompletos");
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
        SET nombre_completo = ?, correo = ?, sexo = ?, rol = ?, contrasenia = ?
        WHERE id = ?
    ");
    $stmt->execute([$nombre_completo, $correo, $sexo ?: null, $rol, $hash, $id]);
} else {
    $stmt = $conexion->prepare("
        UPDATE usuarios
        SET nombre_completo = ?, correo = ?, sexo = ?, rol = ?
        WHERE id = ?
    ");
    $stmt->execute([$nombre_completo, $correo, $sexo ?: null, $rol, $id]);
}
 
header("Location: ../index.php?route=perfil&ok=1");
exit;
