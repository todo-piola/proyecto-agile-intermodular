<?php
session_start();
require "conexion.php";

if (isset($_POST['login'])) {
    // Recoger los datos del formulario
    $correo = trim($_POST['correo'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $aceptarCookies = isset($_POST['crear']);

    // Validaciones básicas
    if (empty($correo) || empty($password)) {
        $error = "Todos los campos son obligatorios";
    } elseif (!$aceptarCookies) {
        $error = "Debes aceptar la política de cookies";
    } else {
        try {
            // Buscar usuario por correo
            $sql = "SELECT * FROM usuarios WHERE correo = :correo LIMIT 1";
            $stmt = $conexion->prepare($sql);
            $stmt->bindParam(":correo", $correo);
            $stmt->execute();

            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($usuario && password_verify($password, $usuario['contrasenia'])) {
                // Login correcto
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['nombre_completo'] = $usuario['nombre_completo'];
                $_SESSION['correo'] = $usuario['correo'];
                $_SESSION['rol'] = $usuario['rol']; 

                // Crear cookie si acepta
                if ($aceptarCookies) {
                    setcookie("usuario_cookie", $usuario['nombre_completo'], time() + 86400, "/");
                }

                // Guardar sesión en tabla sesiones
                try {
                    $stmtSesion = $conexion->prepare("INSERT INTO sesiones (nombre_usuario) VALUES (:nombre)");
                    $stmtSesion->bindParam(":nombre", $usuario['nombre_completo']);
                    $stmtSesion->execute();
                } catch (PDOException $e) {
                    // ignorar errores de sesiones
                }

                // Contar todas las sesiones
                try {
                    $stmtCount = $conexion->query("SELECT COUNT(*) FROM sesiones");
                    $totalSesiones = $stmtCount->fetchColumn();
                    setcookie("contador_sesiones", $totalSesiones, time() + 86400, "/");
                } catch (PDOException $e) {
                    setcookie("contador_sesiones", 0, time() + 86400, "/");
                }

                // Login correcto — limpiar carrito de invitado
                setcookie("clear_cart", "1", time() + 60, "/");
                $_SESSION['usuario_id'] = $usuario['id'];

                header("Location: ../index.php");
                exit;

            } else {
                $error = "Correo o contraseña incorrectos";
            }

        } catch (PDOException $e) {
            $error = "Error en la base de datos: " . $e->getMessage();
        }
    }

    if (isset($error)) {
        // Puedes mostrar el error en el modal
        echo "<script>alert('$error'); window.location='../index.php';</script>";
    }
}
?>