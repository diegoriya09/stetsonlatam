// js/store_locator.js
let map;
let allStores = []; // Guardamos todas las tiendas una vez
let currentMarkers = []; // Para llevar registro de los marcadores

async function initMap() {
   const defaultPosition = { lat: 6.244, lng: -75.581 };
   map = new google.maps.Map(document.getElementById("map"), {
      center: defaultPosition,
      zoom: 4, // Un zoom más alejado al inicio
   });

   await loadAllStores(); // Cargamos todas las tiendas una sola vez
   setupSearch(); // Configuramos el botón de búsqueda
   getUserLocation(); // Intentamos obtener la ubicación
}

// Carga TODAS las tiendas al iniciar
const loadAllStores = async () => {
   try {
      const response = await fetch('php/get_stores.php');
      const data = await response.json();
      if (data.success) {
         allStores = data.stores;
      }
   } catch (error) { console.error('Error fetching stores:', error); }
};

// Pide la ubicación del usuario
const getUserLocation = () => {
   if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(
         (position) => {
            const userPos = { lat: position.coords.latitude, lng: position.coords.longitude };
            map.setCenter(userPos);
            map.setZoom(8);
            processStores(userPos, allStores);
         },
         () => { processStores(null, allStores); }
      );
   } else { processStores(null, allStores); }
};

// Procesa un CONJUNTO de tiendas
const processStores = (userPosition, storesToProcess) => {
   if (userPosition) {
      storesToProcess.forEach(store => {
         const storePos = { lat: parseFloat(store.latitude), lng: parseFloat(store.longitude) };
         store.distance = haversine_distance(userPosition, storePos);
      });
      storesToProcess.sort((a, b) => a.distance - b.distance);
   }
   renderStoreList(storesToProcess);
   renderMapMarkers(storesToProcess);
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

// Pone los marcadores en el mapa (con limpieza previa)
const renderMapMarkers = (storesToRender) => {
   // Limpiamos los marcadores anteriores
   currentMarkers.forEach(marker => marker.setMap(null));
   currentMarkers = [];

   const infoWindow = new google.maps.InfoWindow();
   const bounds = new google.maps.LatLngBounds(); // Para ajustar el zoom del mapa

   storesToRender.forEach(store => {
      const position = { lat: parseFloat(store.latitude), lng: parseFloat(store.longitude) };
      const marker = new google.maps.Marker({
         position: position,
         map: map,
         title: store.name,
      });
      currentMarkers.push(marker);

      marker.addListener('click', () => { /* ... se queda igual ... */ });
      bounds.extend(position); // Añadimos la posición al área visible
   });

   if (storesToRender.length > 0) {
      map.fitBounds(bounds); // Ajustamos el mapa para que se vean todos los marcadores
   }
};

// Configura la búsqueda
function setupSearch() {
   const searchBtn = document.getElementById('search-btn');
   const countryInput = document.getElementById('country-search');
   const cityInput = document.getElementById('city-search');

   searchBtn.addEventListener('click', async () => {
      const country = countryInput.value;
      const city = cityInput.value;

      // Construimos la URL con los parámetros de búsqueda
      const url = new URL('php/get_stores.php', window.location.origin);
      if (country) url.searchParams.append('country', country);
      if (city) url.searchParams.append('city', city);

      // Hacemos la petición con los filtros
      try {
         const response = await fetch(url);
         const data = await response.json();
         if (data.success) {
            processStores(null, data.stores); // Procesamos solo las tiendas filtradas
         }
      } catch (error) {
         console.error('Error during search:', error);
      }
   });
}

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