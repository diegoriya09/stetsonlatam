// js/cart.js
document.addEventListener("DOMContentLoaded", () => {
    const jwt = localStorage.getItem("jwt");
    loadCart(!!jwt); // Carga el carrito al iniciar (true si hay jwt, false si no)
});

// Esta función ahora será global para poder ser llamada desde producto.php
function addToCart(productData) {
    const jwt = localStorage.getItem("jwt");
    if (jwt) {
        // Usuario logueado: enviar al backend
        fetch('php/cart/add_to_cart.php', {
            method: 'POST',
            headers: {
                'Authorization': 'Bearer ' + jwt,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                producto_id: productData.id,
                cantidad: productData.quantity,
                color_id: productData.color,
                size_id: productData.size
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                Swal.fire({ icon: 'success', title: 'Added to cart!', showConfirmButton: false, timer: 1500 });
                // Aquí podrías actualizar el ícono del carrito en el header
            } else {
                Swal.fire({ icon: 'error', title: 'Error', text: data.message });
            }
        });
    } else {
        // Usuario no logueado: guardar en localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const existingProductIndex = cart.findIndex(item => 
            item.id === productData.id && item.color === productData.color && item.size === productData.size
        );

        if (existingProductIndex > -1) {
            cart[existingProductIndex].quantity += productData.quantity;
        } else {
            cart.push(productData);
        }
        localStorage.setItem('cart', JSON.stringify(cart));
        Swal.fire({ icon: 'success', title: 'Added to cart!', showConfirmButton: false, timer: 1500 });
    }
}

async function loadCart(isLoggedIn) {
    let cartItems = [];
    if (isLoggedIn) {
        const jwt = localStorage.getItem("jwt");
        const res = await fetch('php/cart/get_cart.php', { headers: { 'Authorization': 'Bearer ' + jwt } });
        const data = await res.json();
        if (data.success) {
            cartItems = data.cart;
        }
    } else {
        cartItems = JSON.parse(localStorage.getItem('cart')) || [];
    }
    renderCart(cartItems);
}

function renderCart(items) {
    const container = document.getElementById('cart-items-container');
    const summarySubtotal = document.getElementById('summary-subtotal');
    const summaryTotal = document.getElementById('summary-total');
    
    container.innerHTML = '';
    let subtotal = 0;
    
    if (items.length === 0) {
        container.innerHTML = '<p class="empty-cart">Your cart is empty.</p>';
    } else {
        items.forEach(item => {
            const itemTotal = item.price * item.cantidad;
            subtotal += itemTotal;
            const itemElement = document.createElement('div');
            itemElement.className = 'cart-item';
            itemElement.innerHTML = `
                <img src="${item.image}" alt="${item.name}" class="item-image">
                <div class="item-details">
                    <h3>${item.name}</h3>
                    ${item.size_name ? `<p>Size: ${item.size_name}</p>` : ''}
                    ${item.color_name ? `<p>Color: ${item.color_name}</p>` : ''}
                </div>
                <div class="item-quantity">
                    <button class="qty-btn" data-id="${item.cart_item_id}" data-qty="${item.cantidad - 1}">-</button>
                    <input type="text" value="${item.cantidad}" readonly>
                    <button class="qty-btn" data-id="${item.cart_item_id}" data-qty="${item.cantidad + 1}">+</button>
                </div>
                <div class="item-total">$${itemTotal.toFixed(2)}</div>
                <button class="item-remove" data-id="${item.cart_item_id}"><i class="fas fa-trash-alt"></i></button>
            `;
            container.appendChild(itemElement);
        });
    }
    
    summarySubtotal.textContent = `$${subtotal.toFixed(2)}`;
    summaryTotal.textContent = `$${subtotal.toFixed(2)}`;
}

// Event Listeners para actualizar y eliminar
document.getElementById('cart-items-container')?.addEventListener('click', async e => {
    const jwt = localStorage.getItem("jwt");
    if (!jwt) return; // Solo funciona para usuarios logueados

    const postData = async (url, body) => {
        const res = await fetch(url, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + jwt },
            body: JSON.stringify(body)
        });
        return res.json();
    };

    if (e.target.matches('.qty-btn')) {
        const cart_item_id = e.target.dataset.id;
        const cantidad = parseInt(e.target.dataset.qty);
        if (cantidad > 0) {
            await postData('php/cart/update_cart.php', { cart_item_id, cantidad });
        } else {
            await postData('php/cart/remove_from_cart.php', { cart_item_id });
        }
        loadCart(true);
    }
    if (e.target.matches('.item-remove, .item-remove *')) {
        const cart_item_id = e.target.closest('.item-remove').dataset.id;
        await postData('php/cart/remove_from_cart.php', { cart_item_id });
        loadCart(true);
    }
});