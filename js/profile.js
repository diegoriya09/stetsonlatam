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
        document.getElementById('profile-email').textContent = userData.user.email;
    }

    // Obtener órdenes recientes
    const ordersRes = await fetch('php/order/get_orders.php', {
        headers: { 'Authorization': 'Bearer ' + jwt }
    });
    const ordersData = await ordersRes.json();
    if (ordersData.success && ordersData.orders) {
        const ordersList = document.getElementById('orders-list');
        ordersList.innerHTML = '';
        ordersData.orders.forEach(order => {
            const li = document.createElement('li');
            li.textContent = `Order #${order.id} - $${order.total} - ${order.fecha} - ${order.estado}`;
            ordersList.appendChild(li);
        });
    }
});