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

export async function getPeliculasSemana() {
  const res = await fetch(`${BASE_URL}/trending/movie/week?language=es-ES`, options);
  if (!res.ok) throw new Error(`Error TMDB: ${res.status}`);
  const datos = await res.json();
  return datos.results;
}

export async function getPeliculasMejorValoradas() {
  const res = await fetch(`${BASE_URL}/movie/top_rated?language=es-ES`, options);
  if (!res.ok) throw new Error(`Error TMDB: ${res.status}`);
  const datos = await res.json();
  return datos.results;
}


