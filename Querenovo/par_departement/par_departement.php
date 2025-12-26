    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
  
/* Default desktop — no scroll */
.map-scroll-wrapper {
    overflow: hidden;
    width: 100%;
}




    .header-section {
        background-color: #0c2340;
        color: #fff;
        padding: 60px 0;
        text-align: center;
    }

    .box {
        background: #fff;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        margin-bottom: 30px;
		height: 800px;
		width: 100%;
    }

    .box2,.box3 {
        background: #fff;
        border-radius: 8px;
        padding: 25px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        margin-bottom: 30px;
	
    }
    .box3{
        height: 600px;
    }

    .title {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    /* MAP STYLES (Your Code) */
    #map {
        height: 660px;
        width: 100%;
        border-radius: 10px;
    }

    .leaflet-control-container {
        display: none;
    }

    .custom-tooltip {
        background: linear-gradient(135deg, #006c86, #00bcd4);
        color: #fff;
        border-radius: 8px;
        padding: 6px 10px;
        font-size: 13px;
        font-weight: bold;
        box-shadow: 0 4px 10px rgba(0,0,0,0.3);
        pointer-events: none;
    }

    .custom-tooltip b {
        color: #ffeb3b;
        font-size: 14px;
    }

    .department-label {
        width: 16px !important;
        font-size: 12px;
        color: white;
    }

    .department-list {
        max-height: 480px;
        overflow-y: auto;
    }

    .dep-tag{
        display: inline-block;
        padding: 6px 12px;
        margin: 5px;
        border-radius: 6px;
        border: 1px solid #d2d6dc;
        cursor: pointer;
        width: 48%;
    }
    .dep-tag:hover {
        background: #e10c1a;
        color: #fff;
    }
    /* Mobile screens: allow horizontal scrolling */
@media (max-width: 767px) {

    .map-scroll-wrapper {
        overflow-x: scroll;
        overflow-y: hidden;
        width: 100%;
        -webkit-overflow-scrolling: touch; /* smooth scrolling */
        border-radius: 10px;
    }

    #map {
        width: 700px !important; /* wider so user scrolls */
        height: 700px !important;
    }
    .dep-tag {
        display: inline-block;
        padding: 6px 12px;
        margin: 5px;
        border-radius: 6px;
        border: 1px solid #d2d6dc;
        cursor: pointer;
        width: fit-content;
    }
}

</style>

<!-- Header -->
<div class="header-section">
    <h1>La rénovation énergétique par département</h1>
    <p>Découvrez les acteurs, aides et initiatives près de chez vous</p>
</div>

<div class="container" style="margin-top: 40px;">
    <div class="row">

        <div class="col-sm-6">
            <div class="box">
                <div class="title">📍 Sélectionnez votre département</div>
                <div class="map-scroll-wrapper">
                    <div id="map"></div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="box2">
                <div class="title">🔍 Rechercher un département</div>

                <div class="form-group">
                    <label>Par nom ou numéro</label>
                    <input type="text" class="form-control"
                           placeholder="Ex : Ain, 01, Var…">
                </div>

                <!-- <div class="form-group">
                    <label>Par région</label>
                    <select class="form-control">
                        <option>Sélectionner une région</option>
                        <option>Île-de-France</option>
                        <option>Auvergne-Rhône-Alpes</option>
                        <option>Occitanie</option>
                    </select>
                </div> -->
            </div>

            <div class="box3">
                <div class="title">Tous les départements</div>
                <div class="department-list">
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    const map = L.map("map", { minZoom: 6 }).setView([46.5, 2.5], 6);

    map.scrollWheelZoom.disable();
    map.dragging.disable();
    map.doubleClickZoom.disable();
    map.touchZoom.disable();
    map.boxZoom.disable();
    map.keyboard.disable();

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
        maxZoom: 18
    }).addTo(map);

    /* Mask outside of France */
    const worldMask = [
        [[-90, -180], [-90, 180], [90, 180], [90, -180], [-90, -180]]
    ];
    L.polygon(worldMask, { fillColor: "#e5e9f2", fillOpacity: 1 }).addTo(map);

    fetch("https://raw.githubusercontent.com/gregoiredavid/france-geojson/master/departements-version-simplifiee.geojson")
    .then(res => res.json())
    .then(data => {
        L.geoJSON(data, {
            style: {
                fillColor: "#006c86",
                fillOpacity: 1,
                color: "black",
                weight: 0.8
            },
            onEachFeature: (feature, layer) => {

                layer.bindTooltip(
                    `<b>${feature.properties.nom}</b><br>Code : ${feature.properties.code}`,
                    {
                        className: "custom-tooltip",
                        direction: "auto",
                        opacity: 0.9
                    }
                );

                layer.on("mouseover", function () {
                    layer.setStyle({
                        fillColor: "#a72020ff",
                        weight: 1
                    });
                });

                layer.on("mouseout", function () {
                    layer.setStyle({
                        fillColor: "#006c86",
                        weight: 0.8
                    });
                });

                layer.on("click", () => {
                    location.href = `https://www.quirenov.fr/resultats?department_code=${feature.properties.code}`;
                });
            }
        }).addTo(map);
    });
</script>

