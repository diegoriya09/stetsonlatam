// js/profile.js

document.addEventListener('DOMContentLoaded', async () => {
    // Obtiene el token de autenticación del almacenamiento local
    const jwt = localStorage.getItem('jwt');
    if (!jwt) {
        // Si no hay token, redirige al inicio
        window.location.href = '../index.php';
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

    // --- CARGAR DATOS DEL USUARIO DESDE EL SERVIDOR ---
    try {
        const userRes = await fetch('../php/user/get_user.php', {
            headers: { 'Authorization': 'Bearer ' + jwt }
        });
        const userData = await userRes.json();
        
        if (userData.success && userData.user) {
            const user = userData.user;
            // Actualiza el nombre de usuario en la barra lateral
            document.getElementById('sidebar-username').textContent = user.name;
            // Actualiza el avatar con la inicial del nombre
            document.getElementById('sidebar-avatar').textContent = user.name.charAt(0).toUpperCase();
            // Actualiza el saludo de bienvenida
            document.getElementById('overview-username').textContent = user.name.split(' ')[0]; // Muestra solo el primer nombre
        } else {
            console.error('Error al obtener datos del usuario:', userData.message);
        }
    } catch (error) {
        console.error('Error de red al obtener datos del usuario:', error);
    }

    // --- CARGAR ÓRDENES RECIENTES DESDE EL SERVIDOR ---
    try {
        const ordersRes = await fetch('../php/order/get_orders.php', {
            headers: { 'Authorization': 'Bearer ' + jwt }
        });
        const ordersData = await ordersRes.json();
        const ordersTbody = document.getElementById('orders-tbody');
        ordersTbody.innerHTML = ''; // Limpia el mensaje "Loading..."

        if (ordersData.success && ordersData.orders.length > 0) {
            ordersData.orders.forEach(order => {
                const tr = document.createElement('tr');
                // Formatea la fecha para que sea legible (ej: August 25, 2025)
                const orderDate = new Date(order.fecha).toLocaleDateString('en-US', {
                    year: 'numeric', month: 'long', day: 'numeric'
                });
                const orderStatus = order.estado.toLowerCase();

                // Crea la fila de la tabla con los datos de la orden
                tr.innerHTML = `
                    <td>#${order.id}</td>
                    <td>${orderDate}</td>
                    <td><span class="status-badge status-${orderStatus}">${order.estado}</span></td>
                    <td>$${parseFloat(order.total).toFixed(2)}</td>
                `;
                ordersTbody.appendChild(tr);
            });
        } else {
            // Muestra un mensaje si no hay órdenes
            ordersTbody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 2rem;">You have not placed any orders yet.</td></tr>`;
        }
    } catch (error) {
        console.error('Error de red al obtener las órdenes:', error);
        ordersTbody.innerHTML = `<tr><td colspan="4" style="text-align: center; padding: 2rem;">Error loading orders.</td></tr>`;
    }
});