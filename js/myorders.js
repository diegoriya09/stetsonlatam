//   if (!jwt) return location.href = 'index.php';

//   fetch('php/order/get_orders.php', {
//     headers: {
//       'Authorization': 'Bearer ' + jwt,
//       'Content-Type': 'application/json'
//     }
//   })
//     .then(res => res.json())
//     .then(pedidos => {
//       const container = document.getElementById('pedidos-container');
//       if (pedidos.length === 0) {
//         // container.innerHTML = "<p>No orders yet.</p>";
//         return;
//       }

//       pedidos.forEach(p => {
//         const div = document.createElement('div');
//         div.classList.add('pedido-item');
//         div.innerHTML = `
//           <h3>Order #${p.id}</h3>
//           <p><strong>Date:</strong> ${new Date(p.fecha).toLocaleString()}</p>
//           <p><strong>Status:</strong> ${p.estado}</p>
//           <p><strong>Total:</strong> $${p.total.toLocaleString()}</p>
//           <p><strong>Products:</strong> ${p.total_items}</p>
//           <button class="ver-detalle" data-id="${p.id}">View Details</button>
//         `;
//         container.appendChild(div);
//       });

//       document.querySelectorAll('.ver-detalle').forEach(btn => {
//         btn.addEventListener('click', () => {
//           const id = btn.dataset.id;
//           fetch(`php/order/get_detail_order.php?pedido_id=${id}`, {
//             headers: {
//               'Authorization': 'Bearer ' + jwt,
//               'Content-Type': 'application/json'
//             }
//           })
//             .then(res => res.json())
//             .then(productos => {
//               const detalleDiv = document.getElementById('detalle-pedido');
//               detalleDiv.innerHTML = productos.map(p => `
//                 <div class="producto-detalle">
//                   <p><strong>${p.nombre_producto}</strong></p>
//                   <p>Quantity: ${p.cantidad} - $${p.precio}</p>
//                   <p>Color: ${p.color_nombre || '-'} / Size: ${p.size_nombre || '-'}</p>
//                 </div>
//               `).join('');
//               document.getElementById('detalle-modal').style.display = 'block';
//             });
//         });
//       });
//     });

//   document.querySelector('.close-btn').addEventListener('click', () => {
//     document.getElementById('detalle-modal').style.display = 'none';
//   });
// });
