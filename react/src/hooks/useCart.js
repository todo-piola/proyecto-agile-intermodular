import { useState, useEffect } from "react";

const STORAGE_KEY = 'cine_cart';

function loadCart() {
  try {
    return JSON.parse(localStorage.getItem(STORAGE_KEY)) || [];
  } catch { return []; }
}

function saveCart(cart) {
  localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
}

let globalCart = loadCart();
let listeners = [];

function notify() {
  saveCart(globalCart);
  listeners.forEach(l => l([...globalCart]));
}

export function useCart() {
  const [cart, setCart] = useState(globalCart);

  useEffect(() => {
    listeners.push(setCart);
    return () => { listeners = listeners.filter(l => l !== setCart); };
  }, []);

  const addToCart = (item) => {
    if (globalCart.some(i => i.id === item.id)) return;
    globalCart.push(item);
    notify();
  };

  const removeFromCart = (id) => {
    globalCart = globalCart.filter(i => i.id !== id);
    notify();
  };

  const clearCart = () => {
    globalCart = [];
    notify();
  };

  const total = cart.reduce((acc, i) => acc + Number(i.precio), 0);

  return { cart, addToCart, removeFromCart, clearCart, total };
}