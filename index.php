<?php

declare(strict_types=1);

$route = $_GET['route'] ?? 'home';

//Array de rutas para cargar las vistas correspondientes. 
$routes = [
    'home' => __DIR__ . '/views/home.php',
    'movies' => __DIR__ . '/views/movies.php',
    'listas' => __DIR__ . '/views/listas.php',
    'blog' => __DIR__ . '/views/blog.php',
    'contacto' => __DIR__ . '/views/about_contacto.php',
    'perfil' => __DIR__ . '/views/profile.php',
    'profile' => __DIR__ . '/views/profile.php',
    'search' => __DIR__ . '/views/search.php',
    'pelicula' => __DIR__ . '/views/pelicula.php',
    'peliculas_genero' => __DIR__ . '/views/peliculas_genero.php'
];

//En caso de que la ruta no exista se muestra el código de error 404 y se carga la home.
if (!isset($routes[$route])){
    http_response_code(404);
    require __DIR__ . '/views/home.php';
    exit;
}

//
require $routes[$route];
?>