import { useState } from "react";

/**
 * 
 * @param {*} param0 
 * mobile define si el componente se renderiza o no es la versión móvil de la web
 */
export function SearchBar({ mobile = false}) {
    const [query, setQuery] = useState("");

    /**
     * 
     * Arrow function que se ejecutará al hacer click en el botón de lupa 
     * o de manera automática al teclear
     */
    const goSearch = () => {
        const queryClean = query.trim().replace(/\s+/g, " ");
        if (queryClean.length < 1) return;

        localStorage.setItem("query", queryClean);

        const pathname = window.location.pathname;
        const marker = "/proyecto-agile-intermodular";
        const markerIndex = pathname.indexOf(marker);
        const base = markerIndex >= 0 ? pathname.slice(0, markerIndex + marker.length) : "";


        window.location.href = 
        `${base}/views/search.php?query=${encodeURIComponent(queryClean)}`;
    }

    const onSubmit = (e) => {
        e.preventDefault();
        goSearch();
    }

    return (
        <form
            className={`d-flex align-items-center ${mobile ? "w-100" : ""}`}
            onSubmit = {onSubmit}
            role = "search"
        >
            <input
                type = "search"
                className="form-control me-2"
                placeholder="Buscar película..."
                aria-label="buscar"
                value={query}
                onChange = {(e) => setQuery(e.target.value)}
            />
            <button type="submit" className="btn p-0 border-0 bg-transparent">
                <i className="bi bi-search fs-4 text-warning" />
            </button>
        </form>
    )
}