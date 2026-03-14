async function getOrderDetails(orderId) {
    const res = await fetch(`/proyecto-agile-intermodular/php/get_pedido.php?id=${orderId}`);
    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    return res.json();
}

function showItems(items){
    const template = document.querySelector('.templateResume');
    
    items.forEach(item => {
        const clone = template.cloneNode(true);
        clone.style.display = 'block';
        clone.querySelector('img').src = item.imagen;
        clone.querySelector('.card-title').textContent = item.titulo;
        clone.querySelector('.card-director-name').textContent = item.director;
        clone.querySelector('.rent-date').textContent = item.fecha;
        clone.querySelector('.price-rent').textContent = `${Number(item.precio).toFixed(2).replace('.', ',')}€`;

        document.querySelector('#product-list').appendChild(clone);
    });
}

function renderTotal(total){
    document.querySelector('#total-price').textContent = `Total: ${Number(total).toFixed(2).replace('.', ',')}€`;
}

export async function loadOrderSummary(){
    try{
        const { orderId} = JSON.parse(sessionStorage.getItem('orderSummary'));
        const pedido = await getOrderDetails(orderId);

        showItems(pedido.items);
        renderTotal(pedido.total);
    }
    catch(error){
        console.error('Error al cargar los detalles del pedido:', error);
    }
}   