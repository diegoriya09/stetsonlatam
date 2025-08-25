// js/profile.js

document.addEventListener('DOMContentLoaded', async () => {
    const jwt = localStorage.getItem('jwt');
    if (!jwt) {
        window.location.href = 'index.php';
        return;
    }

    // --- LÓGICA PARA NAVEGAR ENTRE PANELES (TABS) ---
    const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
    const contentPanels = document.querySelectorAll('.content-panel');
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.dataset.target;
            
            navLinks.forEach(l => l.classList.remove('active'));
            contentPanels.forEach(p => p.classList.remove('active'));

            this.classList.add('active');
            document.getElementById(targetId).classList.add('active');
        });
    });

    // --- FUNCIONES PARA CARGAR Y MOSTRAR DATOS ---
    const fetchAndRender = async (endpoint, containerId, renderer) => {
        try {
            const res = await fetch(endpoint, { headers: { 'Authorization': 'Bearer ' + jwt } });
            const data = await res.json();
            const container = document.getElementById(containerId);
            container.innerHTML = ''; // Limpiar
            if (data.success) {
                renderer(data, container);
            } else {
                container.innerHTML = `<p>${data.message || 'Error loading data.'}</p>`;
            }
        } catch (error) {
            console.error(`Error fetching from ${endpoint}:`, error);
        }
    };

    // Renderizadores para cada tipo de dato
    const renderUser = (data) => {
        if (data.user) {
            const user = data.user;
            document.getElementById('sidebar-username').textContent = user.name;
            document.getElementById('sidebar-avatar').textContent = user.name.charAt(0).toUpperCase();
            document.getElementById('overview-username').textContent = user.name.split(' ')[0];
        }
    };
    const renderOrders = (data, container) => {
        // ... (código para renderizar la tabla de órdenes, como lo tenías)
    };
    const renderAddresses = (data, container) => {
        if (data.addresses && data.addresses.length > 0) {
            data.addresses.forEach(addr => {
                const div = document.createElement('div');
                div.className = 'info-card';
                div.innerHTML = `<strong>${addr.street_address}</strong><p>${addr.city}, ${addr.state} ${addr.postal_code}</p><p>${addr.country}</p>`;
                container.appendChild(div);
            });
        } else {
            container.innerHTML = '<p>You have no saved addresses.</p>';
        }
    };
    const renderPayments = (data, container) => {
        if (data.payment_methods && data.payment_methods.length > 0) {
            data.payment_methods.forEach(pm => {
                const div = document.createElement('div');
                div.className = 'info-card';
                div.innerHTML = `<strong>${pm.card_type}</strong><p>**** **** **** ${pm.last_four_digits}</p><p>Expires: ${pm.expiry_date}</p>`;
                container.appendChild(div);
            });
        } else {
            container.innerHTML = '<p>You have no saved payment methods.</p>';
        }
    };

    // --- INICIALIZACIÓN ---
    fetchAndRender('php/user/get_user.php', 'sidebar-username', renderUser);
    fetchAndRender('php/order/get_orders.php', 'orders-table-container', renderOrders);
    fetchAndRender('php/user/get_addresses.php', 'address-list', renderAddresses);
    fetchAndRender('php/user/get_payment_methods.php', 'payment-method-list', renderPayments);
});