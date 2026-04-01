import { useEffect, useState } from "react";
import { createPortal } from "react-dom";

// Normaliza la URL del póster (fallback + URL completa de TMDB)
function getPosterUrl(poster) {
  if (!poster) return '../img/poster-prueba.jpg';
  return poster.startsWith('/')
    ? `https://image.tmdb.org/t/p/w92${poster}`
    : poster;
}

function Cart({ cart, total, removeFromCart, clearCart }) {
  const [error, setError] = useState(null);

  // Actualiza manualmente el badge del carrito
  useEffect(() => {
    const badge = document.getElementById('cart-count');
    if (badge) badge.textContent = cart.length;
  }, [cart]);


  const handleCheckout = async () => {
    try {
      // Creamos el pedido
      const res1 = await fetch('/proyecto-agile-intermodular/php/crear_pedido.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ total })
      });

      const { orderId, error } = await res1.json();

      if (!orderId) {
        return setError(error || 'Error al crear el pedido');
      }

      // itemsData es un array con {movieId, precio} para cada película del carrito
      const itemsData = cart.map(({ id, precio }) => ({
        movieId: id,
        precio
      }));

      // detalle_pedido.php recibe el orderId y un array con {movieId, precio} para cada película del carrito y los guarda en la base de datos
      const res2 = await fetch('/proyecto-agile-intermodular/php/detalle_pedido.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ orderId, itemsData })
      });

      const data2 = await res2.json();

      if (!data2.success) {
        return setError(data2.error || 'Error al guardar los detalles');
      }

      // 4. Éxito → limpiar + flag + redirect
      clearCart();
      sessionStorage.setItem('pedido_ok', '1');
      window.location.href = '/proyecto-agile-intermodular/index.php';

    } catch {
      setError('Error de conexión');
    }
  };

  // Portal: evita problemas de superposiciones con modales de Bootstrap
  const modal = (
    <div className="modal fade" id="modalCheckout" tabIndex="-1">
      <div className="modal-dialog modal-dialog-centered">
        <div className="modal-content">
          <div className="modal-header btn-modificar">
            <h5 className="modal-title">Confirmar pedido</h5>
            <button type="button" className="btn-close" data-bs-dismiss="modal"></button>
          </div>

          <div className="modal-body">
            <p>
              Vas a comprar <strong>{cart.length} película(s) por {total.toFixed(2)}€</strong>.
            </p>
            <p>¿Confirmas el pedido?</p>
          </div>

          <div className="modal-footer">
            <button className="btn btn-eliminar" data-bs-dismiss="modal">
              Cancelar
            </button>
            <button
              className="btn btn-agregar"
              data-bs-dismiss="modal"
              onClick={handleCheckout}
            >
              Confirmar
            </button>
          </div>
        </div>
      </div>
    </div>
  );

  return (
    <>
      <div>
        {/* Carrito vacío */}
        {cart.length === 0 && <p>Carrito vacío</p>}

        {/* Lista de películas*/}
        {cart.map(item => {
          const precio = Number(item.precio);

          return (
            <div key={item.id} className="mb-3 border-bottom pb-2 d-flex gap-2 align-items-center">
              <img src={getPosterUrl(item.poster)} className="poster-carrito" alt={item.titulo} />

              <div>
                <p className="mb-1"> <strong>{item.titulo}</strong> </p>
                <p className="mb-1"> {precio.toFixed(2)}€ </p>

                <button className="btn btn-eliminar" onClick={() => removeFromCart(item.id)}> Eliminar </button>
              </div>
            </div>
          );
        })}

        {cart.length > 0 && (
          <>
            <h5>Total: {total.toFixed(2)}€</h5>

            <button className="btn btn-modificar w-100 mt-3" data-bs-toggle="modal" data-bs-target="#modalCheckout">
              Finalizar pedido
            </button>
          </>
        )}

        {/* Error */}
        {error && <p className="text-danger mt-2">{error}</p>}
      </div>

      {/* Renderiza el modal fuera del árbol principal */}
      {createPortal(modal, document.body)}
    </>
  );
}

export default Cart;