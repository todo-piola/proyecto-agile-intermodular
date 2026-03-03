const TOKEN = 'eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI4MmFjN2M4NWYyYTZiZWJiNTE3NWI3ODg1ODk3ODgxNSIsIm5iZiI6MTc2OTA4NDcyNy40NDcsInN1YiI6IjY5NzIxNzM3YTQzMTYzMTUxOWUzNmQzOCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.vqfF3hbMqw1AQ4UyJ12Tj_Nda3gdcplkdoJxtP_MmhY';
const BASE_URL = 'https://api.themoviedb.org/3';
export const IMG_URL = 'https://image.tmdb.org/t/p/w500';

const options = {
  method: 'GET',
  headers: {
    accept: 'application/json',
    Authorization: `Bearer ${TOKEN}`
  }
};

async function fetchConCache(url, claveCache) {
  // Si ya está en cache, devuelve los datos sin hacer fetch
  const cached = sessionStorage.getItem(claveCache);

  if (cached) {
    console.log(`Datos de ${claveCache} obtenidos de cache`);
    return JSON.parse(cached);
  }

  // Si no está, hace el fetch y lo guarda
  const res = await fetch(url, options);
  if (!res.ok) throw new Error(`Error TMDB: ${res.status}`);
  const datos = await res.json();
  sessionStorage.setItem(claveCache, JSON.stringify(datos.results));
  return datos.results;
}

export function getPeliculasSemana() {
  return fetchConCache(
    `${BASE_URL}/trending/movie/week?language=es-ES`,
    'tmdb_trending'
  );
}

export function getPeliculasMejorValoradas() {
  return fetchConCache(
    `${BASE_URL}/movie/top_rated?language=es-ES`,
    'tmdb_top_rated'
  );
}

