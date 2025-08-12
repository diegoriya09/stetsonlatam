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
   console.log('userData:', userData);
   if (userData.success && userData.user) {
    document.getElementById('profile-name').textContent = userData.user.name;
    // Si tienes otro campo para el nombre en la sección principal:
    const heroName = document.getElementById('profile-name-hero');
    if (heroName) heroName.textContent = userData.user.name;
    const email = document.getElementById('profile-email');
    if (email) email.textContent = userData.user.email;
}

   // Obtener órdenes recientes
   const ordersRes = await fetch('php/order/get_orders.php', {
      headers: { 'Authorization': 'Bearer ' + jwt }
   });
   const ordersData = await ordersRes.json();
   console.log('ordersData:', ordersData);
   const ordersList = document.getElementById('orders-list');
   ordersList.innerHTML = '';
   if (ordersData.success && ordersData.orders && ordersData.orders.length > 0) {
      ordersData.orders.forEach(order => {
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