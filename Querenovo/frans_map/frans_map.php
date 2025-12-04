<style>
  .h2tag{
    padding-bottom: 29px;
    margin-top: -10px !important;
    font-size: 30px;
    color: white;
    background: #434244;
    font-size: 2.5rem;
    font-weight: 600;
    margin-top: 0;
    
  }
.custom-tooltip {
  background: linear-gradient(135deg, #006c86, #00bcd4); 
  color: #fff;                      
  border: 2px solid #ffffff33;      
  border-radius: 8px;               
  padding: 6px 10px;
  font-size: 13px;
  font-weight: bold;
  font-family: Arial, sans-serif;
  box-shadow: 0 4px 10px rgba(0,0,0,0.3);
  /* transition: all 0.3s ease-in-out; */
  pointer-events: none;             
}

.custom-tooltip b {
  color: #ffeb3b; 
  font-size: 14px;
}

.leaflet-interactive {
  transition: all 0.3s ease-in-out;
}
  html, body {
    margin: 0;
    padding: 0;
    height: 100%;      
    
  }
.leaflet-control-container{
    display: none;
}
  #map {
    height: 100vh;      
    width: 100%;
    position: relative;
    overflow: hidden;  
    margin-top: -10px;
  }

  #mapInfo {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(255,255,255,0.8);
    padding: 5px;
    border-radius: 4px;
    font-family: Arial;
    font-size: 14px;
    z-index: 1000;
  }

</style>

<style>
#zoom-buttons button {
    width: 40px;
    height: 40px;
    font-size: 24px;
    margin: 2px;
    border: none;
    border-radius: 4px;
    background-color: red; 
    color: white;
    cursor: pointer;
    box-shadow: 1px 1px 4px rgba(0,0,0,0.3);
}
#zoom-buttons button:hover {
    background-color: darkred;
}
</style>


<!-- <div id="zoom-buttons" style="position: absolute; top: 10px; left: 10px; z-index: 1000;">
    <button id="zoom-in">+</button>
    <button id="zoom-out">-</button>
</div> -->

  <h2 class="text-center h2tag">Découvrez les Experts Locaux dans Votre Département</h2>


<div id="map" style="height: 660px; width: 100%; position: relative;"></div>




<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>


<script>
  const mapDiv = document.getElementById('map');

  
  const map = L.map(mapDiv, {
  minZoom: 6  
}).setView([46.5, 2.5], 6);


map.scrollWheelZoom.disable();
map.doubleClickZoom.disable();
map.touchZoom.disable();
map.boxZoom.disable();
map.keyboard.disable();
map.dragging.disable();



  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 18
  }).addTo(map);

  
  const worldMask = [
    [[-90, -180], [-90, 180], [90, 180], [90, -180], [-90, -180]]
  ];
  L.polygon(worldMask, { color: 'white', fillColor: '#434244', fillOpacity: 1 }).addTo(map);

  
  fetch('https://raw.githubusercontent.com/gregoiredavid/france-geojson/master/departements-version-simplifiee.geojson')
    .then(res => res.json())
    .then(departmentsGeoJSON => {
      L.geoJSON(departmentsGeoJSON, {
        style: { 
            color: "#0299ce", 
            weight: 1, 
            fillColor: "#006c86",
            fillOpacity: 0.7
        },
        onEachFeature: (feature, layer) => {
          const center = layer.getBounds().getCenter();
          L.marker(center, {
            icon: L.divIcon({ className: 'department-label' })
          }).addTo(map);

          layer.bindTooltip(
            `<b>${feature.properties.nom}</b><br>Code: ${feature.properties.code}`,
            { 
                permanent: false,  
                direction: 'auto',
                opacity: 0.9,
                className: 'custom-tooltip'
            }
            );

           layer.on('mouseover', function() {
                layer.setStyle({
                    fillColor: "#ba1613",
                    color: "#0299ce", 
                    weight: 2.5,
                    fillOpacity: 0.7
                });
                layer.bringToFront(); 
            });

            layer.on('mouseout', function() {
                layer.setStyle({
                    color: "#0299ce", 
                    weight: 1, 
                    fillColor: "#006c86",
                    fillOpacity: 0.7
                });
            });

          layer.on('click', () => {
            location.href=`https://www.quirenov.fr/resultats?department_code=${feature.properties.code}`;
          });
        }
      }).addTo(map);

      map.fitBounds(L.geoJSON(departmentsGeoJSON).getBounds());
    });

  const zoomSpan = document.getElementById('zoomLevel');
  const sizeSpan = document.getElementById('mapSize');

  function updateMapInfo() {
    
    if (map.getZoom() < 5) map.setZoom(5);

    const container = map.getContainer();
    if (container.offsetWidth < 1583) container.style.width = '100%';
    if (container.offsetHeight < 600) container.style.height = '100%';

    zoomSpan.textContent = map.getZoom();
    const size = map.getSize();
    sizeSpan.textContent = `${size.x}px × ${size.y}px`;
  }

  
  map.on('zoom', updateMapInfo);
  map.on('resize', updateMapInfo);

  
  updateMapInfo();

</script>
<script>

document.getElementById('zoom-in').addEventListener('click', function() {
    map.zoomIn();
});

document.getElementById('zoom-out').addEventListener('click', function() {
    map.zoomOut();
});
</script>

<style>
  .department-label {
    width: 16px !important;
    font-size: 12px;
    color: white;
  }
</style>
