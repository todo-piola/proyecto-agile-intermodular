import { useEffect, useState } from "react";
import { createPortal } from "react-dom";

function getPosterUrl(poster) {
  if (!poster) return '../img/poster-prueba.jpg';
  if (poster.startsWith('/')) return 'https://image.tmdb.org/t/p/w92' + poster;
  return poster;
}

function Cart({ cart, total, removeFromCart, clearCart }) {
  const [error, setError] = useState(null);

  useEffect(() => {
    const badge = document.getElementById('cart-count');
    if (badge) badge.textContent = cart.length;
  }, [cart]);

  // Mostrar mensaje de confirmación si viene de un pedido completado
  const [confirmacion] = useState(() => {
    const ok = sessionStorage.getItem('pedido_ok');
    if (ok) { sessionStorage.removeItem('pedido_ok'); return true; }
    return false;
  });

  const handleCheckout = async () => {
    try {
      const res1 = await fetch('/proyecto-agile-intermodular/php/crear_pedido.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ total })
      });
      const data1 = await res1.json();

      if (!data1.orderId) {
        setError(data1.error || 'Error al crear el pedido');
        return;
      }

      const itemsData = cart.map(item => ({ movieId: item.id, precio: item.precio }));

      const res2 = await fetch('/proyecto-agile-intermodular/php/detalle_pedido.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ orderId: data1.orderId, itemsData })
      });
      const data2 = await res2.json();

      if (data2.success) {
        clearCart();
        sessionStorage.setItem('pedido_ok', '1');
        window.location.href = '/proyecto-agile-intermodular/index.php';
      } else {
        setError(data2.error || 'Error al guardar los detalles');
      }
    } catch (e) {
      setError('Error de conexión');
    }
  };

  const modal = (
    <div className="modal fade" id="modalCheckout" tabIndex="-1">
      <div className="modal-dialog modal-dialog-centered">
        <div className="modal-content">
          <div className="modal-header btn-modificar">
            <h5 className="modal-title">Confirmar pedido</h5>
            <button type="button" className="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div className="modal-body">
            <p>Vas a comprar <strong> {cart.length} película(s) por {total.toFixed(2)}€ </strong>.</p>
            <p>¿Confirmas el pedido?</p>
          </div>
          <div className="modal-footer">
            <button className="btn btn-eliminar" data-bs-dismiss="modal">Cancelar</button>
            <button className="btn btn-agregar" data-bs-dismiss="modal" onClick={handleCheckout}> Confirmar </button>
          </div>
        </div>
      </div>
    </div>
  );

  return (
    <>
      <div>
        {confirmacion && (
          <div className="alert alert-success">¡Pedido realizado con éxito!</div>
        )}

        {cart.length === 0 && <p>Carrito vacío</p>}

        {cart.map(item => (
          <div key={item.id} className="mb-3 border-bottom pb-2 d-flex gap-2 align-items-center">
            <img src={getPosterUrl(item.poster)} className="poster-carrito" />
            <div>
              <p className="mb-1"><strong>{item.titulo}</strong></p>
              <p className="mb-1">{Number(item.precio).toFixed(2)}€</p>
              <button className="btn btn-eliminar" onClick={() => removeFromCart(item.id)}> Eliminar </button>
            </div>
          </div>
        ))}

        {cart.length > 0 && (
          <>
            <h5>Total: {total.toFixed(2)}€</h5>
            <button className="btn btn-modificar w-100 mt-3" data-bs-toggle="modal" data-bs-target="#modalCheckout">
              Finalizar pedido
            </button>
          </>
        )}

        {error && <p className="text-danger mt-2">{error}</p>}
      </div>

      {createPortal(modal, document.body)}
    </>
  );
}

export default Cart;