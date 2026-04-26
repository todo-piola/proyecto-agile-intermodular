import { useCart } from "./hooks/useCart.js";
import Pelicula from "./components/Pelicula.jsx";
import Cart from "./components/Cart.jsx";

function App() {
  const { cart, addToCart, removeFromCart, total } = useCart();

  return (
    <>
      <Pelicula addToCart={addToCart} />

      <Cart
        cart={cart}
        total={total}
        removeFromCart={removeFromCart}
      />
    </>
  );
}
export default App;