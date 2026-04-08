import {useEffect, useMemo, useState} from "react";

function normalizeQuery(q) {
  return (q || "").trim().replace(/\s+/g, " ");
}

function getQueryFromUrl() {
    const params = new URLSearchParams(window.location.search);
    return normalizeQuery(params.get("query"));
}

function getBasePath() {
  const pathname = window.location.pathname;
  const marker = "/proyecto-agile-intermodular";
  const idx = pathname.indexOf(marker);
  return idx >= 0 ? pathname.slice(0, idx + marker.length) : "";
}

export function useSearch() {
    const [query] = useState(() => getQueryFromUrl());
    const [results, setResults] = useState([]);
    const [status, setStatus] = useState ("idle");
    const [error, setError] = useState(null);

    const basePath = useMemo(getBasePath, []);

    useEffect(() => {
        const q = getQueryFromUrl();


        if (!q || q.length < 2) {
            setResults([]);
            setStatus("idle");
            setError(null);
            return;
        }

        let cancelled = false;

        async function loadResults() {
            try{
                setStatus("loading");
                setError(null);

                const params = new URLSearchParams({
                    query: q,
                    page: 1
                });

                const response = await fetch(`${basePath}/php/buscar_peliculas.php?${params.toString()}`, {
                    method: "GET",
                    headers: {accept: "application/json"}
                    });

                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                const data = await response.json();
                const rows = Array.isArray(data.results) ? data.results : [];

                if (!cancelled) {
                    setResults(rows);
                    setStatus(rows.length ? "success" : "empty");
                }
            } catch {
                if (!cancelled) {
                    setResults([]);
                    setStatus("error");
                    setError("Ocurrió un error al cargar los resultados de búsqueda.");
                }
            }
        }

        loadResults();

        return () => {
            cancelled = true;
        };
    }, [basePath]);

    return { query, results, status, error };
}