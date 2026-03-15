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
    die("Conexión fallida: " . $e->getMessage());
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
            nombre_completo VARCHAR(100) NOT NULL,
            telefono VARCHAR(9),
            correo VARCHAR(100) NOT NULL UNIQUE,
            contrasenia VARCHAR(255) NOT NULL,
            sexo ENUM('masculino','femenino','prefiero-no-decir'),
            fecha_nacimiento DATE,
            pais VARCHAR(100),
            notificaciones TINYINT(1),
            revista TINYINT(1),
            tarjeta VARCHAR(25),
            imagen_usuario VARCHAR(255)
        );
    ");
} catch (PDOException $e) {
    die("Error creando tabla usuarios: " . $e->getMessage());
}

//////////////////////////////////////////////////////
// TABLA PELICULAS
//////////////////////////////////////////////////////
try {
    $conexion->exec("
        CREATE TABLE IF NOT EXISTS peliculas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(255),
            descripcion TEXT,
            generos VARCHAR(255),
            fecha_estreno DATE,
            duracion_minutos INT,
            poster VARCHAR(255),
            fondo VARCHAR(255),
            puntuacion DECIMAL(3,1),
            presupuesto BIGINT,
            recaudacion BIGINT,
            frase_promocional VARCHAR(255),
            trailer_url VARCHAR(255),
            director VARCHAR(255),
            reparto TEXT,
            fotos_reparto TEXT
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
    $stmt = $conexion->query("SELECT * FROM usuarios WHERE correo='admin@admin.com' LIMIT 1");
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        $passwordHash = password_hash("1234", PASSWORD_DEFAULT);
        $stmtInsert = $conexion->prepare("
            INSERT INTO usuarios (nombre_completo, correo, contrasenia)
            VALUES ('Administrador', 'admin@admin.com', :pass)
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
    $conexion->exec("
        CREATE TABLE IF NOT EXISTS sesiones (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre_usuario VARCHAR(100),
            fecha_inicio TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB;
    ");
} catch (PDOException $e) {
    die("No se puede crear la tabla sesiones: " . $e->getMessage());
}

//////////////////////////////////////////////////////
// IMPORTAR PELICULAS DESDE TMDB CON DIRECTOR Y REPARTO
//////////////////////////////////////////////////////
try {

    $stmt = $conexion->query("SELECT COUNT(*) FROM peliculas");
    $totalPeliculasBD = $stmt->fetchColumn();

    if ($totalPeliculasBD == 0) {

        $apiKey = "4c9ba4a79b657a025515aa64567b103b";
        $totalPeliculas = 0;
        $pagina = 1;

        $insertStmt = $conexion->prepare("
            INSERT INTO peliculas
            (titulo, descripcion, generos, fecha_estreno, duracion_minutos, poster, fondo, puntuacion, presupuesto, recaudacion, frase_promocional, trailer_url, director, reparto, fotos_reparto)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        while ($totalPeliculas < 150) {

            $url = "https://api.themoviedb.org/3/movie/popular?api_key=$apiKey&language=es-ES&page=$pagina";
            $response = file_get_contents($url);
            $data = json_decode($response, true);

            foreach ($data['results'] as $movie) {

                $movieId = $movie['id'];
                $urlDetail = "https://api.themoviedb.org/3/movie/$movieId?api_key=$apiKey&language=es-ES";
                $detailResponse = file_get_contents($urlDetail);
                $detailData = json_decode($detailResponse, true);

                $titulo = $detailData['original_title'];
                $descripcion = $detailData['overview'];
                $fecha_estreno = $detailData['release_date'];
                $poster = $detailData['poster_path'];
                $fondo = $detailData['backdrop_path'];
                $puntuacion = $detailData['vote_average'];
                $duracion_minutos = $detailData['runtime'];
                $presupuesto = $detailData['budget'];
                $recaudacion = $detailData['revenue'];
                $frase_promocional = $detailData['tagline'];

                // Géneros
                $generosArray = [];
                if (isset($detailData['genres'])) {
                    foreach ($detailData['genres'] as $g) {
                        $generosArray[] = $g['name'];
                    }
                }
                $generos = implode(", ", $generosArray);

                // Trailer YouTube
                $urlTrailer = "https://api.themoviedb.org/3/movie/$movieId/videos?api_key=$apiKey&language=es-ES";
                $trailerResponse = file_get_contents($urlTrailer);
                $trailerData = json_decode($trailerResponse, true);
                $trailerUrl = null;
                foreach ($trailerData['results'] as $video) {
                    if ($video['type'] === 'Trailer' && $video['site'] === 'YouTube') {
                        $trailerUrl = "https://www.youtube.com/watch?v=" . $video['key'];
                        break;
                    }
                }

                // Créditos: director y reparto
                $urlCredits = "https://api.themoviedb.org/3/movie/$movieId/credits?api_key=$apiKey&language=es-ES";
                $creditsResponse = file_get_contents($urlCredits);
                $creditsData = json_decode($creditsResponse, true);

                // Director
                $director = null;
                foreach ($creditsData['crew'] as $crewMember) {
                    if ($crewMember['job'] === 'Director') {
                        $director = $crewMember['name'];
                        break;
                    }
                }

                // Reparto principal (5 actores)
                $reparto = [];
                $fotos_reparto = [];
                foreach (array_slice($creditsData['cast'], 0, 5) as $actor) {
                    $reparto[] = $actor['name'] . " como " . $actor['character'];
                    $fotos_reparto[] = $actor['profile_path'] ? "https://image.tmdb.org/t/p/w300" . $actor['profile_path'] : null;
                }

                // Insertar película
                $insertStmt->execute([
                    $titulo,
                    $descripcion,
                    $generos,
                    $fecha_estreno,
                    $duracion_minutos,
                    $poster,
                    $fondo,
                    $puntuacion,
                    $presupuesto,
                    $recaudacion,
                    $frase_promocional,
                    $trailerUrl,
                    $director,
                    json_encode($reparto),
                    json_encode($fotos_reparto)
                ]);

                $totalPeliculas++;
                if ($totalPeliculas >= 30) break;
            }

            $pagina++;
        }
    }

} catch (PDOException $e) {
    echo "Error importando peliculas: " . $e->getMessage();
}

?>