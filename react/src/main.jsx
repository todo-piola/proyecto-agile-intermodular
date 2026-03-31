import { createRoot } from 'react-dom/client'

import Pelicula from './components/Pelicula.jsx'

import Cart from './components/Cart.jsx'
import { useCart } from './hooks/useCart.js'

import RegisterForm from './components/RegisterForm.jsx'
import LoginForm from './components/LoginForm.jsx'

const cartContainer = document.getElementById('cart-root');
if (cartContainer) {
  createRoot(cartContainer).render(<CartWrapper />);
}

const peliculaContainer = document.getElementById('react-pelicula');
if (peliculaContainer && window.PELICULA_DATA) {
  createRoot(peliculaContainer).render(<PeliculaWrapper />);
}

const modalRoot = document.getElementById('modal-root');
if (modalRoot) {
  createRoot(modalRoot).render(
    <>
      <LoginForm />
      <RegisterForm />
    </>
  );
}

function CartWrapper() {
  const { cart, removeFromCart, total, clearCart } = useCart();
  return <Cart cart={cart} total={total} removeFromCart={removeFromCart} clearCart={clearCart} />;
}

function PeliculaWrapper() {
  const { addToCart } = useCart();
  return <Pelicula addToCart={addToCart} />;
}