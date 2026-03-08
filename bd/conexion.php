<?php

$server = "localhost";
$user = "root";
$password = "";
$db = "proyecto_peliculas";

//////////////////////////////////////////////////////
// CONEXION AL SERVIDOR
//////////////////////////////////////////////////////

try {
    $conexion = new PDO("mysql:host=$server;charset=utf8mb4", $user, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Conexion fallida: " . $e->getMessage());
}

//////////////////////////////////////////////////////
// CREAR BASE DE DATOS
//////////////////////////////////////////////////////

try {
    $sqlDB = "CREATE DATABASE IF NOT EXISTS $db
              CHARACTER SET utf8mb4
              COLLATE utf8mb4_general_ci";
    $conexion->exec($sqlDB);
} catch (PDOException $e) {
    die("Error creando base de datos: " . $e->getMessage());
}

//////////////////////////////////////////////////////
// CONECTAR A LA BASE DE DATOS
//////////////////////////////////////////////////////

try {
    $conexion = new PDO("mysql:host=$server;dbname=$db;charset=utf8mb4", $user, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error conectando a la base: " . $e->getMessage());
}

//////////////////////////////////////////////////////
// TABLA USUARIOS
//////////////////////////////////////////////////////
try {
    $conexion->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100),
            apellido VARCHAR(100),
            email VARCHAR(150) UNIQUE,
            contrasena VARCHAR(255),
            pais VARCHAR(50),
            sexo VARCHAR(20),
            fecha_nacimiento DATE,
            tarjeta VARCHAR(50),
            notificaciones TINYINT(1) DEFAULT 0,
            revista_digital TINYINT(1) DEFAULT 0
        ) ENGINE=InnoDB;
    ");
} catch (PDOException $e) {
    die("Error creando tabla usuarios: " . $e->getMessage());
}

//////////////////////////////////////////////////////
// TABLA PELICULAS COMPLETA
//////////////////////////////////////////////////////

try {

    $conexion->exec("
        CREATE TABLE IF NOT EXISTS peliculas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(255),
            titulo_original VARCHAR(255),
            descripcion TEXT,
            generos VARCHAR(255),
            fecha_salida DATE,
            duracion INT,
            poster VARCHAR(255),
            backdrop VARCHAR(255),
            puntuacion DECIMAL(3,1)
        ) ENGINE=InnoDB;
    ");

} catch (PDOException $e) {
    die("Error creando tabla peliculas: " . $e->getMessage());
}

//////////////////////////////////////////////////////
// TABLA PEDIDOS
//////////////////////////////////////////////////////

try {

    $conexion->exec("
        CREATE TABLE IF NOT EXISTS pedidos (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fecha_pedido DATE,
            total DECIMAL(10,2),
            id_usuario INT,
            FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");

} catch (PDOException $e) {
    die("Error creando tabla pedidos: " . $e->getMessage());
}

//////////////////////////////////////////////////////
// TABLA DETALLES PEDIDO
//////////////////////////////////////////////////////

try {

    $conexion->exec("
        CREATE TABLE IF NOT EXISTS detalles_pedido (
            id INT AUTO_INCREMENT PRIMARY KEY,
            precio DECIMAL(10,2),
            fecha_inicio DATE,
            fecha_fin DATE,
            id_pedido INT,
            id_pelicula INT,
            FOREIGN KEY (id_pedido) REFERENCES pedidos(id) ON DELETE CASCADE,
            FOREIGN KEY (id_pelicula) REFERENCES peliculas(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");

} catch (PDOException $e) {
    die("Error creando tabla detalles_pedido: " . $e->getMessage());
}

//////////////////////////////////////////////////////
// CREAR ADMIN SI NO EXISTE
//////////////////////////////////////////////////////

try {

    $stmt = $conexion->query("SELECT * FROM usuarios WHERE email='admin@admin.com' LIMIT 1");
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {

        $passwordHash = password_hash("1234", PASSWORD_DEFAULT);

        $stmtInsert = $conexion->prepare("
            INSERT INTO usuarios (nombre, email, contrasena)
            VALUES ('admin', 'admin@admin.com', :pass)
        ");

        $stmtInsert->bindParam(":pass", $passwordHash);
        $stmtInsert->execute();
    }

} catch(PDOException $e) {
    die("Error creando admin: " . $e->getMessage());
}
//////////////////////////////////////////////////////
// TABLA SESIONES
//////////////////////////////////////////////////////

try {
    $sqlTable = "CREATE TABLE IF NOT EXISTS sesiones (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nombre_usuario VARCHAR(100),
        fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB";
    $conexion->exec($sqlTable);
} catch (PDOException $e) {
    die("No se puede crear la tabla sesiones: " . $e->getMessage());
}
//////////////////////////////////////////////////////
// IMPORTAR PELICULAS DESDE TMDB
//////////////////////////////////////////////////////

try {

    $stmt = $conexion->query("SELECT COUNT(*) FROM peliculas");
    $totalPeliculas = $stmt->fetchColumn();

    if ($totalPeliculas == 0) {

        $apiKey = "4c9ba4a79b657a025515aa64567b103b";

        $url = "https://api.themoviedb.org/3/movie/popular?api_key=$apiKey&language=es-ES&page=1";

        $response = file_get_contents($url);

        if ($response !== false) {

            $data = json_decode($response, true);

            $insertStmt = $conexion->prepare("
                INSERT INTO peliculas
                (titulo, titulo_original, descripcion, generos, fecha_salida, duracion, poster, backdrop, puntuacion)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");

            foreach ($data['results'] as $movie) {

                $movieId = $movie['id']; // ID de TMDB

                // Llamada a la API para detalles completos
                $urlDetail = "https://api.themoviedb.org/3/movie/$movieId?api_key=$apiKey&language=es-ES";
                $detailResponse = file_get_contents($urlDetail);
                $detailData = json_decode($detailResponse, true);

                $titulo = $detailData['title'];
                $tituloOriginal = $detailData['original_title'];
                $descripcion = $detailData['overview'];
                $fecha = $detailData['release_date'];
                $poster = $detailData['poster_path'];
                $backdrop = $detailData['backdrop_path'];
                $puntuacion = $detailData['vote_average'];
                $duracion = $detailData['runtime']; // duración en minutos

                // Generar nombres de géneros
                $generosArray = [];
                if (isset($detailData['genres'])) {
                    foreach ($detailData['genres'] as $g) {
                        $generosArray[] = $g['name'];
                    }
                }
                $generos = implode(", ", $generosArray);

                // Insertar en la base
                $insertStmt->execute([
                    $titulo,
                    $tituloOriginal,
                    $descripcion,
                    $generos,
                    $fecha,
                    $duracion,
                    $poster,
                    $backdrop,
                    $puntuacion
                ]);
            }
        }
    }

} catch (PDOException $e) {
    echo "Error importando peliculas: " . $e->getMessage();
}

?>