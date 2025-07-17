document.addEventListener("DOMContentLoaded", () => {
  const jwt = localStorage.getItem("jwt");

  // Oculta la sección de órdenes si no hay JWT
  const ordersSection = document.getElementById("orders-section");
  if (!jwt) {
    ordersSection.innerHTML = "<p>Inicia sesión para ver tus órdenes.</p>";
    return;
  }

  // Verifica el token con el servidor
  fetch("php/check_session.php", {
    method: "GET",
    headers: {
      Authorization: "Bearer " + jwt,
    },
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.logged_in) {
        const userId = data.user_id;
        // ✅ Llama al backend para obtener las órdenes del usuario
        fetch("php/order/get_orders.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({ user_id: userId }),
        })
          .then((res) => res.json())
          .then((ordersData) => {
            if (ordersData.success && ordersData.orders.length > 0) {
              renderOrders(ordersData.orders);
            } else {
              ordersSection.innerHTML =
                "<p>No se encontraron órdenes para este usuario.</p>";
            }
          })
          .catch((error) => {
            console.error("Error obteniendo órdenes:", error);
            ordersSection.innerHTML =
              "<p>Error al obtener tus órdenes. Intenta más tarde.</p>";
          });
      } else {
        ordersSection.innerHTML = `<p>${data.message}</p>`;
      }
    })
    .catch((err) => {
      console.error("Error verificando sesión:", err);
      ordersSection.innerHTML =
        "<p>No se pudo verificar tu sesión. Intenta más tarde.</p>";
    });
});

// Función para mostrar las órdenes
function renderOrders(orders) {
  const container = document.getElementById("orders-section");
  container.innerHTML = "<h2>Mis Órdenes</h2>";

  orders.forEach((order) => {
    const orderDiv = document.createElement("div");
    orderDiv.classList.add("order");

    orderDiv.innerHTML = `
      <h3>Orden #${order.id}</h3>
      <p><strong>Fecha:</strong> ${order.fecha}</p>
      <p><strong>Total:</strong> $${order.total}</p>
      <p><strong>Estado:</strong> ${order.estado}</p>
    `;

    container.appendChild(orderDiv);
  });
}
