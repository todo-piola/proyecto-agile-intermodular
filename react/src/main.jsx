import { createRoot } from 'react-dom/client'
import { SearchBar } from './components/SearchBar.jsx'
import SearchResultPage from "./components/SearchResultPage.jsx";

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

const desktopSearchContainer = document.getElementById('contenedor-lupa');
if (desktopSearchContainer) {
  createRoot(desktopSearchContainer).render(<SearchBar />)
}

const mobileSearchContainer = document.getElementById('contenedor-lupa-movil');
if (mobileSearchContainer){
  createRoot(mobileSearchContainer).render(<SearchBar mobile={true} />)
}

function CartWrapper() {
  const { cart, removeFromCart, total, clearCart } = useCart();
  return <Cart cart={cart} total={total} removeFromCart={removeFromCart} clearCart={clearCart} />;
}

function PeliculaWrapper() {
  const { addToCart } = useCart();
  return <Pelicula addToCart={addToCart} />;
}

const searchResultsRoot = document.getElementById("react-search-results");
if (searchResultsRoot) {
  createRoot(searchResultsRoot).render(<SearchResultPage />);
}