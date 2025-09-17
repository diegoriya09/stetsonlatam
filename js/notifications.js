// js/notifications.js (CÓDIGO COMPLETO Y MEJORADO)

document.addEventListener('DOMContentLoaded', () => {
   const jwt = localStorage.getItem('jwt');
   if (!jwt) return; // Si no hay sesión, no hacer nada

   const bellBtn = document.getElementById('notifications-btn');
   const panel = document.getElementById('notifications-panel');
   const badge = document.getElementById('unread-count');

   // Si no existen los elementos en la página, detener el script.
   if (!bellBtn || !panel || !badge) {
      return;
   }

   async function fetchNotifications() {
      try {
         const res = await fetch('/php/user/get_notifications', { headers: { 'Authorization': 'Bearer ' + jwt } });
         const data = await res.json();

         if (data.success) {
            // Actualizar contador
            if (data.unread_count > 0) {
               badge.textContent = data.unread_count;
               badge.style.display = 'flex';
            } else {
               badge.style.display = 'none';
            }

            // Llenar panel
            panel.innerHTML = '';
            if (data.notifications.length === 0) {
               panel.innerHTML = '<div class="notification-item">No tienes notificaciones nuevas.</div>';
            } else {
               data.notifications.forEach(n => {
                  panel.innerHTML += `
                            <a href="${n.link || '#'}" class="notification-item ${n.is_read == 0 ? 'unread' : ''}">
                                <p>${n.message}</p>
                                <small>${new Date(n.created_at).toLocaleString()}</small>
                            </a>`;
               });
            }
         }
      } catch (e) { console.error('Error fetching notifications:', e); }
   }

   bellBtn.addEventListener('click', () => {
      const isVisible = panel.style.display === 'block';
      panel.style.display = isVisible ? 'none' : 'block';

      if (!isVisible && badge.style.display !== 'none') {
         // Marcar como leídas cuando se abre el panel
         fetch('/php/user/mark_notifications_read', { method: 'POST', headers: { 'Authorization': 'Bearer ' + jwt } });
         badge.style.display = 'none';
      }
   });

   // Cierra el panel si se hace clic fuera de él
   document.addEventListener('click', function (event) {
      if (!bellBtn.contains(event.target) && !panel.contains(event.target)) {
         panel.style.display = 'none';
      }
   });

   fetchNotifications(); // Cargar al inicio
   setInterval(fetchNotifications, 60000); // Comprobar cada minuto
});