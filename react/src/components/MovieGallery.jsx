import { useEffect, useState } from "react";

export default function MovieGallery({ genero }) {

    const [peliculas, setPeliculas] = useState([]);
    const [loading, setLoading] = useState(true);

    useEffect(() => {

        if (!genero) return;

        setLoading(true);

        const pathname = window.location.pathname;
        const metaBase = document
            .querySelector('meta[name="app-base"]')
            ?.getAttribute("content")
            ?.trim();

        const marker = "/proyecto-agile-intermodular";
        const idx = pathname.indexOf(marker);

        const basePath = metaBase
            ? metaBase.replace(/\/+$/, "")
            : idx >= 0
                ? pathname.slice(0, idx + marker.length)
                : pathname.lastIndexOf("/index.php") >= 0
                    ? pathname.slice(0, pathname.lastIndexOf("/index.php"))
                    : "";

        const API_BASE = window.location.origin + basePath;

        fetch(`${API_BASE}/php/obtener_peliculas_genero.php?genero=${encodeURIComponent(genero)}`)
            .then(res => {
                if (!res.ok) {
                    throw new Error("Error en la respuesta del servidor");
                }
                return res.json();
            })
            .then(data => {

                console.log("RESPUESTA API:", data);

                //protección importante
                setPeliculas(Array.isArray(data.peliculas) ? data.peliculas : []);

            })
            .catch(err => {
                console.error("ERROR FETCH:", err);
                setPeliculas([]);
            })
            .finally(() => setLoading(false));

    }, [genero]);

    if (loading) {
        return <p className="text-center text-white">Cargando películas...</p>;
    }

    if (!peliculas.length) {
        return <p className="text-center text-white">No hay películas para este género</p>;
    }

    return (
        <div className="row g-4 justify-content-center">

            {peliculas.map((p) => {

                const posterSrc = p.poster?.startsWith("/")
                    ? "https://image.tmdb.org/t/p/w500" + p.poster
                    : "/img/" + p.poster;

                return (
                    <div key={p.id} className="col-12 col-sm-6 col-md-4 col-lg-3">

                        <a href={`index.php?route=pelicula&id=${p.id}`} style={{ textDecoration: "none" }}>

                            <div className="card-cine shadow h-100 d-flex flex-column">

                                <div style={{ flex: 1 }}>
                                    <img
                                        src={posterSrc}
                                        className="poster-pelicula-peliculas"
                                        alt={p.titulo}
                                    />
                                </div>

                                <div className="card-body">
                                    <h5 className="titulo-cine">
                                        {p.titulo}
                                    </h5>

                                    <p className="texto-cine">
                                        ⭐ {p.puntuacion}/10
                                    </p>

                                    <span className="btn btn-cine mt-2">
                                        Ver detalles
                                    </span>
                                </div>

                            </div>

                        </a>

                    </div>
                );
            })}

        </div>
    );
}