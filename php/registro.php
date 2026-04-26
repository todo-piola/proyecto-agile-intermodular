<?php
// php/registro.php
header('Content-Type: application/json');
require "conexion.php";

// Lee el JSON enviado desde React en lugar de $_POST
$datos = json_decode(file_get_contents('php://input'), true) ?? [];

$nombreCompleto = trim($datos["nombre"]   ?? "");
$correo         = trim($datos["correo"]   ?? "");
$password       =      $datos["password"] ?? "";
$pais           =      $datos["pais"]     ?? "";
$sexo           = !empty($datos["sexo"])  ? $datos["sexo"]  : null;
$fecha          = !empty($datos["fecha"]) ? $datos["fecha"] : null;
$tarjeta        = trim($datos["tarjeta"]  ?? "");
$notificaciones = !empty($datos["notificaciones"]) ? 1 : 0;
$revista        = !empty($datos["revista"])        ? 1 : 0;

$errores = [];

// Nombre: obligatorio y solo letras/espacios
if ($nombreCompleto === "") {
    $errores[] = "El nombre completo es obligatorio.";
} elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $nombreCompleto)) {
    $errores[] = "El nombre completo solo puede contener letras y espacios.";
}

// Correo: obligatorio y formato válido
if ($correo === "") {
    $errores[] = "El correo es obligatorio.";
} elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El formato del correo no es válido.";
}

// Contraseña: obligatoria y mínimo 8 caracteres
if ($password === "") {
    $errores[] = "La contraseña es obligatoria.";
} elseif (strlen($password) < 8) {
    $errores[] = "La contraseña debe tener al menos 8 caracteres.";
}

// Tarjeta: opcional, entre 12 y 19 dígitos si se proporciona
if ($tarjeta !== "" && !preg_match('/^[0-9]{12,19}$/', $tarjeta)) {
    $errores[] = "La tarjeta debe contener entre 12 y 19 números.";
}

// Comprueba que el correo no esté ya registrado
if (empty($errores)) {
    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    if ($stmt->fetch()) {
        $errores[] = "Este correo ya está registrado.";
    }
}

// Inserta el usuario si no hay errores
if (empty($errores)) {
    // Nunca se guarda la contraseña en texto plano, siempre hasheada
    $password_hash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $sql = "INSERT INTO usuarios
                    (nombre_completo, correo, contrasenia, sexo, fecha_nacimiento, pais, notificaciones, revista, tarjeta)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            $nombreCompleto, $correo, $password_hash,
            $sexo, $fecha, $pais,
            $notificaciones, $revista, $tarjeta
        ]);
    } catch (PDOException $e) {
        $errores[] = "Error al registrar: " . $e->getMessage();
    }
}

echo json_encode([
    "success" => empty($errores),
    "errores" => $errores
]);