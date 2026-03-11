import { cartState } from "./cartState,js";

function recalcTotal(){
    cartState.total = cartState.items.reduce((acc, item) => 
        acc + item.price, 0);
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
    const exists = cartState.items.find(i => i.id === item.id);
    if (exists) return;
    cartState.items.push(item);
    recalcTotal();
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
    if (index === -1 ) return;
    cartState,items.splice(index, 1);
    recalcTotal();
}

/**
 * 
 * @returns 
 * Función asíncrona que se encarga del checkout una vez se clicka el botón de finalizar compra.
 */
export async function checkout(){
    const res = await fetch('/proyecto-agile-intermodular/php/crear_pedido.php', {
        method : 'POST',
        headers : {'Content-Type': 'application/json'},
        body: JSON.stringify({items: cartState.items, total: cartState.total})
    });

    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
}