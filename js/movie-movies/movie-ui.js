import { IMG_URL } from './movie-service.js';

export function renderizarPeliculas(peliculas, idContenedor) {
  const contenedor = document.getElementById(idContenedor);
  if (!contenedor) return;

  if(idContenedor === 'grid-mas-gustadas-sm' || idContenedor === 'grid-mas-vistas-sm') {
    contenedor.innerHTML = peliculas.slice(0, 4).map(pelicula => `
        <div class="col">
          <img
            src="${IMG_URL}${pelicula.poster_path}"
            alt="${pelicula.title}"
            class="img-fluid poster-pelicula-peliculas"
            title="${pelicula.title}">
        </div>
      `).join('');
  } else if(idContenedor === 'grid-mas-gustadas' || idContenedor === 'grid-mas-vistas') {
    contenedor.innerHTML = peliculas.slice(0,8).map(pelicula => `
        <div class="col">
          <img
            src="${IMG_URL}${pelicula.poster_path}"
            alt="${pelicula.title}"
            class="img-fluid poster-pelicula-peliculas"
            title="${pelicula.title}">
        </div>
      `).join('');
  }

}

export function mostrarError(idContenedor) {
  const contenedor = document.getElementById(idContenedor);

  if (contenedor) 
    contenedor.innerHTML = '<p class="text-danger text-center">No se pudieron cargar las películas.</p>';
}