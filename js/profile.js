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
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.dataset.target;
            navLinks.forEach(l => l.classList.remove('active'));
            contentPanels.forEach(p => p.classList.remove('active'));
            this.classList.add('active');
            document.getElementById(targetId).classList.add('active');
        });
    });

    // --- CARGAR DATOS DEL USUARIO ---
    try {
        const userRes = await fetch('php/user/get_user.php', {
            headers: { 'Authorization': 'Bearer ' + jwt }
        });
        const userData = await userRes.json();
        
        if (userData.success && userData.user) {
            const user = userData.user;
            // Poner el nombre en la barra lateral
            document.getElementById('sidebar-username').textContent = user.name;
            // Poner la inicial en el avatar
            document.getElementById('sidebar-avatar').textContent = user.name.charAt(0).toUpperCase();
            // Poner el primer nombre en el saludo de bienvenida
            document.getElementById('overview-username').textContent = user.name.split(' ')[0];
        } else {
            console.error('Error fetching user:', userData.message);
        }
    } catch (error) {
        console.error('Network error fetching user:', error);
    }

    // --- CARGAR ÓRDENES RECIENTES ---
    try {
        const ordersRes = await fetch('php/order/get_orders.php', {
            headers: { 'Authorization': 'Bearer ' + jwt }
        });
        const ordersData = await ordersRes.json();
        const ordersTbody = document.getElementById('orders-tbody');
        ordersTbody.innerHTML = ''; // Limpiar "Loading..."

        if (ordersData.success && ordersData.orders.length > 0) {
            ordersData.orders.forEach(order => {
                const tr = document.createElement('tr');
                const orderDate = new Date(order.fecha).toLocaleDateString('en-US', {
                    year: 'numeric', month: 'long', day: 'numeric'
                });
                const orderStatus = order.estado.toLowerCase();

                tr.innerHTML = `
                    <td>#${order.id}</td>
                    <td>${orderDate}</td>
                    <td><span class="status-badge status-${orderStatus}">${order.estado}</span></td>
                    <td>$${parseFloat(order.total).toFixed(2)}</td>
                `;
                ordersTbody.appendChild(tr);
            });
        } else {
            ordersTbody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 2rem;">You have not placed any orders yet.</td></tr>`;
        }
    } catch (error) {
        console.error('Network error fetching orders:', error);
        document.getElementById('orders-tbody').innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 2rem;">Error loading orders.</td></tr>`;
    }
});