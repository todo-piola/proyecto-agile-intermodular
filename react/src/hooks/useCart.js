// useCart.js — Carrito global con persistencia en localStorage
import { useState, useEffect } from "react";

const STORAGE_KEY = "cine_cart";

/*
    Se construyen una serie de ufnciones cuyo objetivo es:
    normalizar los datos de las películas que van al carrito.
  */
  const normalizePoster = (item) => {
    if (item.poster) return item.poster;
    if (item.imagen) return item.imagen;
    return "";
  }

  const normalizePrice = (value) => {
      const n = Number(value);
      return Number.isFinite(n) ? n : 0;
  }

  const normalizeCartItem = (item) => ({
    id: String(item.id),
    titulo: item.titulo || "",
    precio: normalizePrice(item.precio),
    poster: normalizePoster(item),
    director: item.director || "",
    fecha: item.fecha || ""
  });

// Persistencia del carrito en localStorage
const loadCart = () => {
  try {
    const raw = localStorage.getItem(STORAGE_KEY);
    if (!raw) return [];

    const parsed = JSON.parse(raw);
    if (!Array.isArray(parsed)) return [];

    return parsed.map(normalizeCartItem);
  } catch {
    localStorage.removeItem(STORAGE_KEY);
    return [];
  }
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
    const normalized = normalizeCartItem(item);

    if (globalCart.some(i => String(i.id) === normalized.id)) return;
    globalCart = [...globalCart, normalized];
    syncCart();
  };

  const removeFromCart = (id) => {
    globalCart = globalCart.filter(i => String(i.id) !== String(id));
    syncCart();
  };

  const clearCart = () => {
    globalCart = [];
    syncCart();
  };

  const total = cart.reduce((acc, {precio}) => acc + Number(precio), 0);

  return { cart, addToCart, removeFromCart, clearCart, total };
}