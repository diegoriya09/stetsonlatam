document.addEventListener('DOMContentLoaded', async () => {
    const jwt = localStorage.getItem('jwt');
    if (!jwt) {
        window.location.href = '/index';
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
    const addAddressBtn = document.querySelector('#addresses-panel .add-new-btn');
    const addPaymentBtn = document.querySelector('#payment-panel .add-new-btn');

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

    // --- FUNCIONES GENÉRICAS PARA COMUNICACIÓN CON API ---
    const fetchData = async (endpoint) => {
        try {
            const res = await fetch('/' + endpoint, { headers: { 'Authorization': 'Bearer ' + jwt } });
            if (!res.ok) {
                if (res.status === 401) {
                    localStorage.removeItem('jwt');
                    window.location.href = '/index';
                }
                throw new Error(`El servidor respondió con el estado: ${res.status}`);
            }
            return await res.json();
        } catch (error) {
            console.error(`Error de red al obtener ${endpoint}:`, error);
            return { success: false, message: 'Error de red' };
        }
    };

    const postData = async (endpoint, data) => {
        try {
            const res = await fetch('/' + endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Authorization': 'Bearer ' + jwt },
                body: JSON.stringify(data)
            });
            return await res.json();
        } catch (error) {
            console.error(`Error de red al publicar en ${endpoint}:`, error);
            return { success: false, message: 'Error de red' };
        }
    };

    // --- FUNCIONES PARA CARGAR Y RENDERIZAR DATOS ---
    const loadUserData = async () => {
        const userData = await fetchData('php/user/get_user');
        if (userData.success && userData.user) {
            const user = userData.user;
            document.getElementById('sidebar-username').textContent = user.name;
            document.getElementById('sidebar-avatar').textContent = user.name.charAt(0).toUpperCase();
            document.getElementById('overview-username').textContent = user.name.split(' ')[0];
        }
    };
    const loadOrdersData = async () => {
        const ordersData = await fetchData('php/order/get_orders');
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
            ordersTbody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 2rem;">Aún no ha realizado ningún pedido.</td></tr>`;
        }
    };

    const loadAddressesData = async () => {
        const addrData = await fetchData('php/user/get_addresses');
        const container = document.getElementById('address-list');
        container.innerHTML = '';
        if (addrData.success && addrData.addresses.length > 0) {
            addrData.addresses.forEach(addr => {
                const div = document.createElement('div');
                div.className = 'info-card';
                div.innerHTML = `<div class="info-card-header"><strong>${addr.street_address}</strong><button class="delete-btn delete-address-btn" data-id="${addr.id}" title="Eliminar">&times;</button></div><p>${addr.city}, ${addr.state} ${addr.postal_code}</p><p>${addr.country}</p>`;
                container.appendChild(div);
            });
        } else {
            container.innerHTML = '<p>No tienes direcciones guardadas.</p>';
        }
    };

    const loadPaymentsData = async () => {
        const paymentData = await fetchData('php/user/get_payment_methods');
        const container = document.getElementById('payment-method-list');
        container.innerHTML = '';
        if (paymentData.success && paymentData.payment_methods.length > 0) {
            paymentData.payment_methods.forEach(pm => {
                const div = document.createElement('div');
                div.className = 'info-card';
                div.innerHTML = `<div class="info-card-header"><strong>${pm.card_type}</strong><button class="delete-btn delete-payment-btn" data-id="${pm.id}" title="Eliminar">&times;</button></div><p>**** **** **** ${pm.last_four_digits}</p><p>Expires: ${pm.expiry_date}</p>`;
                container.appendChild(div);
            });
        } else {
            container.innerHTML = '<p>No tienes métodos de pago guardados.</p>';
        }
    };

    // --- LÓGICA PARA FORMULARIOS Y ELIMINACIÓN ---
    document.getElementById('add-address-form')?.addEventListener('submit', async function (e) {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(this).entries());
        const result = await postData('php/user/add_address.php', data);
        if (result.success) {
            Swal.fire('¡Éxito!', result.message, 'success');
            closeModal(addressModal);
            this.reset();
            loadAddressesData();
        } else { Swal.fire('Error', result.message, 'error'); }
    });

    document.getElementById('add-payment-form')?.addEventListener('submit', async function (e) {
        e.preventDefault();
        const data = Object.fromEntries(new FormData(this).entries());
        const result = await postData('php/user/add_payment_method', data);
        if (result.success) {
            Swal.fire('¡Éxito!', result.message, 'success');
            closeModal(paymentModal);
            this.reset();
            loadPaymentsData();
        } else { Swal.fire('Error', result.message, 'error'); }
    });

    document.getElementById('profile-content')?.addEventListener('click', async function (e) {
        const target = e.target;
        let id, endpoint, callback;

        if (target.matches('.delete-address-btn')) {
            id = target.dataset.id;
            endpoint = 'php/user/delete_address';
            callback = loadAddressesData;
        } else if (target.matches('.delete-payment-btn')) {
            id = target.dataset.id;
            endpoint = 'php/user/delete_payment_method';
            callback = loadPaymentsData;
        } else {
            return;
        }

        Swal.fire({
            title: '¿Estás seguro?', text: "No podrás revertir esta acción.", icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#3f1e1f', cancelButtonColor: '#6b7280', confirmButtonText: 'Sí, eliminar'
        }).then(async (result) => {
            if (result.isConfirmed) {
                const res = await postData(endpoint, { id: id });
                if (res.success) {
                    Swal.fire('¡Eliminado!', res.message, 'success');
                    callback(); // Recargamos la lista correspondiente
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            }
        });
    });

    // --- EJECUTAR TODO AL CARGAR ---
    loadUserData();
    loadOrdersData();
    loadAddressesData();
    loadPaymentsData();
});