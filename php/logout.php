<?php
session_start();
session_unset();
session_destroy();

// Borrar cookie
setcookie("usuario_cookie", "", time() - 3600, "/");

header("Location: ../index.php");
exit;