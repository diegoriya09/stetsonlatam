document.addEventListener("DOMContentLoaded", () => {
  const jwt = localStorage.getItem("jwt");

  // Si no hay JWT, no muestres la sección de órdenes ni lances el fetch
  if (!jwt) {
    document.getElementById("pedidos-container").innerHTML = `
      <p>Please log in to view your orders.</p>
    `;
    return;
  }

  // Si hay JWT, validar sesión
  fetch("php/check_session.php", {
    method: "GET",
    headers: {
      "Authorization": "Bearer " + jwt
    }
  })
    .then(res => res.json())
    .then(data => {
      if (data.logged_in) {
        loadOrders(jwt);
      } else {
        redirectToLogin();
      }
    })
    .catch(err => {
      console.error("Error checking session:", err);
      redirectToLogin();
    });

  // Cierre del modal
  document.addEventListener("click", e => {
    if (e.target.classList.contains("close-btn")) {
      document.getElementById("detalle-modal").style.display = "none";
    }
  });
});


function redirectToLogin() {
  Swal.fire({
    icon: 'error',
    title: 'Session expired',
    text: 'Please log in again.',
    confirmButtonText: 'OK'
  }).then(() => {
    localStorage.removeItem("jwt");
    window.location.href = "index.php";
  });
}

function loadOrders(jwt) {
  fetch('php/order/get_orders.php', {
    headers: {
      'Authorization': 'Bearer ' + jwt,
      'Content-Type': 'application/json'
    }
  })
    .then(res => {
      if (!res.ok) throw new Error("Unauthorized");
      return res.json();
    })
    .then(pedidos => {
      const container = document.getElementById("pedidos-container");
      container.innerHTML = '';

      if (!pedidos.length) {
        container.innerHTML = "<p>No orders yet.</p>";
        return;
      }

      pedidos.forEach(p => {
        const div = document.createElement("div");
        div.classList.add("pedido-item");
        div.innerHTML = `
          <h3>Order #${p.id}</h3>
          <p><strong>Date:</strong> ${new Date(p.fecha).toLocaleString()}</p>
          <p><strong>Status:</strong> ${p.estado}</p>
          <p><strong>Total:</strong> $${p.total.toLocaleString()}</p>
          <p><strong>Products:</strong> ${p.total_items}</p>
          <button class="ver-detalle" data-id="${p.id}">View Details</button>
        `;
        container.appendChild(div);
      });

      setupDetalleButtons(jwt);
    })
    .catch(err => {
      console.error("Error loading orders:", err);
      redirectToLogin();
    });
}

function setupDetalleButtons(jwt) {
  document.querySelectorAll(".ver-detalle").forEach(btn => {
    btn.addEventListener("click", () => {
      const pedidoId = btn.dataset.id;

      fetch(`php/order/get_detail_order.php?pedido_id=${pedidoId}`, {
        headers: {
          'Authorization': 'Bearer ' + jwt,
          'Content-Type': 'application/json'
        }
      })
        .then(res => {
          if (!res.ok) throw new Error("Failed to get detail");
          return res.json();
        })
        .then(productos => {
          const detalleDiv = document.getElementById("detalle-pedido");
          detalleDiv.innerHTML = '';

          if (!productos.length) {
            detalleDiv.innerHTML = "<p>No details found.</p>";
          } else {
            detalleDiv.innerHTML = productos.map(p => `
              <div class="producto-detalle">
                <p><strong>${p.nombre_producto}</strong></p>
                <p>Quantity: ${p.cantidad} - $${p.precio}</p>
                <p>Color: ${p.color_nombre || '-'} / Size: ${p.size_nombre || '-'}</p>
              </div>
            `).join('');
          }

          document.getElementById("detalle-modal").style.display = "block";
        })
        .catch(err => {
          console.error("Error getting order details:", err);
          Swal.fire("Error", "Unable to load order details", "error");
        });
    });
  });
}