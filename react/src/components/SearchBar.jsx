import { useRef, useState } from "react";

/**
 * Barra de búsqueda en cabecera.
 * mobile indica si se renderiza en versión móvil.
 */
export function SearchBar({ mobile = false }) {
  const [query, setQuery] = useState("");
  const timerRef = useRef(null);

  const buildBasePath = () => {
    const pathname = window.location.pathname;
    const marker = "/proyecto-agile-intermodular";
    const markerIndex = pathname.indexOf(marker);
    return markerIndex >= 0 ? pathname.slice(0, markerIndex + marker.length) : "";
  };

  const navigateToSearch = (rawValue) => {
    const queryClean = rawValue.trim().replace(/\s+/g, " ");
    if (queryClean.length < 2) return;

    // Compatibilidad con el flujo existente
    localStorage.setItem("ultimaBusqueda", queryClean);
    localStorage.setItem("busquedaTimestamp", String(Date.now()));

    const base = buildBasePath();
    window.location.href = `${base}/views/search.php?query=${encodeURIComponent(queryClean)}`;
  };

  const goSearch = () => {
    navigateToSearch(query);
  };

  const onSubmit = (e) => {
    e.preventDefault();
    clearTimeout(timerRef.current);
    goSearch();
  };

  const handleChange = (e) => {
    const value = e.target.value;
    setQuery(value);

    // Debounce para no navegar en cada tecla
    clearTimeout(timerRef.current);
    timerRef.current = setTimeout(() => {
      navigateToSearch(value);
    }, 300);
  };

  return (
    <form
      className={`d-flex align-items-center ${mobile ? "w-100" : ""}`}
      onSubmit={onSubmit}
      role="search"
    >
      <input
        type="search"
        className="form-control me-2"
        placeholder="Buscar película..."
        aria-label="buscar"
        value={query}
        onChange={handleChange}
      />
      <button type="submit" className="btn p-0 border-0 bg-transparent">
        <i className="bi bi-search fs-4 text-warning" />
      </button>
    </form>
  );
}