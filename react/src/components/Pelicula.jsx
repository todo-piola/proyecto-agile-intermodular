
function Pelicula({ addToCart }) {
  const pelicula = { ...window.PELICULA_DATA, precio: 3.99 };  // precio fijo por película

  if (!pelicula) return null;

  return (
    <div>
      <button className="btn btn-alquilar" onClick={() => addToCart(pelicula)}> Añadir al carrito </button>
    </div>
  );
}

export default Pelicula;