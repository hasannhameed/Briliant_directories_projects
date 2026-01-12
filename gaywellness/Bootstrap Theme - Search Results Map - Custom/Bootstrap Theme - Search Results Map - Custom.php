<?php

$fieldConfigurations = array(
    'membership_markers' => array(
        /*
        Upload markers to your File Manager and enter the marker for your membership below like:
        membership_id => 'marker URL',
        The default BD marker is: $w['line_color']
        */
        0 => $w["line_color"],
        999 => '/images/icons/pin-black.png',
		1 => '/images/icons/pin-red.png',
		2 => '/images/icons/pin-red.png',
		3 => '/images/icons/pin-red.png',
		7 => '/images/icons/pin-blue.png',
		6 => '/images/icons/pin-green.png',
		12 => '/images/icons/pin-orange.png',
		11 => '/images/icons/pin-pink.png',
        10 => '/images/icons/pin-yellow.png',
    )
);

?>
<div class="clearfix"></div>
<div class="search-results-map">
    <div id="map-actions">
        <button type="button" class="view-fullscreen btn btn-default"><i class="fa fa-expand"></i> Fullscreen</button>
        <button type="button" class="previous-property btn btn-default"><i class="fa fa-arrow-left"></i> Prev</button>
        <button type="button" class="next-property btn btn-default"><i class="fa fa-arrow-right"></i> Next</button>
    </div>
    <div id="map-canvas"></div>
</div>
<style>
    #map-canvas {
        height: 100%;
        margin-bottom: 15px;
        width: 100%;
        min-height: 300px;
    }
    #map-actions {
        position: absolute;
        top: 0;
        right: 0;
        z-index: 10;
        background-color: white;
        padding: 10px;
    }
    .info_window a i {
        margin-right: 3px;
    }
    .info_window a {
        color: #333!important;
        display: block;
        font-size: 12px;
        line-height: 1.2em;
        text-align: center;
        font-weight: 700;
        padding: 8px 10px;
        border-bottom: 1px solid #ccc;
    }
    .profile-map-label {
        color: rgba(100,100,100,0.7);;
        font-weight: bold;
        font-size: 13px;
        width: 72px;
        text-shadow: -1px 0 white, 0 1px white, 1px 0 white, 0 -1px white;
        text-align: left;
        line-height: 12px;
    }
    .info_window img {
        display: block;
        margin: 0 auto;
    }
    .info_window {
        max-width: 150px;
    }
    .gm-style-iw > .gm-style-iw-d {
        overflow: auto!important;
    }
    .gm-style-iw-t > .gm-style-iw.gm-style-iw-c {
        padding: 0!important;
        border-radius: 0!important;
    }
    button.gm-ui-hover-effect {
        background: white!important;
        right: 0!important;
        top: 0!important;
        height: 25px!important;
        width: 25px!important;
    }
    button.gm-ui-hover-effect > img {
        margin: auto!important;
    }
    .active-property > div {
        box-shadow: 0 7px 15px 1px rgba(166,166,166,0.7);
        background-color: #f5f5f5!important;
    }
    .search-results-map.full-screen {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 9999;
    }
</style>
<script src="https://thewebsitelauncher.com/resources/markerclusterer/markerclusterer.js"></script>

<script src="https://thewebsitelauncher.com/resources/google/markerwithlabel.js"></script>
<script>
    var searchMap = {
        /* Templates */
        infoWindowTemplate: `<div class="info_window">
		<img src="{property-picture}" style="max-height: 100px;">
		<a class="property-name" href="{property-url}">{property-name}</a>
		<a class="property-phone {property-phone-hidden}" href="tel:{property-phone-number}"><i class="fa fa-phone"></i> {property-phone-number}</a>
	</div>`,
        /* Variables */
        currentMap: '',
        currentMarkers: [],
        currentInfoWindow: undefined,
        zoom: 4,
        myPosition: '',
        bounds: '',
        currentProperty: {
            id: '',
            name: '',
            url: '',
            lat: '',
            lon: '',
            pic: ''
        },
        currentPropertyKey: 0,
        propertyLocation: {
            lat: 0,
            lng: 0
        },
        membershipMarkers : {
    <?php foreach ($fieldConfigurations['membership_markers'] as $key => $value) { ?>
    <?php echo $key; ?> : '<?php echo $value; ?>',
    <?php } ?>
    },
    availableProperties: [],
        /* Functionality */
        init : function() {
        <?php echo widget("Bootstrap Theme - Function - Google Maps Color Controller", "", $w["website_id"], $w); ?>
        var styledMap = new google.maps.StyledMapType(styles, {name: "Styled Map"});
        var mapLat = parseFloat(searchMap.getUrlParameter('lat'));
        var mapLng = parseFloat(searchMap.getUrlParameter('lng'));
        if (isNaN(mapLat) || isNaN(mapLng)) {
            mapLat = parseFloat('42.877742');
            mapLng = parseFloat('-97.380979');
        }
        var propertyLocation = {
            lat: mapLat,
            lng: mapLng
        };
        searchMap.propertyLocation = propertyLocation;
        var mapOptions = {
            center: searchMap.propertyLocation,
            mapTypeControlOptions: {mapTypeIds: [google.maps.MapTypeId.ROADMAP, 'map_style']},
            zoom: searchMap.zoom,
            controlSize: 26,
            mapTypeControl: false,
            streetViewControl: false,
            scrollwheel: true
        };
        searchMap.currentMap = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
        searchMap.currentMap.mapTypes.set('map_style', styledMap);
        searchMap.currentMap.setMapTypeId('map_style');
        searchMap.loadMarkers();
        var clusterOptions = {
            imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m',
            maxZoom: 19,
            minimumClusterSize: 2
        };
        searchMap.markerCluster = new MarkerClusterer(searchMap.currentMap, searchMap.currentMarkers, clusterOptions);
        /* Clickables */
        $(document).on("click",".next-property",function(){
            searchMap.nextProperty();
        });
        $(document).on("click",".previous-property",function(){
            searchMap.previousProperty();
        });
        $(document).on("click", ".property-element", function(){
            searchMap.setProperty($(this).find("> div"));
        });
        $(document).on("click", ".view-fullscreen, .mapView", function(){
            searchMap.toggleFullScreen();
            if ($('.view-fullscreen').html() == '<i class="fa fa-expand"></i> Fullscreen') {
                $('.view-fullscreen').html('<i class="fa fa-compress"></i> Exit Fullscreen');
            } else {
                $('.view-fullscreen').html('<i class="fa fa-expand"></i> Fullscreen');
            }
        });
        $(document).on("click", ".view-property", function(){
            window.location.href = searchMap.currentProperty.url;
        });
    },
    getUrlParameter : function(sParam) {
        var sPageURL = window.location.search.substring(1);
        var sURLVariables = sPageURL.split('&');

        for (var i = 0; i < sURLVariables.length; i++) {
            var sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] == sParam) {
                return decodeURIComponent(sParameterName[1]);
            }
        }
    },
    setMapMarkers : function(map, locations) {
        searchMap.bounds = new google.maps.LatLngBounds();
        var propertyKey;
        for (propertyKey = 0; propertyKey < locations.length; propertyKey++) {
            var location = locations[propertyKey];
            var latlngset = new google.maps.LatLng(location.lat, location.lon);
            searchMap.bounds.extend(latlngset);
            var iconImage = '<?php echo $w["line_color"];?>';
            if (location.membershipID in searchMap.membershipMarkers) {
                iconImage = searchMap.membershipMarkers[location.membershipID];
            } else {
                iconImage = searchMap.membershipMarkers[0];
            }
            var markerName = location.name;
            var marker = new MarkerWithLabel({
                position: latlngset,
                map: map,
                icon: iconImage,
                title: markerName,
                labelContent: markerName,
                labelAnchor: new google.maps.Point(-15, 45),
                labelClass: "profile-map-label",
                labelInBackground: false,
            });
            searchMap.currentMarkers[searchMap.currentMarkers.length] = marker;
            var content = searchMap.infoWindowTemplate;
            content = content.replace(/{property-picture}/g, location.pic)
                .replace(/{property-url}/g, location.url)
                .replace(/{property-name}/g, location.name)
                .replace(/{property-phone-hidden}/g, location.phone!=''?'':'hidden')
                .replace(/{property-phone-number}/g, location.phone);
            var infowindow = new google.maps.InfoWindow();
            google.maps.event.addListener(marker,'click', (function(marker,content,infowindow,location,propertyKey){
                return function() {
                    infowindow.setContent(content);
                    infowindow.open(map,marker);
                    if (typeof searchMap.currentInfoWindow !== 'undefined') {
                        searchMap.currentInfoWindow.close();
                    }
                    searchMap.setPropertyData(location);
                    searchMap.currentMap.panTo(searchMap.propertyLocation);
                    searchMap.currentInfoWindow = infowindow;
                    searchMap.currentPropertyKey = propertyKey;
                };
            })(marker,content,infowindow,location,propertyKey));
        }
        searchMap.currentMap.fitBounds(searchMap.bounds);
    },
    loadMarkers : function() {
        /* Load the markers */
        $('.google-pin-location').each(function () {
            var memberElement = $(this).prevAll('.member_results').eq(0);
            var membershipID = 0;
            var classes = memberElement.attr('class').split(' ');
            $.each(classes, function(i, name) {
                if (name.indexOf('level_') === 0) {
                    membershipID = name.substring('level_'.length);
                }
            });
            // Making the icon
            if ($(this).attr("data-name") != "") {
                searchMap.availableProperties[searchMap.availableProperties.length] = {
                    id: searchMap.availableProperties.length,
                    name: $(this).attr("data-name"),
                    url: $(this).attr("data-filename"),
                    lat: $(this).attr("data-lat"),
                    lon: $(this).attr("data-lng"),
                    pic: $(this).attr("data-photo"),
                    phone: $(this).attr("data-phone"),
                    membershipID: membershipID
                };
            }
        });
        searchMap.setMapMarkers(searchMap.currentMap, searchMap.availableProperties);
        if (searchMap.availableProperties.length) {
            searchMap.setPropertyData(searchMap.availableProperties[0]);
        }
    },
    toggleFullScreen : function() {
        if (!$(".search-results-map").hasClass("full-screen")) {
            $(".search-results-map").addClass("full-screen");
            $("body").css("overflow","hidden");
        } else {
            $(".search-results-map").removeClass("full-screen");
            $("body").css("overflow","");
        }
    },
    setPropertyData : function(property) {
        searchMap.currentProperty.id = property["id"];
        searchMap.currentProperty.name = property["name"];
        searchMap.currentProperty.url = property["url"];
        searchMap.currentProperty.lat = parseFloat(property["lat"]);
        searchMap.currentProperty.lon = parseFloat(property["lon"]);
        searchMap.currentProperty.pic = property["pic"];
        searchMap.currentProperty.phone = property["phone"];
        var propertyLocation = {
            lat: searchMap.currentProperty.lat,
            lng: searchMap.currentProperty.lon
        };
        searchMap.propertyLocation = propertyLocation;
    },
    setMapMarker : function(withPopup = true) {
        /* Clear old marker */
        if (searchMap.currentMarker) {
            searchMap.currentMarker.setMap(null);
            searchMap.currentInfoWindow.close();
        }
        var iconImage = '<?php echo $w["line_color"];?>';
        var markerName = searchMap.currentProperty.name;
        searchMap.currentMarker = new google.maps.Marker({
            position: searchMap.currentMap.getCenter(),
            map: searchMap.currentMap,
            icon: iconImage,
            title: markerName
        });
        if (withPopup) {
            var phonehtml = '';
            var windowTemplate = searchMap.infoWindowTemplate;
            windowTemplate = windowTemplate.replace(/{property-picture}/g, searchMap.currentProperty.pic)
                .replace(/{property-url}/g, searchMap.currentProperty.url)
                .replace(/{property-name}/g, searchMap.currentProperty.name)
                .replace(/{property-phone-hidden}/g, searchMap.currentProperty.phone!=''?'':'hidden')
                .replace(/{property-phone-number}/g, searchMap.currentProperty.phone)
                .replace(/{owner-url}/g, searchMap.currentProperty.ownerURL);
            searchMap.currentInfoWindow = new google.maps.InfoWindow({
                content: windowTemplate
            });
            searchMap.currentInfoWindow.open(searchMap.currentMap, searchMap.currentMarker);
        }
        searchMap.currentMarker.setMap(searchMap.currentMap);
    },
    setProperty : function(propertyData) {
        searchMap.setPropertyData(propertyData);
        searchMap.currentMap.panTo(searchMap.propertyLocation);
        searchMap.setMapMarker();
    },
    nextProperty : function(){
        if (searchMap.currentPropertyKey > -1) {
            searchMap.currentPropertyKey++;
            if (searchMap.currentPropertyKey in searchMap.availableProperties) {
                searchMap.setProperty(searchMap.availableProperties[searchMap.currentPropertyKey]);
            } else {
                searchMap.currentPropertyKey = 0;
                searchMap.setProperty(searchMap.availableProperties[searchMap.currentPropertyKey]);
            }
        } else {
            searchMap.currentPropertyKey = 0;
            searchMap.setProperty(searchMap.availableProperties[searchMap.currentPropertyKey]);
        }
    },
    previousProperty : function(){
        if (searchMap.currentPropertyKey > 0) {
            searchMap.currentPropertyKey--;
            if (searchMap.currentPropertyKey in searchMap.availableProperties) {
                searchMap.setProperty(searchMap.availableProperties[searchMap.currentPropertyKey]);
            } else {
                searchMap.currentPropertyKey = searchMap.availableProperties.length-1;
                searchMap.setProperty(searchMap.availableProperties[searchMap.currentPropertyKey]);
            }
        } else {
            searchMap.currentPropertyKey = searchMap.availableProperties.length-1;
            searchMap.setProperty(searchMap.availableProperties[searchMap.currentPropertyKey]);
        }
    },
    }
    $(document).ready(function(){
        searchMap.init();
    });
</script>