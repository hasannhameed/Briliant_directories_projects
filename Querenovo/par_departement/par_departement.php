    <!-- Leaflet -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<style>
  
/* Default desktop — no scroll */
.map-scroll-wrapper {
    overflow: hidden;
    width: 100%;
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
        height: 520px;
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
        max-height: 410px;
        overflow-y: auto;
    }

    .dep-tag{
        display: inline-block;
        padding: 6px 12px;
        margin: 5px;
        border-radius: 6px;
        border: 1px solid #d2d6dc;
        cursor: pointer;
    }
    .dep-tag:hover {
        background: #e10c1a;
        color: #fff;
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

                <div class="form-group">
                    <label>Par région</label>
                    <select class="form-control">
                        <option>Sélectionner une région</option>
                        <option>Île-de-France</option>
                        <option>Auvergne-Rhône-Alpes</option>
                        <option>Occitanie</option>
                    </select>
                </div>
            </div>

            <div class="box3">
                <div class="title">Tous les départements</div>

                <div class="department-list">

                    <a href="/resultats?department_code=01"><span class="dep-tag col-sm-6">01 Ain</span></a>
                    <a href="/resultats?department_code=02"><span class="dep-tag col-sm-5">02 Aisne</span></a>
                    <a href="/resultats?department_code=03"><span class="dep-tag col-sm-6">03 Allier</span></a>
                    <a href="/resultats?department_code=04"><span class="dep-tag col-sm-5">04 Alpes-de-Haute-Provence</span></a>
                    <a href="/resultats?department_code=05"><span class="dep-tag col-sm-6">05 Hautes-Alpes</span></a>
                    <a href="/resultats?department_code=06"><span class="dep-tag col-sm-5">06 Alpes-Maritimes</span></a>
                    <a href="/resultats?department_code=07"><span class="dep-tag col-sm-6">07 Ardèche</span></a>
                    <a href="/resultats?department_code=08"><span class="dep-tag col-sm-5">08 Ardennes</span></a>
                    <a href="/resultats?department_code=09"><span class="dep-tag col-sm-6">09 Ariège</span></a>
                    <a href="/resultats?department_code=10"><span class="dep-tag col-sm-5">10 Aube</span></a>
                    <a href="/resultats?department_code=11"><span class="dep-tag col-sm-6">11 Aude</span></a>
                    <a href="/resultats?department_code=12"><span class="dep-tag col-sm-5">12 Aveyron</span></a>
                    <a href="/resultats?department_code=13"><span class="dep-tag col-sm-6">13 Bouches-du-Rhône</span></a>
                    <a href="/resultats?department_code=14"><span class="dep-tag col-sm-5">14 Calvados</span></a>
                    <a href="/resultats?department_code=15"><span class="dep-tag col-sm-6">15 Cantal</span></a>
                    <a href="/resultats?department_code=16"><span class="dep-tag col-sm-5">16 Charente</span></a>
                    <a href="/resultats?department_code=17"><span class="dep-tag col-sm-6">17 Charente-Maritime</span></a>
                    <a href="/resultats?department_code=18"><span class="dep-tag col-sm-5">18 Cher</span></a>
                    <a href="/resultats?department_code=19"><span class="dep-tag col-sm-6">19 Corrèze</span></a>
                    <a href="/resultats?department_code=2A"><span class="dep-tag col-sm-5">2A Corse-du-Sud</span></a>
                    <a href="/resultats?department_code=2B"><span class="dep-tag col-sm-6">2B Haute-Corse</span></a>
                    <a href="/resultats?department_code=21"><span class="dep-tag col-sm-5">21 Côte-d'Or</span></a>
                    <a href="/resultats?department_code=22"><span class="dep-tag col-sm-6">22 Côtes-d'Armor</span></a>
                    <a href="/resultats?department_code=23"><span class="dep-tag col-sm-5">23 Creuse</span></a>
                    <a href="/resultats?department_code=24"><span class="dep-tag col-sm-6">24 Dordogne</span></a>
                    <a href="/resultats?department_code=25"><span class="dep-tag col-sm-5">25 Doubs</span></a>
                    <a href="/resultats?department_code=26"><span class="dep-tag col-sm-6">26 Drôme</span></a>
                    <a href="/resultats?department_code=27"><span class="dep-tag col-sm-5">27 Eure</span></a>
                    <a href="/resultats?department_code=28"><span class="dep-tag col-sm-6">28 Eure-et-Loir</span></a>
                    <a href="/resultats?department_code=29"><span class="dep-tag col-sm-5">29 Finistère</span></a>
                    <a href="/resultats?department_code=30"><span class="dep-tag col-sm-6">30 Gard</span></a>
                    <a href="/resultats?department_code=31"><span class="dep-tag col-sm-5">31 Haute-Garonne</span></a>
                    <a href="/resultats?department_code=32"><span class="dep-tag col-sm-6">32 Gers</span></a>
                    <a href="/resultats?department_code=33"><span class="dep-tag col-sm-5">33 Gironde</span></a>
                    <a href="/resultats?department_code=34"><span class="dep-tag col-sm-6">34 Hérault</span></a>
                    <a href="/resultats?department_code=35"><span class="dep-tag col-sm-5">35 Ille-et-Vilaine</span></a>
                    <a href="/resultats?department_code=36"><span class="dep-tag col-sm-6">36 Indre</span></a>
                    <a href="/resultats?department_code=37"><span class="dep-tag col-sm-5">37 Indre-et-Loire</span></a>
                    <a href="/resultats?department_code=38"><span class="dep-tag col-sm-6">38 Isère</span></a>
                    <a href="/resultats?department_code=39"><span class="dep-tag col-sm-5">39 Jura</span></a>
                    <a href="/resultats?department_code=40"><span class="dep-tag col-sm-6">40 Landes</span></a>
                    <a href="/resultats?department_code=41"><span class="dep-tag col-sm-5">41 Loir-et-Cher</span></a>
                    <a href="/resultats?department_code=42"><span class="dep-tag col-sm-6">42 Loire</span></a>
                    <a href="/resultats?department_code=43"><span class="dep-tag col-sm-5">43 Haute-Loire</span></a>
                    <a href="/resultats?department_code=44"><span class="dep-tag col-sm-6">44 Loire-Atlantique</span></a>
                    <a href="/resultats?department_code=45"><span class="dep-tag col-sm-5">45 Loiret</span></a>
                    <a href="/resultats?department_code=46"><span class="dep-tag col-sm-6">46 Lot</span></a>
                    <a href="/resultats?department_code=47"><span class="dep-tag col-sm-5">47 Lot-et-Garonne</span></a>
                    <a href="/resultats?department_code=48"><span class="dep-tag col-sm-6">48 Lozère</span></a>
                    <a href="/resultats?department_code=49"><span class="dep-tag col-sm-5">49 Maine-et-Loire</span></a>
                    <a href="/resultats?department_code=50"><span class="dep-tag col-sm-6">50 Manche</span></a>
                    <a href="/resultats?department_code=51"><span class="dep-tag col-sm-5">51 Marne</span></a>
                    <a href="/resultats?department_code=52"><span class="dep-tag col-sm-6">52 Haute-Marne</span></a>
                    <a href="/resultats?department_code=53"><span class="dep-tag col-sm-5">53 Mayenne</span></a>
                    <a href="/resultats?department_code=54"><span class="dep-tag col-sm-6">54 Meurthe-et-Moselle</span></a>
                    <a href="/resultats?department_code=55"><span class="dep-tag col-sm-5">55 Meuse</span></a>
                    <a href="/resultats?department_code=56"><span class="dep-tag col-sm-6">56 Morbihan</span></a>
                    <a href="/resultats?department_code=57"><span class="dep-tag col-sm-5">57 Moselle</span></a>
                    <a href="/resultats?department_code=58"><span class="dep-tag col-sm-6">58 Nièvre</span></a>
                    <a href="/resultats?department_code=59"><span class="dep-tag col-sm-5">59 Nord</span></a>
                    <a href="/resultats?department_code=60"><span class="dep-tag col-sm-6">60 Oise</span></a>
                    <a href="/resultats?department_code=61"><span class="dep-tag col-sm-5">61 Orne</span></a>
                    <a href="/resultats?department_code=62"><span class="dep-tag col-sm-6">62 Pas-de-Calais</span></a>
                    <a href="/resultats?department_code=63"><span class="dep-tag col-sm-5">63 Puy-de-Dôme</span></a>
                    <a href="/resultats?department_code=64"><span class="dep-tag col-sm-6">64 Pyrénées-Atlantiques</span></a>
                    <a href="/resultats?department_code=65"><span class="dep-tag col-sm-5">65 Hautes-Pyrénées</span></a>
                    <a href="/resultats?department_code=66"><span class="dep-tag col-sm-6">66 Pyrénées-Orientales</span></a>
                    <a href="/resultats?department_code=67"><span class="dep-tag col-sm-5">67 Bas-Rhin</span></a>
                    <a href="/resultats?department_code=68"><span class="dep-tag col-sm-6">68 Haut-Rhin</span></a>
                    <a href="/resultats?department_code=69"><span class="dep-tag col-sm-5">69 Rhône</span></a>
                    <a href="/resultats?department_code=70"><span class="dep-tag col-sm-6">70 Haute-Saône</span></a>
                    <a href="/resultats?department_code=71"><span class="dep-tag col-sm-5">71 Saône-et-Loire</span></a>
                    <a href="/resultats?department_code=72"><span class="dep-tag col-sm-6">72 Sarthe</span></a>
                    <a href="/resultats?department_code=73"><span class="dep-tag col-sm-5">73 Savoie</span></a>
                    <a href="/resultats?department_code=74"><span class="dep-tag col-sm-6">74 Haute-Savoie</span></a>
                    <a href="/resultats?department_code=75"><span class="dep-tag col-sm-5">75 Paris</span></a>
                    <a href="/resultats?department_code=76"><span class="dep-tag col-sm-6">76 Seine-Maritime</span></a>
                    <a href="/resultats?department_code=77"><span class="dep-tag col-sm-5">77 Seine-et-Marne</span></a>
                    <a href="/resultats?department_code=78"><span class="dep-tag col-sm-6">78 Yvelines</span></a>
                    <a href="/resultats?department_code=79"><span class="dep-tag col-sm-5">79 Deux-Sèvres</span></a>
                    <a href="/resultats?department_code=80"><span class="dep-tag col-sm-6">80 Somme</span></a>
                    <a href="/resultats?department_code=81"><span class="dep-tag col-sm-5">81 Tarn</span></a>
                    <a href="/resultats?department_code=82"><span class="dep-tag col-sm-6">82 Tarn-et-Garonne</span></a>
                    <a href="/resultats?department_code=83"><span class="dep-tag col-sm-5">83 Var</span></a>
                    <a href="/resultats?department_code=84"><span class="dep-tag col-sm-6">84 Vaucluse</span></a>
                    <a href="/resultats?department_code=85"><span class="dep-tag col-sm-5">85 Vendée</span></a>
                    <a href="/resultats?department_code=86"><span class="dep-tag col-sm-6">86 Vienne</span></a>
                    <a href="/resultats?department_code=87"><span class="dep-tag col-sm-5">87 Haute-Vienne</span></a>
                    <a href="/resultats?department_code=88"><span class="dep-tag col-sm-6">88 Vosges</span></a>
                    <a href="/resultats?department_code=89"><span class="dep-tag col-sm-5">89 Yonne</span></a>
                    <a href="/resultats?department_code=90"><span class="dep-tag col-sm-6">90 Territoire de Belfort</span></a>
                    <a href="/resultats?department_code=91"><span class="dep-tag col-sm-5">91 Essonne</span></a>
                    <a href="/resultats?department_code=92"><span class="dep-tag col-sm-6">92 Hauts-de-Seine</span></a>
                    <a href="/resultats?department_code=93"><span class="dep-tag col-sm-5">93 Seine-Saint-Denis</span></a>
                    <a href="/resultats?department_code=94"><span class="dep-tag col-sm-6">94 Val-de-Marne</span></a>
                    <a href="/resultats?department_code=95"><span class="dep-tag col-sm-5">95 Val-d'Oise</span></a>

                    <!-- Overseas -->
                    <a href="/resultats?department_code=971"><span class="dep-tag col-sm-6">971 Guadeloupe</span></a>
                    <a href="/resultats?department_code=972"><span class="dep-tag col-sm-5">972 Martinique</span></a>
                    <a href="/resultats?department_code=973"><span class="dep-tag col-sm-6">973 Guyane</span></a>
                    <a href="/resultats?department_code=974"><span class="dep-tag col-sm-5">974 La Réunion</span></a>
                    <a href="/resultats?department_code=976"><span class="dep-tag col-sm-6">976 Mayotte</span></a>

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
<script>
document.addEventListener("DOMContentLoaded", function () {

    
    const regionDepartments = {
        "Île-de-France":        ["75","77","78","91","92","93","94","95"],
        "Auvergne-Rhône-Alpes": ["01","03","07","15","26","38","42","43","63","69","73","74"],
        "Occitanie":             ["09","11","12","30","31","32","34","46","48","65","66","81","82"],

        
        "Provence-Alpes-Côte d'Azur": ["04","05","06","13","83","84"],
        "Hauts-de-France":             ["02","59","60","62","80"],
        "Normandie":                   ["14","27","50","61","76"],
        "Bretagne":                    ["22","29","35","56"],
        "Pays de la Loire":            ["44","49","53","72","85"],
        "Centre-Val de Loire":         ["18","28","36","37","41","45"],
        "Grand Est":                   ["08","10","51","52","54","55","57","67","68","88"],
        "Nouvelle-Aquitaine":          ["16","17","19","23","24","33","40","47","64","79","86","87"],
        "Bourgogne-Franche-Comté":     ["21","25","39","58","70","71","89","90"],
        "Corse":                       ["2A","2B"],
        "Outre-Mer":                   ["971","972","973","974","976"]
    };

    document.querySelectorAll(".dep-tag").forEach(tag => {
        const deptCode = tag.innerText.split(" ")[0]; 

        for (let region in regionDepartments) {
            if (regionDepartments[region].includes(deptCode)) {
                tag.setAttribute("data-region", region);
                break;
            }
        }
    });

});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const searchInput = document.querySelector(".box2 input.form-control");
    const regionSelect = document.querySelector(".box2 select.form-control");
    const departments = document.querySelectorAll(".dep-tag");

    function debounce(fn, delay) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    function filterDepartments() {
        const searchValue = searchInput.value.toLowerCase().trim();
        const selectedRegion = regionSelect.value.toLowerCase().trim();

        departments.forEach(dep => {
            const text = dep.innerText.toLowerCase();
            const region = dep.getAttribute("data-region")?.toLowerCase() || "";

            const matchSearch = text.includes(searchValue);
            const matchRegion = selectedRegion === "" || region === selectedRegion;

            
            dep.parentElement.style.display = (matchSearch && matchRegion) ? "block" : "none";
        });
    }

    searchInput.addEventListener("input", debounce(filterDepartments, 300));
    regionSelect.addEventListener("change", filterDepartments);
});
</script>
