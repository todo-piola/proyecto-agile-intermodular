import React from 'react'
import { createRoot } from 'react-dom/client'
import Cart from './components/Cart.jsx'
import Pelicula from './components/Pelicula.jsx'
import { useCart } from './hooks/useCart.js'

const cartContainer = document.getElementById('cart-root');
if (cartContainer) {
  createRoot(cartContainer).render(<CartWrapper />);
}

const peliculaContainer = document.getElementById('react-pelicula');
if (peliculaContainer && window.PELICULA_DATA) {
  createRoot(peliculaContainer).render(<PeliculaWrapper />);
}

function CartWrapper() {
  const { cart, removeFromCart, total, clearCart } = useCart();
  return <Cart cart={cart} total={total} removeFromCart={removeFromCart} clearCart={clearCart} />;
}

function PeliculaWrapper() {
  const { addToCart } = useCart();
  return <Pelicula addToCart={addToCart} />;
}