import {searchState} from "./searchState.js";
import  { buildSearchPageUrl, getQueryFromUrl, searchMovies } from './searchService.js';

function renderMessage(message) {
    const errorDiv = document.getElementById('sinResultados');
    errorDiv.textContent = message;
}

function addText(root, selector, text){
    root.querySelector(selector).forEach(el =>
    el.textContent = text)
}

function addImage(root, src, alt){
    root.querySelectorAll('img').forEach(img => {
        img.src = src;
        img.alt = alt;
    })
}

function renderResults() {
    const result = document.getElementById('resultados');
    const template = document.getElementById('template-pelicula');

    if (!result || !template) return;

    result.innerHTML = '';

    if (searchState.status === 'loading') {
        renderMessage(`Buscando "${searchState.query}"...`);
        return;
    }

    if (searchState.status === 'error') {
        renderMessage('Error al cargar resultados. Intente de nuevo.');
        return;
    }

    if (!searchState.coincidences.length){
        renderMessage(`No se han encontrado coincidencias para "${searchState.query}".`);
        return;
    }

    searchState.coincidences.forEach((movie) => {
        const clone = template.content.cloneNode(true);

        addImage(clone, movie.imagen, `Poster de ${movie.titulo}`);
        addText(clone, '.card-title', movie.titulo);
        addText(clone, '.anio', movie.anio);
        addText(clone, '.director', movie.director);
        addText(clone, '.descripcion', movie.descripcion);
        addText(clone, '.precio', movie.precio);

        addImage.appendChild(clone);
    });
}

export function initSearchPage() {
    const query = getQuery
}

