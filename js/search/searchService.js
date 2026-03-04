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
    return normalizeQuery(params.get('query') || '');
}

/**
 * @param query
 * @returns {string}
 * Esta función construye la URL de la página de resultados de búsqueda,
 * asegurándose de normalizar el término de búsqueda y codificarlo correctamente para la URL.
 */
export function buildSearchPageUrl(query) {
    const q = normalizeQuery(query);
    const repoBase = window.location.hostname.includes('github.io')
        ? '/proyecto-agile-intermodular'
        : '';
    return `${repoBase}/views/search.html?q=${encodeURIComponent(q)}`;
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
    if (!cleanQuery || cleanQuery.lenght < 2) return [];

    const cacheKey = `tmdb_search_${cleanQuery.toLowerCase()}_p${page}`;
    const cached = sessionStorage.getItem(cacheKey);
    if (cached) return JSON.parse(cached);

    const params = new URLSearchParams({
        query: cleanQuery,
        include_adult: 'false',
        language: 'es-ES',
        page: String(page)
    })

    const url = `${BASE_URL}/search/movie?${params.toString()}`;

    try{
        const res = await fetch(url, options);
        if (!res.ok) throw new Error(`Error TMDB: ${res.status}`);
        const data = await res.json();

        const mapped = (data.results || []).map(movie => ({
            id: movie.id,
            titulo: movie.title || 'Título desconocido',
            fecha: movie.release_date || 'Fecha desconocida',
            descripcion: movie.overview || 'Sin descripcion',
            imagen: movie.poster_path ? `${IMG_URL}${movie.poster_path}` : '../img/poster-prueba.jpg',
            precio: '3,99 EUR',
            director: movie.director || 'Director desconocido',
        }))

        sessionStorage.setItem(cacheKey, JSON.stringify(mapped));
        return mapped;
    }
    catch (err) {
        console.error("Error en búsqueda:", err);
    }
}

/**
 * Inicializa la navegación de la barra de búsqueda,
 * tanto para el ícono de lupa como para la tecla Enter.
 */
export function initSearchBarNavigation() {
    //Escuchador que funciona para ambos icono de lupa, tanto el de la versión escritorio como la móvil.
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-role="search-btn"], #lupaBtn');
        if (!btn) return;

        const container = btn.closest('#contenedor-lupa, #contenedor-lupa-movil');
        const input = container?.querySelector('[data-role="search-input"], #barraBusqueda, input[type="search"]');
        if (!input) return;

        const query = input.value.trim();
        if (query.length < 2) return;

        window.location.href = buildSearchPageUrl(query);
    });

    //Escuchador para tecla enter en cualquier input de búsuqeda
    document.addEventListener('keydown', (e) => {
        const isSearchInput = e.target.matches('[data-role="search-input"], #barraBusqueda, input[type="search"]');
        if (!isSearchInput || e.key !== 'Enter') return;

        const query = e.target.value.trim();
        if (query.length < 2) return;

        window.location.href = buildSearchPageUrl(query);
    });
}
