document.addEventListener("DOMContentLoaded", () => {
  const jwt = localStorage.getItem("jwt");

  // Oculta la sección de órdenes si no hay JWT
  const ordersSection = document.getElementById("orders-section");
  if (!jwt) {
    ordersSection.innerHTML = "<p>Log in to view your orders.</p>";
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
        // ✅ Llama al backend para obtener las órdenes del usuario
        fetch("php/order/get_all_orders.php", {
          method: "GET",
          headers: {
            "Content-Type": "application/json",
            Authorization: "Bearer " + jwt,
          },
        })
          .then((res) => res.json())
          .then((ordersData) => {
            if (ordersData.success && ordersData.orders.length > 0) {
              renderOrders(ordersData.orders);
            } else {
              ordersSection.innerHTML =
                "<p>No orders found for this user.</p>";
            }
          })
          .catch((error) => {
            console.error("Error getting orders:", error);
            ordersSection.innerHTML =
              "<p>Error getting your orders. Please try again later.</p>";
          });
      } else {
        ordersSection.innerHTML = `<p>${data.message}</p>`;
      }
    })
    .catch((err) => {
      console.error("Error verifying session:", err);
      ordersSection.innerHTML =
        "<p>Could not verify your session. Please try again later.</p>";
    });

  // Cierra el modal de order

  const ordermodal = document.querySelector(".ordermodal");
  const closeBtn = document.querySelector(".close-modal-order");

  closeBtn.addEventListener("click", () => {
    ordermodal.classList.add("hidden");
  });

  // También cerrar si se hace click fuera del contenido
  ordermodal.addEventListener("click", (e) => {
    if (e.target === ordermodal) {
      ordermodal.classList.add("hidden");
    }
  });
});

function openModalWithOrderDetails(orderId) {
  const ordermodal = document.querySelector(".ordermodal");
  const detailsContainer = document.getElementById("orderDetails");

  fetch(`php/order/get_detail_order.php?id=${orderId}`)
    .then((res) => res.json())
    .then((data) => {
      if (data.success) {
        if (data.details.length === 0) {
          detailsContainer.innerHTML = "<p>No details found for this order.</p>";
          ordermodal.classList.remove("hidden");
          return;
        }

        let html = "<ul>";
        data.details.forEach((item) => {
          html += `
            <li>
              <strong>${item.name}</strong><br>
              Quantity: ${item.cantidad}<br>
              Price: $${item.price}<br>
              Size: ${item.size_nombre || "N/A"}<br>
              ${item.color_nombre ? `Color: ${item.color_nombre}` : ""}
            </li>
          `;
        });
        html += "</ul>";
        detailsContainer.innerHTML = html;
        ordermodal.classList.remove("hidden");
      } else {
        detailsContainer.innerHTML = "<p>Error loading details.</p>";
        ordermodal.classList.remove("hidden");
      }
    })
    .catch((err) => {
      console.error(err);
      detailsContainer.innerHTML = "<p>Network error.</p>";
      ordermodal.classList.remove("hidden");
    });
}

// Función para mostrar las órdenes
function renderOrders(orders) {
  const container = document.getElementById("orders-section");
  container.innerHTML = "<h2>My Orders</h2>";

  orders.forEach((order) => {
    const orderDiv = document.createElement("div");
    orderDiv.classList.add("order");

    orderDiv.innerHTML = `
      <h3>Orden #${order.id}</h3>
      <p><strong>Date:</strong> ${order.fecha}</p>
      <p><strong>Total:</strong> $${order.total}</p>
      <p><strong>Status:</strong> ${order.estado || "Pendiente"}</p>
      <button class="btn-detalle" data-id="${order.id}">View Details</button>
    `;

    container.appendChild(orderDiv);
  });

  // Agrega evento a cada botón
  document.querySelectorAll(".btn-detalle").forEach((btn) => {
    btn.addEventListener("click", () => {
      const orderId = btn.getAttribute("data-id");
      openModalWithOrderDetails(orderId);
    });
  });
}
