const TOKEN = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI4MmFjN2M4NWYyYTZiZWJiNTE3NWI3ODg1ODk3ODgxNSIsIm5iZiI6MTc2OTA4NDcyNy40NDcsInN1YiI6IjY5NzIxNzM3YTQzMTYzMTUxOWUzNmQzOCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.vqfF3hbMqw1AQ4UyJ12Tj_Nda3gdcplkdoJxtP_MmhY';
const BASE_URL = 'https://api.themoviedb.org/3';
export const IMG_URL = 'https://image.tmdb.org/t/p/w500';

const options = {
    method : 'GET',
    headers : {
        accept : 'application/json',
        Authorization: `Bearer ${TOKEN}`
    }
};

/**
 *
 * @param query
 * @returns {string}
 * Esta función normaliza la consulta de búsqueda.
 * Elimina espacios al inicio y al final, así como los múltiples espacios entre palabras.
 */
export function normalizeQuery(query) {
    return query.trim().replace(/\s+/g, ' ');
}

/**
 * @returns {string}
 * Esta función extrae el término de búsqueda de la URL,
 * normalizándolo para evitar problemas con espacios o caracteres especiales.
 */
export function getQueryFromUrl(){
    const params = new URLSearchParams(window.location.search);
    // Compatibilidad con enlaces antiguos que usaban ?q=
    return normalizeQuery(params.get('query') || params.get('q') || '');
}

/**
 * @param query
 * @returns {string}
 * Esta función construye la URL de la página de resultados de búsqueda,
 * asegurándose de normalizar el término de búsqueda y codificarlo correctamente para la URL.
 */
export function buildSearchPageUrl(query) {
    const q = normalizeQuery(query);
    const pathname = window.location.pathname;
    const projectMarker = '/proyecto-agile-intermodular';
    const projectMarkerIndex = pathname.indexOf(projectMarker);

    // Si detectamos la carpeta del proyecto en la URL (XAMPP subcarpeta, GitHub Pages, etc.),
    // conservamos el prefijo completo para no romper las rutas.
    const repoBase = projectMarkerIndex >= 0
        ? pathname.slice(0, projectMarkerIndex + projectMarker.length)
        : '';

    return `${repoBase}/views/search.php?query=${encodeURIComponent(q)}`;
}

/**
 * Función auxiliar para obtener el director de una película
 * @param movieId
 * @returns {Promise<string>}
 */
async function fetchDirector(movieId) {
    try {
        const url = `${BASE_URL}/movie/${movieId}/credits`;
        const res = await fetch(url, options);
        const data = await res.json();

        const director = data.crew?.find(person => person.job === 'Director');
        return director?.name || 'Director desconocido';
    } catch (err) {
        console.error(`Error obteniendo director para película ${movieId}:`, err);
        return 'Director desconocido';
    }
}

/**
 *
 * @param query
 * @param page
 * @returns {Promise<{id: *, titulo, fecha, descripcion, imagen: string|string, precio: string, director}[]|any|*[]>}
 * Esta función realiza la búsqueda de películas en TMDB utilizando la API,
 * asegurándose de normalizar la consulta, manejar el almacenamiento en caché para mejorar el rendimiento,
 * y mapear los resultados a un formato más amigable para la aplicación.
 */
export async function searchMovies(query, page = 1) {
    const cleanQuery = normalizeQuery(query);

    if (!cleanQuery || cleanQuery.length < 2) return [];

    const cacheKey = `tmdb_search_${cleanQuery.toLowerCase()}_p${page}`;
    const cached = sessionStorage.getItem(cacheKey);
    if (cached) return JSON.parse(cached);

    const params = new URLSearchParams({
        query: cleanQuery,
        include_adult: 'false',
        language: 'es-ES',
        page: String(page)
    });

    const url = `${BASE_URL}/search/movie?${params.toString()}`;

    try {
        const res = await fetch(url, options);
        const data = await res.json();

        console.log("Respuesta TMDB:", data);

        // Primero mapeamos los resultados básicos
        const basicResults = (data.results || []).map(movie => ({
            id: movie.id,
            titulo: movie.title || 'Título desconocido',
            fecha: movie.release_date || 'Fecha desconocida',
            descripcion: movie.overview || 'Sin descripcion',
            imagen: movie.poster_path ? `${IMG_URL}${movie.poster_path}` : '../img/poster-prueba.jpg',
            precio: '3,99 EUR',
            director: 'Cargando director...' // Temporal mientras obtenemos los directores
        }));

        /*
        Necesitamos obtener el director de cada película de manera individual
        porque la API que usamos de búsqueda no lo incluye en la información general de la película.
         */
        const resultsWithDirectors = await Promise.all(
            basicResults.map(async (movie) => {
                const director = await fetchDirector(movie.id);
                return {
                    ...movie,
                    director: director
                };
            })
        );

        sessionStorage.setItem(cacheKey, JSON.stringify(resultsWithDirectors));
        return resultsWithDirectors;

    } catch (err) {
        console.error("Error en búsqueda:", err);
        return [];
    }
}
