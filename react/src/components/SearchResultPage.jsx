import { useSearch } from "../hooks/useSearch";
//Importamos el carrito para poder añadir desde la página de búsuqeda las películas a este
import { useCart } from "../hooks/useCart.js";

function formatPrice(value) {
      const n = Number(value);
    if (!Number.isFinite(n)) return "0,00€";
    return `${n.toFixed(2).replace(".", ",")}€`;
}

function SearchCard({movie, addToCart}) {

    const handleAddToCart = () => {
    addToCart({
        id: movie.id,
        titulo: movie.titulo,
        precio: movie.precio,
        poster: movie.imagen,
        director: movie.director,
        fecha: movie.fecha
    });
};

    return (
        <div className = " col-12 col-md-6 mb-4">
            <div className="card h-100 shadow-sm bg-dark">
                <img
                    className="card-img-top"
                    src={movie.imagen}
                    alt={`Poster de ${movie.titulo}`}
                    style={{objectFit: "cover", height: "300px"}}
                />

                <div className="card-body">
                    <h5 className="card-title text-white">{movie.titulo}</h5>
                    <p className="card-text text-white"><strong>Año:</strong> {movie.fecha}</p>
                    <p className="card-text text-white"><strong>Director:</strong> {movie.director}</p>
                    <p className="card-text small text-white">{movie.descripcion}</p>
                    
                    <div className="d-flex justify-content-between align-items-center mt-2">
                    <span className="h5 mb-0 text-white">{formatPrice(movie.precio)}</span>
                    <button className="btn btn-primary btn-alquilar" onClick={handleAddToCart}>
                        Alquilar
                    </button>
                </div>
                </div>

            </div>
        </div>
    )
}

export default function SearchResultPage() {
    const {query, results, status, error} = useSearch();
    const { addToCart } = useCart();

    return (
        <section className="bg-black">
            <div className="container-fluid px-4">
                {status === "loading" && <div className="text-warning mb-3">Buscando "{query}"...</div>}
                {status === "error" && <div className="text-danger mb-3">{error}</div>}
                {status === "empty" && (
                <div className="text-warning mb-3">
                    {!query ? "Escribe algo en el buscador para ver resultados." : `No hay coincidencias para "${query}".`}
                </div>
                )}

                <div className="row gy-4">
                {results.map((movie) => (
                    <SearchCard key={movie.id} movie={movie} addToCart={addToCart} />
                ))}
                </div>
            </div>
        </section>
    );
}