<?php
session_start();
session_unset();
session_destroy();

// Borrar cookie
setcookie("usuario_cookie", "", time() - 3600, "/");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
</head>
<body>
    <script>
        // Limpiar carrito del localStorage
        localStorage.removeItem('proyecto-agile-intermodular-cart');

        // Redirigir al index
        window.location.href = "../index.php";
    </script>
</body>
</html>