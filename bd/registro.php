<?php
// bd/registro.php
header('Content-Type: application/json');
require "conexion.php";

// Inicializamos array de errores
$errores = [];

// Tomamos datos del POST
$nombreCompleto = trim($_POST["nombre"] ?? "");
$telefono       = trim($_POST["telefono"] ?? "");
$correo         = trim($_POST["correo"] ?? "");
$password       = $_POST["password"] ?? "";
$pais           = $_POST["pais"] ?? "";
$sexo           = $_POST["sexo"] ?? "";
$fecha          = $_POST["fecha"] ?? "";
$notificaciones = !empty($_POST["notificaciones"]) ? 1 : 0;
$revista        = !empty($_POST["revista"]) ? 1 : 0;

// ===== VALIDACIONES =====

// Nombre completo obligatorio y solo letras y espacios
if($nombreCompleto === "") {
    $errores[] = "El nombre completo es obligatorio.";
} elseif(!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ ]+$/', $nombreCompleto)) {
    $errores[] = "El nombre completo solo puede contener letras y espacios.";
}

// Correo obligatorio y formato válido
if($correo === "") {
    $errores[] = "El correo es obligatorio.";
} elseif(!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El formato del correo no es válido.";
}

// Teléfono opcional, 9 números
if($telefono !== "" && !preg_match('/^[0-9]{9}$/', $telefono)) {
    $errores[] = "El teléfono debe tener 9 números.";
}

// Contraseña obligatoria y mínimo 6 caracteres
if($password === "") {
    $errores[] = "La contraseña es obligatoria.";
} elseif(strlen($password) < 6) {
    $errores[] = "La contraseña debe tener al menos 6 caracteres.";
}

// ===== COMPROBAR CORREO DUPLICADO =====
if(empty($errores)) {
    $stmt = $conexion->prepare("SELECT id FROM usuarios WHERE correo = ?");
    $stmt->execute([$correo]);
    if($stmt->fetch()) {
        $errores[] = "Este correo ya está registrado.";
    }
}

// ===== INSERTAR USUARIO =====
if(empty($errores)){
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $sql = "INSERT INTO usuarios
                (nombre_completo, telefono, correo, contrasenia, sexo, fecha_nacimiento, pais, notificaciones, revista)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conexion->prepare($sql);
        $stmt->execute([
            $nombreCompleto, $telefono, $correo, $password_hash,
            $sexo, $fecha, $pais, $notificaciones, $revista
        ]);

    } catch(PDOException $e) {
        $errores[] = "Error al registrar: " . $e->getMessage();
    }
}

// ===== DEVOLVER JSON =====
echo json_encode([
    "success" => empty($errores),
    "errores" => $errores
]);