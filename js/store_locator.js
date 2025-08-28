// js/store_locator.js
document.addEventListener('DOMContentLoaded', () => {
   const storeListContainer = document.getElementById('store-results-list');

   const loadStores = async () => {
      try {
         const response = await fetch('../php/get_stores.php');
         const data = await response.json();

         if (data.success && data.stores.length > 0) {
            storeListContainer.innerHTML = ''; // Limpiar "Loading..."
            data.stores.forEach(store => {
               const storeCard = document.createElement('div');
               storeCard.className = 'store-card';
               storeCard.innerHTML = `
                        <img src="${store.image_url || 'img/default.jpg'}" alt="${store.name}" class="store-image">
                        <div class="store-info">
                            <h3>${store.name}</h3>
                            <p>${store.address || ''}<br>${store.city}, ${store.country} ${store.postal_code || ''}</p>
                        </div>
                        <div class="store-hours">
                            <h4>HORARIO</h4>
                            <p>${store.hours || 'N/A'}</p>
                        </div>
                    `;
               storeListContainer.appendChild(storeCard);
            });
         } else {
            storeListContainer.innerHTML = '<p>No se han encontrado tiendas.</p>';
         }
      } catch (error) {
         console.error('Error fetching stores:', error);
         storeListContainer.innerHTML = '<p>Error al cargar las tiendas.</p>';
      }
   };

   loadStores();
});