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
 *
 * @param query
 * @param page
 * @returns {Promise<{id: *, titulo, fecha, descripcion, imagen: string|string, precio: string, director}[]|any|*[]>}
 * Esta función realiza la búsqueda de películas en la base de datos local,
 * asegurándose de normalizar la consulta, manejar el almacenamiento en caché para mejorar el rendimiento,
 * y mapear los resultados a un formato más amigable para la aplicación.
 */
export async function searchMovies(query, page = 1) {
    const cleanQuery = normalizeQuery(query);

    if (!cleanQuery || cleanQuery.length < 2) return [];

    const cacheKey = `db_search_${cleanQuery.toLowerCase()}_p${page}`;
    const cached = sessionStorage.getItem(cacheKey);
    if (cached) return JSON.parse(cached);

    const params = new URLSearchParams({
        query: cleanQuery,
        page: String(page)
    });

    const url = `../php/buscar_peliculas.php?${params.toString()}`;

    try {
        const res = await fetch(url, {
            method: 'GET',
            headers: {
                accept: 'application/json'
            }
        });

        if (!res.ok) {
            throw new Error(`HTTP ${res.status}`);
        }

        const data = await res.json();
        const results = Array.isArray(data.results) ? data.results : [];

        sessionStorage.setItem(cacheKey, JSON.stringify(results));
        return results;

    } catch (err) {
        console.error("Error en búsqueda:", err);
        return [];
    }
}
