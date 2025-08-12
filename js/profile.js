document.addEventListener('DOMContentLoaded', async () => { 
   
   const jwt = localStorage.getItem('jwt');
    if (!jwt) {
        window.location.href = 'index.php';
        return;
    }

    // Obtener datos del usuario
    const userRes = await fetch('php/user/get_user.php', {
        headers: { 'Authorization': 'Bearer ' + jwt }
    });
    const userData = await userRes.json();
    if (userData.success && userData.user) {
        document.getElementById('profile-name').textContent = userData.user.name;
    } else {
        document.getElementById('profile-name').textContent = 'No user';
    }

    // Obtener órdenes recientes
    const ordersRes = await fetch('php/order/get_orders.php', {
        headers: { 'Authorization': 'Bearer ' + jwt }
    });
    const ordersData = await ordersRes.json();
    const ordersList = document.getElementById('orders-list');
    ordersList.innerHTML = '';
    if (ordersData.success && ordersData.pedidos && ordersData.pedidos.length > 0) {
        ordersData.pedidos.forEach(order => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="px-4 py-2 text-[#181411] text-sm font-normal leading-normal">#${order.id}</td>
                <td class="px-4 py-2 text-[#887563] text-sm font-normal leading-normal">${order.fecha}</td>
                <td class="px-4 py-2 text-sm font-normal leading-normal">
                    <span class="inline-block rounded px-3 py-1 bg-[#f4f2f0] text-[#181411]">${order.estado}</span>
                </td>
                <td class="px-4 py-2 text-[#887563] text-sm font-normal leading-normal">$${order.total}</td>
            `;
            ordersList.appendChild(tr);
        });
    } else {
        const tr = document.createElement('tr');
        tr.innerHTML = `<td colspan=\"4\" class=\"px-4 py-2 text-center text-[#887563]\">No orders found</td>`;
        ordersList.appendChild(tr);
    }
});