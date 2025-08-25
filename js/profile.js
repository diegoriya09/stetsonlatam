// js/profile.js
document.addEventListener('DOMContentLoaded', async () => {
    const jwt = localStorage.getItem('jwt');
    if (!jwt) {
        window.location.href = 'index.php';
        return;
    }

    // --- LÓGICA DE PANELES (TABS) ---
    const navLinks = document.querySelectorAll('.sidebar-nav .nav-link');
    const contentPanels = document.querySelectorAll('.content-panel');
    navLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            e.preventDefault();
            const targetId = this.dataset.target;
            navLinks.forEach(l => l.classList.remove('active'));
            contentPanels.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(targetId).classList.add('active');
        });
    });

    // --- LÓGICA PARA MODALES ---
    const addressModal = document.getElementById('address-modal');
    const paymentModal = document.getElementById('payment-modal');
    const addAddressBtn = document.querySelector('[data-target="addresses-panel"] .add-new-btn');
    const addPaymentBtn = document.querySelector('[data-target="payment-panel"] .add-new-btn');

    const openModal = (modal) => modal.classList.add('active');
    const closeModal = (modal) => modal.classList.remove('active');

    if (addAddressBtn) addAddressBtn.addEventListener('click', () => openModal(addressModal));
    if (addPaymentBtn) addPaymentBtn.addEventListener('click', () => openModal(paymentModal));

    document.querySelectorAll('.modal-close-btn, .modal-backdrop').forEach(el => {
        el.addEventListener('click', () => {
            if (addressModal) closeModal(addressModal);
            if (paymentModal) closeModal(paymentModal);
        });
    });

    // --- FUNCIÓN GENÉRICA PARA PEDIR DATOS ---
    const fetchData = async (endpoint) => {
        try {
            const res = await fetch(endpoint, { headers: { 'Authorization': 'Bearer ' + jwt } });
            if (!res.ok) {
                if (res.status === 401) {
                    localStorage.removeItem('jwt');
                    window.location.href = 'index.php';
                }
                throw new Error(`Server responded with status: ${res.status}`);
            }
            return await res.json();
        } catch (error) {
            console.error(`Network error fetching from ${endpoint}:`, error);
            return { success: false, message: 'Network error' };
        }
    };

    // --- CARGAR DATOS DEL USUARIO ---
    const loadUserData = async () => {
        const userData = await fetchData('php/user/get_user.php');
        if (userData.success && userData.user) {
            const user = userData.user;
            document.getElementById('sidebar-username').textContent = user.name;
            document.getElementById('sidebar-avatar').textContent = user.name.charAt(0).toUpperCase();
            document.getElementById('overview-username').textContent = user.name.split(' ')[0];
        }
    };

    // --- CARGAR ÓRDENES ---
    const loadOrdersData = async () => {
        const ordersData = await fetchData('php/order/get_orders.php');
        const ordersTbody = document.getElementById('orders-tbody');
        ordersTbody.innerHTML = '';
        if (ordersData.success && ordersData.orders.length > 0) {
            ordersData.orders.forEach(order => {
                const tr = document.createElement('tr');
                const orderDate = new Date(order.fecha).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                const orderStatus = order.estado ? order.estado.toLowerCase() : 'unknown';
                tr.innerHTML = `<td>#${order.id}</td><td>${orderDate}</td><td><span class="status-badge status-${orderStatus}">${order.estado}</span></td><td>$${parseFloat(order.total).toFixed(2)}</td>`;
                ordersTbody.appendChild(tr);
            });
        } else {
            ordersTbody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 2rem;">You have not placed any orders yet.</td></tr>`;
        }
    };

    // --- LÓGICA FORMULARIO DE DIRECCIÓN ---
    const addAddressForm = document.getElementById('add-address-form');
    if (addAddressForm) {
        addAddressForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            const data = Object.fromEntries(formData.entries());

            const res = await fetch('php/user/add_address.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + jwt
                },
                body: JSON.stringify(data)
            });
            const result = await res.json();
            if (result.success) {
                Swal.fire('¡Éxito!', result.message, 'success');
                closeModal(addressModal);
                loadAddressesData(); // Recargamos la lista de direcciones
            } else {
                Swal.fire('Error', result.message, 'error');
            }
        });
    }

    // --- LÓGICA FORMULARIO DE PAGO (SIMULACIÓN) ---
    const addPaymentForm = document.getElementById('add-payment-form');
    if (addPaymentForm) {
        addPaymentForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const cardNumber = document.getElementById('card_number').value;
            const data = {
                card_type: document.getElementById('card_type').value,
                expiry_date: document.getElementById('expiry_date').value,
                last_four: cardNumber.slice(-4)
            };

            // ... (Lógica fetch similar a la de dirección, apuntando a add_payment_method.php) ...
            Swal.fire('¡Éxito!', 'Método de pago añadido (simulación).', 'success');
            closeModal(paymentModal);
            loadPaymentsData(); // Recargamos la lista de pagos
        });
    }

    // --- CARGAR Y MOSTRAR DIRECCIONES ---
    const loadAddressesData = async () => {
        const addrData = await fetchData('php/user/get_addresses.php');
        const container = document.getElementById('address-list');
        container.innerHTML = '';
        if (addrData.success && addrData.addresses.length > 0) {
            addrData.addresses.forEach(addr => {
                const div = document.createElement('div');
                div.className = 'info-card';
                div.innerHTML = `
                    <strong>${addr.street_address}</strong>
                    <p>${addr.city}, ${addr.state} ${addr.postal_code}</p>
                    <p>${addr.country}</p>
                `;
                container.appendChild(div);
            });
        } else {
            container.innerHTML = '<p>You have no saved addresses.</p>';
        }
    };

    // --- CARGAR Y MOSTRAR MÉTODOS DE PAGO ---
    const loadPaymentsData = async () => {
        const paymentData = await fetchData('php/user/get_payment_methods.php');
        const container = document.getElementById('payment-method-list');
        container.innerHTML = '';
        if (paymentData.success && paymentData.payment_methods.length > 0) {
            paymentData.payment_methods.forEach(pm => {
                const div = document.createElement('div');
                div.className = 'info-card';
                div.innerHTML = `
                    <strong>${pm.card_type}</strong>
                    <p>**** **** **** ${pm.last_four_digits}</p>
                    <p>Expires: ${pm.expiry_date}</p>
                `;
                container.appendChild(div);
            });
        } else {
            container.innerHTML = '<p>You have no saved payment methods.</p>';
        }
    };

    // --- EJECUTAR TODAS LAS FUNCIONES AL CARGAR LA PÁGINA ---
    loadUserData();
    loadOrdersData();
    loadAddressesData();
    loadPaymentsData();
});