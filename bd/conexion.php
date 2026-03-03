<?php
$server = "localhost";
$user = "root";
$password = "";
$db = "proyecto_peliculas";

try {
    // Conectar solo al servidor
    $conexion = new PDO("mysql:host=$server;charset=utf8mb4", $user, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("Conexion fallida: " . $e->getMessage());
}

// Crear base de datos si no existe
try {
    $sqlDB = "CREATE DATABASE IF NOT EXISTS $db
              CHARACTER SET utf8mb4
              COLLATE utf8mb4_general_ci";
    $conexion->exec($sqlDB);

} catch (PDOException $e) {
    die("No se puede crear la base de datos: " . $e->getMessage());
}

// Conectar a la base de datos
try {
    $conexion = new PDO("mysql:host=$server;dbname=$db;charset=utf8mb4", $user, $password);
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    die("No se puede conectar a la base '$db': " . $e->getMessage());
}

//////////////////////////////////////////////////////
//                CREACIÓN DE TABLAS
//////////////////////////////////////////////////////

// TABLA USUARIOS
try {
    $conexion->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100),
            apellido VARCHAR(100),
            email VARCHAR(150) UNIQUE,
            contrasena VARCHAR(255),
            sexo VARCHAR(20),
            fecha_nacimiento DATE,
            activar_notificacion BOOLEAN DEFAULT FALSE,
            recibir_revista BOOLEAN DEFAULT FALSE,
            pais VARCHAR(100),
            num_tarjeta VARCHAR(50),
            direccion VARCHAR(255)
        ) ENGINE=InnoDB;
    ");
} catch (PDOException $e) {
    die("Error creando tabla usuarios: " . $e->getMessage());
}


// TABLA PELICULAS
try {
    $conexion->exec("
        CREATE TABLE IF NOT EXISTS peliculas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(255),
            reparto TEXT,
            equipo TEXT,
            generos VARCHAR(255),
            detalles TEXT,
            fecha_salida DATE
        ) ENGINE=InnoDB;
    ");
} catch (PDOException $e) {
    die("Error creando tabla peliculas: " . $e->getMessage());
}


// TABLA RESEÑAS
try {
    $conexion->exec("
        CREATE TABLE IF NOT EXISTS resenas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            puntuacion INT,
            comentario TEXT,
            favorita BOOLEAN DEFAULT FALSE,
            watchlist BOOLEAN DEFAULT FALSE,
            id_usuario INT,
            id_pelicula INT,
            FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE,
            FOREIGN KEY (id_pelicula) REFERENCES peliculas(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");
} catch (PDOException $e) {
    die("Error creando tabla resenas: " . $e->getMessage());
}


// TABLA LISTAS
try {
    $conexion->exec("
        CREATE TABLE IF NOT EXISTS listas (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre_lista VARCHAR(255),
            descripcion TEXT,
            etiquetas VARCHAR(255),
            id_usuario INT,
            FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");
} catch (PDOException $e) {
    die("Error creando tabla listas: " . $e->getMessage());
}


// TABLA INTERMEDIA LISTA - PELICULA
try {
    $conexion->exec("
        CREATE TABLE IF NOT EXISTS lista_pelicula (
            id_lista INT,
            id_pelicula INT,
            PRIMARY KEY (id_lista, id_pelicula),
            FOREIGN KEY (id_lista) REFERENCES listas(id) ON DELETE CASCADE,
            FOREIGN KEY (id_pelicula) REFERENCES peliculas(id) ON DELETE CASCADE
        ) ENGINE=InnoDB;
    ");
} catch (PDOException $e) {
    die("Error creando tabla lista_pelicula: " . $e->getMessage());
}


// TABLA PEDIDOS
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


// TABLA DETALLES PEDIDO
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
//              CREAR ADMIN SI NO EXISTE
//////////////////////////////////////////////////////

try {
    $stmt = $conexion->query("SELECT * FROM usuarios WHERE email = 'admin@admin.com' LIMIT 1");
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

echo "Base de datos lista y conectada correctamente.";
?>