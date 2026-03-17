import {
    cartState,
    persistCartState,
    recalculateCartTotal
} from "./cartState.js";

function recalcTotal(){
    return recalculateCartTotal();
}

function parseCartPrice(value) {
    const parsedPrice = Number.parseFloat(value);
    return Number.isFinite(parsedPrice) ? parsedPrice : 0;
}

function normalizeCartItem(item) {
    return {
        ...item,
        precio: parseCartPrice(item.precio ?? item.price)
    };
}

/**
 * 
 * @param {*} item 
 * @returns
 * Está función se encarga de agregar un item al carrito.
 * Primero se verifica si existe el item, si ya existe no se agrega nuevamente.
 * No se aumenta el número de unidades porque solo se permite un único item por película.
 */
export function addToCart(item) {
    const normalizedItem = normalizeCartItem(item);
    const exists = cartState.items.find(i => i.id === normalizedItem.id);

    if (exists) return false;

    cartState.items.push(normalizedItem);
    recalcTotal();
    persistCartState();

    return true;
}

/**
 * 
 * @param {*} id 
 * @returns 
 * Esta función sirve para eliminar items del carrito. Primera se busca en el array,
 * si hay una coincidencia se elimina, sino se sale de la función.
 */
export function eraseMovieFromCart(id){
    const index = cartState.items.findIndex((item) => item.id === id);

    if (index === -1 ) return false;

    cartState.items.splice(index, 1);
    recalcTotal();
    persistCartState();

    return true;
}

export function clearCart() {
    cartState.items = [];
    cartState.total = 0;
    persistCartState();
}

/**
 * 
 * @returns 
 * Esta función se usa para calcular la fecha de devolución de la película alquilada.
 */
export function movieDate(){
    const date = new Date();
    date.setDate(date.getDate() + 14);
    
    const day = date.getDate().toString().padStart(2, '0');
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    const year = date.getFullYear();
    
    return `${day}/${month}/${year}`;
}

/**
 * 
 * @returns 
 * Función asíncrona que se encarga del checkout una vez se clicka el botón de finalizar compra.
 */
export async function checkout(){

    //Creamos el pedido en la BBDD
    const resOrder = await fetch('/proyecto-agile-intermodular/php/crear_pedido.php', {
        method : 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({total: cartState.total})
    })

    if (!resOrder.ok) throw new Error(`HTTP ${resOrder.status}`);
    const { orderId } = await resOrder.json();

    //Creamos el detalle del pedido en la BBDD que luego será relevante para mostrar el resumen.
    const res = await fetch('/proyecto-agile-intermodular/php/detalle_pedido.php', {
        method : 'POST',
        headers : {'Content-Type': 'application/json'},
        body: JSON.stringify({
            orderId: orderId,
            itemsData: cartState.items.map(item => ({
                movieId: item.id,
                precio: item.precio
            }))
        })
    });

    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const data = await res.json();

    /* Guardamos el pedido para que la página de resumen lo pueda leer */
    sessionStorage.setItem('orderSummary', JSON.stringify({
        orderId: orderId
    }));

    window.location.href = `/proyecto-agile-intermodular/views/create_order.php?orderId=${orderId}`;
    return data;
}