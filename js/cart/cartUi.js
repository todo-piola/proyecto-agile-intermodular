import { cartState, getCartItemCount } from "./cartState.js";
import { addToCart, clearCart, eraseMovieFromCart, movieDate, checkout } from "./cartResume.js";

const cartIcon = document.getElementById('cartBtn');
const cartCount = document.getElementById('cart-count');
const cartItemsEl = document.getElementById('cart-items');
const cartTotal = document.getElementById('cart-total');
const endTransaction = document.getElementById('finalizar');

function formatCartPrice(value) {
    const parsedPrice = Number.parseFloat(value);
    return Number.isFinite(parsedPrice) ? parsedPrice.toFixed(2) : '0.00';
}

function updateCartCount() {
    const itemsCount = getCartItemCount();

    if (cartCount) {
        cartCount.textContent = String(itemsCount);
    }

    if (cartIcon) {
        cartIcon.classList.toggle('active', itemsCount > 0);
    }
}

export function renderCart(){
    if (!cartItemsEl || !cartTotal || !endTransaction) return;

    cartItemsEl.textContent = '';
    cartTotal.className = 'cartTotal';
    cartTotal.textContent = `Subtotal: ${cartState.total.toFixed(2)}€`;
    endTransaction.textContent = '';
    updateCartCount();

    if (cartState.items.length === 0){
        return;
    }

    cartState.items.forEach(item => {

        const div = document.createElement('div');

        div.className = 'cart-item';
        div.dataset.idCartItem = item.id;

        const img = document.createElement('img');
        img.src = item.imagen;
        img.alt = item.titulo;

        const nombre = document.createElement('h3');
        nombre.textContent = item.titulo;

        const precio = document.createElement('p');
        precio.textContent = `Precio: ${formatCartPrice(item.precio)}€`;

        const returnDate = document.createElement('p');
        returnDate.textContent = `Fecha de devolución: ${movieDate()}`;

        const eliminateBtn = document.createElement('button');
        eliminateBtn.type = 'button';
        eliminateBtn.className = 'eliminate-movie';
        eliminateBtn.textContent = 'Eliminar';


        div.appendChild(img);
        div.appendChild(nombre);
        div.appendChild(precio);
        div.appendChild(returnDate);
        div.appendChild(eliminateBtn);

        cartItemsEl.appendChild(div);
    });

    if (cartState.items.length > 0) {
        const checkoutBtn = document.createElement('button');
        checkoutBtn.id = 'checkout-btn';
        checkoutBtn.type = 'button';
        checkoutBtn.className = 'btn btn-warning';
        checkoutBtn.textContent = 'Finalizar compra';
        endTransaction.appendChild(checkoutBtn);
    }
}

if (cartItemsEl) {
    cartItemsEl.addEventListener('click', (e) => {
        const removeBtn = e.target.closest('.eliminate-movie');

        if (!removeBtn) return;

        const cartItem = removeBtn.closest('[data-id-cart-item]');
        const itemId = cartItem?.dataset.idCartItem;

        if (!itemId) return;

        eraseMovieFromCart(itemId);
        renderCart();
    });
}

document.addEventListener('click', async(e) => {
    if (e.target.id !== 'checkout-btn') return;

    try{
        await checkout();
        clearCart();
        renderCart();
        //Aquí tendré que redireccionar a la página de resumen de pedido
    }
    catch(err){
        alert('Error al procesar el pedido. Inténtalo de nuevo.');
        console.error(err);
    }
});

document.addEventListener('click', (e) => {
    const btn = e.target.closest('[data-action="add-to-cart"]');

    if (!btn) return;

    const item = {
        id : btn.dataset.movieId,
        titulo : btn.dataset.movieTitle,
        precio : btn.dataset.moviePrice,
        imagen : btn.dataset.movieImage,
        director : btn.dataset.director,
        fecha : btn.dataset.fecha
    };

    addToCart(item);
    renderCart();
});