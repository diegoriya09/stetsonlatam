document.addEventListener('DOMContentLoaded', async () => {

   const jwt = localStorage.getItem('jwt');
   if (!jwt) {
      window.location.href = 'index.php';
      return;
   }

   // Obtener datos del usuario
   try {
    const response = await fetch('php/user/get_user.php', {
      method: 'GET',
      headers: {
        Authorization: `Bearer ${localStorage.getItem('jwtToken')}`, // Asegúrate de que el token JWT esté almacenado en localStorage
      },
    });

    const data = await response.json();
    if (data.success) {
      document.getElementById('profile-name').textContent = data.user.name;
    } else {
      console.error('Error fetching user:', data.message);
    }
  } catch (error) {
    console.error('Error:', error);
  }

   // Obtener órdenes recientes
   const ordersRes = await fetch('php/order/get_orders.php', {
      headers: { 'Authorization': 'Bearer ' + jwt }
   });
   const ordersData = await ordersRes.json();
   console.log('ordersData:', ordersData); // Verificar datos recibidos del backend
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

document.getElementById("view-all-orders-btn").addEventListener("click", () => {
  const jwt = localStorage.getItem("jwt");
  if (jwt) {
    window.location.href = "myorders.php";
  } else {
    alert("You need to log in to view all orders.");
  }
});