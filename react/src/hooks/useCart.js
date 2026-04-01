// useCart.js — Carrito global con persistencia en localStorage
import { useState, useEffect } from "react";

const STORAGE_KEY = "cine_cart";

// Persistencia del carrito en localStorage
const loadCart = () => {
    return JSON.parse(localStorage.getItem(STORAGE_KEY)) || []; 
};

// Estado global del carrito  compartido entre componentes
let globalCart = loadCart();
let listeners = [];

const syncCart = () => {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(globalCart));
  listeners.forEach(fn => fn([...globalCart]));
};

// Hook del estado del carrito
export function useCart() {
  const [cart, setCart] = useState(globalCart);


  useEffect(() => {
    listeners.push(setCart);
    return () => { 
      listeners = listeners.filter(fn => fn !== setCart); // Limpia los listeners al desmontar el componente
    };
  }, []);

  // No permite duplicados (una sola entrada por película)
  const addToCart = (item) => {
    if (globalCart.some(i => i.id === item.id)) return;
    globalCart = [...globalCart, item];
    syncCart();
  };

  const removeFromCart = (id) => {
    globalCart = globalCart.filter(i => i.id !== id);
    syncCart();
  };

  const clearCart = () => {
    globalCart = [];
    syncCart();
  };

  const total = cart.reduce((acc, {precio}) => acc + Number(precio), 0);

  return { cart, addToCart, removeFromCart, clearCart, total };
}