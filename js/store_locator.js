// js/store_locator.js
let map;
let stores = [];

// Esta función es llamada por el script de Google Maps una vez que está listo
async function initMap() {
   // Coordenadas por defecto (ej. Medellín) si el usuario no da permiso
   const defaultPosition = { lat: 6.244, lng: -75.581 };

   map = new google.maps.Map(document.getElementById("map"), {
      center: defaultPosition,
      zoom: 5,
   });

   // Cargamos las tiendas y luego pedimos la geolocalización
   await loadStores();
   getUserLocation();
}

// Carga las tiendas desde nuestra API
const loadStores = async () => {
   try {
      const response = await fetch('php/get_stores.php');
      const data = await response.json();
      if (data.success) {
         stores = data.stores;
      } else {
         document.getElementById('store-results-list').innerHTML = '<p>No se han encontrado tiendas.</p>';
      }
   } catch (error) {
      console.error('Error fetching stores:', error);
   }
};

// Pide la ubicación del usuario
const getUserLocation = () => {
   if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
         (position) => {
            const userPos = {
               lat: position.coords.latitude,
               lng: position.coords.longitude,
            };
            // Centramos el mapa en el usuario
            map.setCenter(userPos);
            map.setZoom(8);
            // Calculamos distancias y re-renderizamos todo
            processStores(userPos);
         },
         () => {
            // El usuario no dio permiso, usamos los datos por defecto
            processStores(null);
         }
      );
   } else {
      // El navegador no soporta geolocalización
      processStores(null);
   }
};

// Procesa las tiendas: calcula distancia, ordena y muestra
const processStores = (userPosition) => {
   if (userPosition) {
      stores.forEach(store => {
         const storePos = { lat: parseFloat(store.latitude), lng: parseFloat(store.longitude) };
         store.distance = haversine_distance(userPosition, storePos);
      });
      // Ordenamos por distancia
      stores.sort((a, b) => a.distance - b.distance);
   }
   renderStoreList(stores);
   renderMapMarkers(stores);
};

// Dibuja la lista de tiendas
const renderStoreList = (storesToRender) => {
   const storeListContainer = document.getElementById('store-results-list');
   storeListContainer.innerHTML = '';

   storesToRender.forEach(store => {
      const storeCard = document.createElement('div');
      storeCard.className = 'store-card';

      let distanceHTML = '';
      if (store.distance !== undefined) {
         distanceHTML = `<p class="store-distance">${store.distance.toFixed(1)} km away</p>`;
      }

      storeCard.innerHTML = `
            <img src="${store.image_url || 'img/default.jpg'}" alt="${store.name}" class="store-image">
            <div class="store-info">
                <h3>${store.name}</h3>
                <p>${store.address || ''}<br>${store.city}, ${store.country} ${store.postal_code || ''}</p>
                ${distanceHTML}
            </div>
            <div class="store-hours">
                <h4>HORARIO</h4>
                <p>${store.hours || 'N/A'}</p>
            </div>
        `;
      storeListContainer.appendChild(storeCard);
   });
};

// Pone los marcadores en el mapa
const renderMapMarkers = (storesToRender) => {
   const infoWindow = new google.maps.InfoWindow();

   storesToRender.forEach(store => {
      const marker = new google.maps.Marker({
         position: { lat: parseFloat(store.latitude), lng: parseFloat(store.longitude) },
         map: map,
         title: store.name,
      });

      marker.addListener('click', () => {
         const content = `<strong>${store.name}</strong><br>${store.address}`;
         infoWindow.setContent(content);
         infoWindow.open(map, marker);
      });
   });
};

// Fórmula para calcular distancia entre dos puntos geográficos
function haversine_distance(mk1, mk2) {
   var R = 6371.0710; // Radio de la Tierra en km
   var rlat1 = mk1.lat * (Math.PI / 180);
   var rlat2 = mk2.lat * (Math.PI / 180);
   var difflat = rlat2 - rlat1;
   var difflon = (mk2.lng - mk1.lng) * (Math.PI / 180);

   var d = 2 * R * Math.asin(Math.sqrt(Math.sin(difflat / 2) * Math.sin(difflat / 2) + Math.cos(rlat1) * Math.cos(rlat2) * Math.sin(difflon / 2) * Math.sin(difflon / 2)));
   return d;
}