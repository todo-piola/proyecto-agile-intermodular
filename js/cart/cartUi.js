import { cartState } from "./cartState.js";
import { addToCart, eraseMovieFromCart, checkout } from "./cartResume";

const cartItemsEl = document.getElementById('cart-items');
const cartTotal = document.getElementById('buy-button');

export function renderCart(){
    cartItemsEl.textContent = '';

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
        precio.textContent = `Precio: ${item.precio}€`;

        const plusBtn = document.createElement('button');
        plusBtn.className = 'eliminate-movie';
        plusBtn.textContent = 'Eliminar';


        div.appendChild(img);
        div.appendChild(nombre);
        div.appendChild(precio);
        div.appendChild(plusBtn);
        div.appendChild(cantidad);
        div.appendChild(minusBtn);

        cartItemsEl.appendChild(div);
    });

    const total = document.createElement('p');
    total.textContent = `Total: ${cartState.total.toFixed(2)}€`;
    cartTotal.textContent = `Finalizar compra (${cartState.total.toFixed(2)}€)`;
}

cartItemsEl.addEventListener('click', (e) => {
    if (e.target.classList.contains('eliminate-movie')) {
        const itemId = e.target.dataset.idCartItem;
        eraseMovieFromCart(itemId);
    }
})

buyButton.addEventListener('click', async() => {
    try{
        await checkout();
        cartState.items = [];
        cartState.total = 0;
        renderCart();
        //Aquí tendré que redireccionar a la página de resumen de pedido
    }
    catch(err){
        alert('Error al procesar el pedido. Inténtalo de nuevo.');
        console.error(err);
    }
})