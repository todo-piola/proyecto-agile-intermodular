const CART_STORAGE_KEY = 'proyecto-agile-intermodular-cart';

export const cartState = {
    items : [],
    total : 0
};

export function recalculateCartTotal() {
    cartState.total = cartState.items.reduce((acc, item) => {
        const parsedPrice = Number.parseFloat(item?.precio);
        return acc + (Number.isFinite(parsedPrice) ? parsedPrice : 0);
    }, 0);

    return cartState.total;
}

export function getCartItemCount() {
    return cartState.items.length;
}

export function loadCartState() {
    if (typeof window === 'undefined') return;

    try {
        const storedCart = window.localStorage.getItem(CART_STORAGE_KEY);

        if (!storedCart) {
            cartState.items = [];
            cartState.total = 0;
            return;
        }

        const parsedCart = JSON.parse(storedCart);
        cartState.items = Array.isArray(parsedCart.items) ? parsedCart.items : [];

        recalculateCartTotal();
    } catch (error) {
        cartState.items = [];
        cartState.total = 0;
        window.localStorage.removeItem(CART_STORAGE_KEY);
        console.error('Error leyendo carrito guardado:', error);
    }
}

export function persistCartState() {
    if (typeof window === 'undefined') return;

    if (cartState.items.length === 0) {
        window.localStorage.removeItem(CART_STORAGE_KEY);
      } else {
        window.localStorage.setItem(CART_STORAGE_KEY, JSON.stringify({
          items: cartState.items
        }));
      }
}