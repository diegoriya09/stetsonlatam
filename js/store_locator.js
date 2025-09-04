// js/store_locator.js
let map;
let allStores = [];
let currentMarkers = [];
let locations = {}; // Para guardar la lista de países y ciudades

// 1. El script se inicia al cargar la página
document.addEventListener('DOMContentLoaded', () => {
   loadGoogleMaps();
});

// 2. Pide nuestra clave de API segura a nuestro propio servidor
const loadGoogleMaps = async () => {
   try {
      const response = await fetch('php/api/get_maps_key');
      const data = await response.json();
      const apiKey = data.apiKey;

      // 3. Crea la etiqueta <script> de Google Maps y la añade al HTML
      const script = document.createElement('script');
      script.src = `https://maps.googleapis.com/maps/api/js?key=${apiKey}&callback=initMap`;
      script.async = true;
      script.defer = true;
      document.head.appendChild(script);

   } catch (error) {
      console.error("No se pudo cargar la clave API de Google Maps.", error);
      document.getElementById('map').innerHTML = '<p>Error al cargar el mapa.</p>';
   }
};

async function initMap() {
   const defaultPosition = { lat: 4.710, lng: -74.072 }; // Centro de Colombia
   map = new google.maps.Map(document.getElementById("map"), {
      center: defaultPosition,
      zoom: 5,
   });

   await initializeLocator();
   setupSearch();
   getUserLocation();
}

// NUEVA FUNCIÓN de inicialización
const initializeLocator = async () => {
   try {
      const response = await fetch('php/get_locations');
      const data = await response.json();
      if (data.success) {
         locations = data.locations;
         populateCountrySelect();
      }
   } catch (error) { console.error('Error fetching locations:', error); }
};

// NUEVA FUNCIÓN para poblar el dropdown de países
const populateCountrySelect = () => {
   const countrySelect = document.getElementById('country-select');
   countrySelect.innerHTML = '<option value="">Todos los países</option>'; // Opción por defecto
   for (const country in locations) {
      const option = document.createElement('option');
      option.value = country;
      option.textContent = country;
      countrySelect.appendChild(option);
   }

   countrySelect.addEventListener('change', () => {
      populateCitySelect(countrySelect.value);
   });
};

const populateCitySelect = (selectedCountry) => {
   const citySelect = document.getElementById('city-select');
   citySelect.innerHTML = '<option value="">Todas las ciudades</option>';

   if (selectedCountry && locations[selectedCountry]) {
      citySelect.disabled = false;
      locations[selectedCountry].forEach(city => {
         const option = document.createElement('option');
         option.value = city;
         option.textContent = city;
         citySelect.appendChild(option);
      });
   } else {
      citySelect.disabled = true;
      citySelect.innerHTML = '<option value="">Selecciona un país primero</option>';
   }
};

// Carga TODAS las tiendas al iniciar
const loadAllStores = async () => {
   try {
      const response = await fetch('php/get_stores');
      const data = await response.json();
      if (data.success) {
         allStores = data.stores;
      }
   } catch (error) { console.error('Error al obtener las tiendas:', error); }
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

        // --- LÓGICA PARA PROCESAR EL HORARIO ---
        let hoursHTML = '<p>N/A</p>'; // Valor por defecto
        if (store.hours) {
            // 1. Separamos el texto por cada punto y coma (;)
            const hoursArray = store.hours.split(';').map(line => line.trim());
            
            // 2. Creamos un elemento <li> por cada línea
            hoursHTML = '<ul>' + hoursArray.map(line => `<li>${line}</li>`).join('') + '</ul>';
        }

        let distanceHTML = store.distance ? `<p class="store-distance">${store.distance.toFixed(1)} km away</p>` : '';

        storeCard.innerHTML = `
            <img src="${store.image_url || 'img/default.jpg'}" alt="${store.name}" class="store-image">
            <div class="store-info">
                <h3>${store.name}</h3>
                <p>${store.address || ''}<br>${store.city}, ${store.country} ${store.postal_code || ''}</p>
                ${distanceHTML}
            </div>
            <div class="store-hours">
                <h4>HORARIO</h4>
                ${hoursHTML}
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

      marker.addListener('click', () => {
         const content = `<strong>${store.name}</strong><br>${store.address}`;
         infoWindow.setContent(content);
         infoWindow.open(map, marker);
      });
      bounds.extend(position); // Añadimos la posición al área visible
   });

   if (storesToRender.length > 0) {
      map.fitBounds(bounds); // Ajustamos el mapa para que se vean todos los marcadores
   }
};

// Configura la búsqueda
function setupSearch() {
   const searchBtn = document.getElementById('search-btn');

   searchBtn.addEventListener('click', async () => {
      const country = document.getElementById('country-select').value;
      const city = document.getElementById('city-select').value;

      const url = new URL('php/get_stores', window.location.origin);
      if (country) url.searchParams.append('country', country);
      if (city) url.searchParams.append('city', city);

      try {
         const response = await fetch(url);
         const data = await response.json();
         if (data.success) {
            processStores(null, data.stores);
         }
      } catch (error) { console.error('Error durante la búsqueda:', error); }
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