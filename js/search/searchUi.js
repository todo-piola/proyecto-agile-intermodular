import { searchState } from './searchState.js';
import { buildSearchPageUrl, getQueryFromUrl, searchMovies } from './searchService.js';

const sinResultados = document.getElementById('sinResultados');

/**
 *
 * @type {{saveSearch: function(*): void, getLastSearch: function(): *, clearOldSearch: function(number=): void}}
 * Utilidades para manejar el almacenamiento de la última búsqueda en localStorage,
 * incluyendo funciones para guardar, recuperar y limpiar búsquedas antiguas.
 * Esto permite restaurar el término de búsqueda si el usuario.
 */
const StorageUtils = {
    saveSearch: (query) => {
        if (query && query.length >= 2) {
            localStorage.setItem('ultimaBusqueda', query);
            localStorage.setItem('busquedaTimestamp', Date.now());
        }
    },

    getLastSearch: () => {
        return localStorage.getItem('ultimaBusqueda') || '';
    },

    clearOldSearch: (maxHours = 24) => {
        const timestamp = localStorage.getItem('busquedaTimestamp');
        if (!timestamp) return;

        const hoursPassed = (Date.now() - parseInt(timestamp)) / (1000 * 60 * 60);
        if (hoursPassed > maxHours) {
            localStorage.removeItem('ultimaBusqueda');
            localStorage.removeItem('busquedaTimestamp');
        }
    }
};

/**
 *
 * @param message
 * Esta función se encarga de mostrar mensajes en la sección de resultados,
 * como estados de carga, errores o falta de coincidencias.
 */
function renderMessage(message) {
    sinResultados.textContent = "";
    if (! sinResultados) return;

    sinResultados.textContent = message;
}

/**
 *
 * @param root
 * @param selector
 * @param texto
 * Esta función es una utilidad para pintar texto en todos los elementos que coincidan
 * con un selector dentro de un nodo raíz dado.
 */
function pintarTextoEnTodos(root, selector, texto) {
    root.querySelectorAll(selector).forEach((el) => {
        el.textContent = texto;
    });
}

/**
 *
 * @param root
 * @param src
 * @param alt
 * Esta función es una utilidad para pintar imágenes en todos los elementos <img> dentro de un nodo raíz dado,
 * estableciendo su atributo src y alt.
 */
function pintarImagenes(root, src, alt) {
    root.querySelectorAll('img').forEach((img) => {
        img.src = src;
        img.alt = alt;
    });
}

/**
 * Esta función se encarga de renderizar los resultados de la búsqueda en la página,
 * manejando diferentes estados como carga, error o falta de coincidencias, y utilizando
 * una plantilla HTML para mostrar cada película encontrada de manera consistente.
 */
function renderResults() {
    const resultados = document.getElementById('resultados');
    const template = document.getElementById('template-pelicula');

    if (!resultados || !template) return;

    if (searchState.status === 'success') {
        sinResultados.textContent = "";
    }

    resultados.innerHTML = '';

    if (searchState.status === 'loading') {
        renderMessage(`Buscando "${searchState.query}"...`);
        return;
    }

    if (searchState.status === 'error') {
        renderMessage(searchState.error || 'Ha ocurrido un error en la búsqueda.');
        return;
    }

    if (!searchState.coincidences || searchState.coincidences.length === 0) {
        renderMessage(`No hay coincidencias para "${searchState.query}".`);
        return;
    }

    searchState.coincidences.forEach((movie) => {
        const clone = template.content.cloneNode(true);

        pintarImagenes(clone, movie.imagen, `Poster de ${movie.titulo}`);
        pintarTextoEnTodos(clone, '.card-title', movie.titulo);
        pintarTextoEnTodos(clone, '.anio', movie.fecha);
        pintarTextoEnTodos(clone, '.director', movie.director);
        pintarTextoEnTodos(clone, '.descripcion', movie.descripcion);
        pintarTextoEnTodos(clone, '.precio', movie.precio);

        resultados.appendChild(clone);
    });
}

/**
 * Esta función inicializa la navegación del buscador, agregando event listeners para manejar tanto
 * clics en el botón de búsqueda como la pulsación de la tecla Enter en el campo de búsqueda.
 */
export function initSearchBarNavigation() {

    document.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-role="search-btn"], #lupaBtn');
        if (!btn) return;

        const container = btn.closest('#contenedor-lupa, #contenedor-lupa-movil');
        const input = container?.querySelector('[data-role="search-input"], #barraBusqueda, input[type="search"]');
        if (!input) return;

        const query = input.value.trim();
        if (query.length < 2) return;

        StorageUtils.saveSearch(query);
        window.location.href = buildSearchPageUrl(query);
    });

    document.addEventListener('keydown', (e) => {
        const isSearchInput = e.target.matches('[data-role="search-input"], #barraBusqueda, input[type="search"]');
        if (!isSearchInput || e.key !== 'Enter') return;

        const query = e.target.value.trim();
        if (query.length < 2) return;

        StorageUtils.saveSearch(query);
        window.location.href = buildSearchPageUrl(query);
    });
}

/**
 *
 * @returns {Promise<void>}
 * Esta función se encarga de inicializar la página de resultados de búsqueda,
 * extrayendo el término de búsqueda de la URL o del almacenamiento local,
 * realizando la búsqueda de películas utilizando la API,
 * y renderizando los resultados o mensajes correspondientes según el estado de la búsqueda.
 */
export async function initSearchResultsPage() {
    const resultados = document.getElementById('resultados');
    if (!resultados) return;

    StorageUtils.clearOldSearch();

    let query = getQueryFromUrl();

    if (!query) {
        query = StorageUtils.getLastSearch();

        const searchInput = document.querySelector(
            '[data-role="search-input"], #barraBusqueda, input[type="search"]'
        );
        if (searchInput && query) {
            searchInput.value = query;
        }
    }

    searchState.query = query;

    if (!query) {
        searchState.coincidences = [];
        searchState.status = 'empty';
        renderMessage('Escribe algo en el buscador para ver resultados.');
        return;
    }

    try {
        searchState.status = 'loading';
        renderResults();

        const coincidencias = await searchMovies(query);
        searchState.coincidences = coincidencias;
        searchState.status = coincidencias.length ? 'success' : 'empty';
        searchState.error = null;

    } catch (err) {
        searchState.coincidences = [];
        searchState.status = 'error';
        searchState.error = 'No se pudieron cargar los resultados ahora.';
        console.error(err);
    }

    renderResults();
}

/**
 * Esta función se encarga de restaurar el término de búsqueda en el campo de búsqueda
 * al cargar la página, utilizando el almacenamiento local
 * para recuperar la última búsqueda realizada por el usuario.
 */
(function initSearchRestore() {
    function restoreInput() {
        const searchInput = document.querySelector(
            '[data-role="search-input"], #barraBusqueda, input[type="search"]'
        );
        if (searchInput && !searchInput.value) {
            searchInput.value = StorageUtils.getLastSearch();
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', restoreInput);
    } else {
        restoreInput();
    }
})();