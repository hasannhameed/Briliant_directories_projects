<?php
if (empty($_REQUEST['loadedGLocation'])) {
	$_REQUEST['loadedGLocation'] = 1;
if ($w['enable_new_search_engine'] == 1 || $w['enable_member_feature_search_engine'] == 1 || $w['enable_album_search_engine'] == 1) {
    $keyCode = "";
    $keySiteURL = str_replace("www.", "", $w['website_url']);

    if ($w['google_maps_api_key'] != "" && strstr($_SERVER['SERVER_NAME'], $keySiteURL)) {
        $keyCode = "&key=" . $w['google_maps_api_key'];
    }

    if ($w['enable_localized_search'] == 1) {
        $mainCountry = $w['country'];

    } else {
        $mainCountry = "";
    } ?>
    <script type="text/javascript">

        window.addEventListener('DOMContentLoaded', () => {
            let googleSuggestForm = document.querySelectorAll('.googleSuggest,.google-writen-location,input[name="lead_location"],.fill_location');
            if (typeof googleSuggestForm != "undefined") {
                for (let i = 0; i < googleSuggestForm.length; i++) {
                    let closestForm = googleSuggestForm[i].closest('form');
                    if(typeof closestForm != "undefined" && closestForm != null){
                        closestForm.addEventListener('focusin', googleMapOnFormClick, false);
                    }else{
                        googleSuggestForm[i].addEventListener('input', googleMapOnFormClick, false);
                    }

                    if(googleSuggestForm[i].className.indexOf('fill_location') != -1){
                        googleSuggestForm[i].addEventListener('click', googleMapOnFormClick, false);
                    }
                }
            }
        });

        function googleMapOnFormClick() {
            let googleSuggestForm = document.querySelectorAll('.googleSuggest,.google-writen-location,input[name="lead_location"],.fill_location');
            let scriptLoaded = new Promise( (resolve,rejection) => {
                resolve('already loaded');
            });
            let mapUri ="https://maps.googleapis.com/maps/api/js?v=3.exp&libraries=places&region=<?php echo $w['country'];?>&language=<?php echo brilliantDirectories::getWebsiteLanguage(); echo $keyCode; ?>";
            if (typeof googleSuggestForm != "undefined") {
                for (let i = 0; i < googleSuggestForm.length; i++) {
                    let closestForm = googleSuggestForm[i].closest('form');
                    if(typeof closestForm != "undefined" && closestForm != null){
                        closestForm.removeEventListener('focusin', googleMapOnFormClick, false);
                    }else{
                        googleSuggestForm[i].removeEventListener('input', googleMapOnFormClick, false);
                    }

                    if(googleSuggestForm[i].className.indexOf('fill_location') != -1){
                        googleSuggestForm[i].removeEventListener('click', googleMapOnFormClick, false);
                        setTimeout(function(){
                            googleSuggestForm[i].click();
                        },200);
                    }
                }
            }
            let loadMapScriptAsync = function (uri) {
                return new Promise((resolve, reject) => {
                    let script = document.createElement('script');
                    script.type = 'text/javascript';
                    script.src = uri;
                    script.async = true;
                    script.onload = function () {
                        initializeG(true);
                        resolve("ok");
                    };
                    let head = document.getElementsByTagName('head')[0];
                    head.appendChild(script);
                });
            }
            if(typeof google == "undefined" || !google.hasOwnProperty('maps')) {
                scriptLoaded = loadMapScriptAsync(mapUri);
            } else {
                if (typeof initialize !== 'undefined' && typeof initialize === 'function') {
                    initialize();
                }
                initializeG();
            }
            return scriptLoaded;
        }

        function initializeG(runFunctions = false) {

            var geocoder;
            var cachedSelectedOption = [];
            let mapExists = document.querySelectorAll('[id^="map"]');

            if (runFunctions) { //already ran in the websiteScripts.php
                //function that initializes the creation of the google map
                if (typeof setJsMap !== 'undefined' && typeof setJsMap === 'function') {
                    setJsMap();
                }
                if (typeof setJsMapOverview !== 'undefined' && typeof setJsMapOverview === 'function' && mapExists.length > 0) {
                    setJsMapOverview();
                }
                if (typeof setJsMapSidebar !== 'undefined' && typeof setJsMapSidebar === 'function' && mapExists.length > 0) {
                    setJsMapSidebar();
                }
            }

            //This piece of code will create an autosuggest for every input with the class "googleSuggest"
            var counter = 1;
            var inputsArray = [];
            $(".googleSuggest").each(function () {
                //get the id from the input to be the unique identifier for each autocomplete
                var inputId = $(this).attr("id");
                var inputElement = $(this);
                inputsArray[counter] = /** @type {HTMLInputElement} */(
                    document.getElementById('' + inputId));
                var localizedMainCoutry = '<?php echo $mainCountry; ?>';
                var inputOptions = {
                    fields: ['address_components', 'formatted_address', 'geometry', 'icon', 'name'],
                    types: ['geocode']
                }
                if (localizedMainCoutry != "") {
                    inputOptions.componentRestrictions = {country: localizedMainCoutry}
                }

                var autocomplete = new google.maps.places.Autocomplete(inputsArray[counter], inputOptions);
                google.maps.event.addListener(autocomplete, 'place_changed', function () {
                    cachedSelectedOption = [];
                    cachedSelectedOption.push(autocomplete.getPlace());
                    cachedSelectedOption[0]['typed_location'] = inputElement.val();

                    if (cachedSelectedOption[0]['place_id'] == undefined) {
                        cachedSelectedOption = [];
                        var emptyObj = {
                            'typed_location': ''
                        };
                        cachedSelectedOption.push(emptyObj);
                    }
                });
                //the code that prevents the submition when hit enter on a google location autocomplete item
                google.maps.event.addDomListener(inputsArray[counter], 'keydown', function (e) {

                    if (e.keyCode == 13) {
                        var pacCounter = 0;
                        //need to run a loop to check each pac container
                        $('.pac-container').each(function () {

                            if ($(this).css("display") != "none") {
                                pacCounter++;
                            }
                        });
                        if (pacCounter > 0) {
                            e.preventDefault();
                        }
                    }
                });
                counter++;
            });

            //triggers every time a form with the id website-search is trigered
            $('.website-search').submit(function (e) {
                var currentForm = $(this);
                var locationInput = $(this).find(".googleLocation");

                //checks if the location input is filled when submitting the search form
                if (locationInput.val() != "" && typeof locationInput.val() != 'undefined') {
                    e.preventDefault();
                    //this variable has the main country of the site
                    var countryRegion = "<?php echo $w['country']; ?>";
                    var localizedSearchSetting = "<?php echo $w['enable_localized_search']; ?>";
                    var form = currentForm;
                    var urlGET = form.serialize();
                    var formActionUrl = form.attr("action");
                    var locationValue = locationInput.val();

                    //if the setting localized search is equal to 1 then the region parameter will be sent with the main country of the site
                    if (localizedSearchSetting != 1) {
                        countryRegion = "";
                    }
                    if (cachedSelectedOption[0] == undefined) {
                        var emptyForm = {
                            'typed_location': ''
                        };
                        cachedSelectedOption.push(emptyForm);
                    }
                    if (locationValue != cachedSelectedOption[0]['typed_location']) {
                        geocoder = new google.maps.Geocoder();
                        geocoder.geocode({
                            'address': locationValue,
                            'region': countryRegion
                        }, function (results, status) {

                            //if the google response of the geocoding was successful it will use that info to build the url for the new search
                            if (status == google.maps.GeocoderStatus.OK) {
                                parseInfoToSearch(results, urlGET, formActionUrl);

                            } else {
                                var urlPath = formActionUrl;
                                var redirect = urlPath + "?" + urlGET;
                                //will redirect the page using the new url that has been constructed
                                window.location.href = redirect;
                            }
                        });

                    } else {
                        parseInfoToSearch(cachedSelectedOption, urlGET, formActionUrl);
                    }
                }
            });

            function parseInfoToSearch(results, urlGET, formActionUrl) {
                var parameters = {};
                var addressComponentsArray = [];
                if (results.length > 1) {
                    for (let i = 0; i < results.length; i++) {
                        if (results[i].types[0] === 'natural_feature' ||
                            results[i].types[0] === 'airport' ||
                            results[i].types[0] === 'point_of_interest' ||
                            results[i].types[0] === 'establishment' ||
                            results[i].types[0] === 'park') {
                            results.splice(i, 1);
                        }
                    }
                }
                var adComLength = results[0].address_components.length;

                //loop that will build the array with the address components and will get the short name of country and administrative area level 1
                for (var i = 0; i < adComLength; i++) {

                    if (results[0].address_components[i]['types'][0] == "country") {
                        parameters.country_sn = results[0].address_components[i]['short_name'];
                    }
                    if (results[0].address_components[i]['types'][0] == "administrative_area_level_1") {
                        parameters.adm_lvl_1_sn = results[0].address_components[i]['short_name'];
                    }
                    if (results[0].address_components[i]['types'][0] == "administrative_area_level_1") {
                        parameters.stateSearchLN = results[0].address_components[i]['long_name'];
                    }
                    if (results[0].address_components[i]['types'][0] == "administrative_area_level_2") {
                        parameters.county_sn = results[0].address_components[i]['short_name'];
                    }
                    if (results[0].address_components[i]['types'][0] == "locality" || results[0].address_components[i]['types'][0] == "colloquial_area") {
                        parameters.city = results[0].address_components[i]['long_name'];
                    }
                    if (results[0].address_components[i]['types'][0] == "postal_code" || results[0].address_components[i]['types'][0] == "postal_code_prefix") {
                        parameters.postal_code = results[0].address_components[i]['long_name'];
                    }
                }

                parameters.location_type = results[0].types[0];

                if (parameters.adm_lvl_1_sn != '') {
                    parameters.stateSearch = parameters.adm_lvl_1_sn;
                }
                if (parameters.country_sn == "GB") {
                    delete parameters.adm_lvl_1_sn;
                }

                //will check if the response had the bounds parameters
                //if it had it will add the south west and north east parameters to the new url
                if (results[0].geometry.hasOwnProperty('bounds') || results[0].geometry.hasOwnProperty('viewport')) {

                    if (results[0].geometry.hasOwnProperty('bounds')) {
                        var boundsResponse = results[0].geometry.bounds;

                    } else {
                        var boundsResponse = results[0].geometry.viewport;
                    }
                    parameters.swlat = boundsResponse.getSouthWest().lat();
                    parameters.nelat = boundsResponse.getNorthEast().lat();
                    parameters.swlng = boundsResponse.getSouthWest().lng();
                    parameters.nelng = boundsResponse.getNorthEast().lng();

                    //if there were not bounds parameters in the response it will send the parameter fsearch as radius so a radius search will be performed because of lack of info for this location
                } else {
                    parameters.fsearch = "radius";
                }
                var locationCenterResponse = results[0].geometry.location;
                parameters.lat = locationCenterResponse.lat();
                parameters.lng = locationCenterResponse.lng();
                parameters.faddress = results[0].formatted_address;
                parameters.place_id = results[0].place_id;
                var formatParameters = $.param(parameters);
                urlGET = urlGET + "&" + formatParameters;
                var urlPath = formActionUrl;
                var redirect = urlPath + "?" + urlGET;
                //will redirect the page using the new url that has been constructed
                window.location.href = redirect;
            }

            //check if a map has been loaded
            if ($('#map-canvas').length > 0) {
                var cssHideLogo = "<style>.pac-container:after {  /* Disclaimer: not needed to show 'powered by Google' if also a Google Map is shown */background-image: none !important;height: 0px;}</style>";
                $('body').append(cssHideLogo);
            } else {
                var cssHideLogo = "<style>.pac-container:after {  /* Disclaimer: not needed to show 'powered by Google' if also a Google Map is shown */background-image: block !important;height: 16px;}</style>";
                $('body').append(cssHideLogo);
            }

            if (geocodeVisitorsSetting == 1 && geocodingMethod === "IP") {
                populateSearchFields();
            }

            populateSearchFields();
            var geocodeVisitorsSetting = '<?php echo $w['geocode']; ?>';
            var geocodingMethod = '<?php echo $w['user_geocoding_method']; ?>';

            //check the advanced setting "geocode_visitor_default" if set to 1 will override the "location_value" values to the formatted desire address from the google reverse geocoding response
            function populateSearchFields() {
                var prePopulateLocationSetting = '<?php echo $w["geocode_visitor_default"]; ?>';
                var geolocationMethod = '<?php echo $w["user_geocoding_method"]; ?>';

                //if set to one will get the lat and lng to do reverse geocoding
                if (prePopulateLocationSetting == 1 && (geolocationMethod === "HTML5" || geolocationMethod === "IP") && (vlat !== '' && vlon !== '')) {
                    var visitorLatLng = new google.maps.LatLng(parseFloat(vlat), parseFloat(vlon));
                    var visitorGeocoder = new google.maps.Geocoder();
                    var formattedAddress = [];
                    var preFormattedStructure = {
                        "locality": "long_name",
                        "administrative_area_level_2": "long_name",
                        "administrative_area_level_1": "long_name",
                        "country": "long_name"
                    };
                    visitorGeocoder.geocode({'latLng': visitorLatLng}, function (results, status) {
                        //if the google response of the geocoding was successful it will use that info to build the url for the new search
                        if (status == google.maps.GeocoderStatus.OK) {
                            $.each(preFormattedStructure, function (findex, fvalue) {
                                $.each(results[0].address_components, function (rindex, rvalue) {
                                    if (rvalue.types[0] == findex) {
                                        formattedAddress.push(rvalue.long_name);
                                    }
                                });
                            });
                            $('.googleSuggest').each(function () {
                                if ($(this).val() == '') {
                                    if (formattedAddress.length > 0) {
                                        $(this).val(formattedAddress.join(', '));
                                        clearContent($(this));
                                    }
                                }
                            });
                        } else {
                            $('.googleSuggest').each(function () {
                                $(this).val('');
                            });
                        }
                    });
                }
            };
            var vlon = '<?php echo $_SESSION[vlon]; ?>';
            var vlat = '<?php echo $_SESSION[vlat]; ?>';

            function showError(error) {
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        $('.fill_location.clicked').popover({
                            content: 'Your Local browser settings have prevented location targeting',
                            container: 'body'
                        });
                        $('.fill_location.clicked').popover('toggle');
                        setTimeout(function () {
                            $('.fill_location.clicked').popover('hide');
                            $('.fill_location.clicked').removeClass('clicked');
                        }, 2000);
                        break;
                    case error.POSITION_UNAVAILABLE:
                        console.log("Location information is unavailable.");
                        break;
                    case error.TIMEOUT:
                        console.log("The request to get user location timed out.");
                        break;
                    case error.UNKNOWN_ERROR:
                        console.log("An unknown error occurred.");
                        break;
                }
            }

            if (navigator.geolocation) {
                if ($(".googleSuggest")[0]) {
                    $(document).on('click', '.fill_location', function () {
                        $(this).addClass('clicked');
                        var startPos;
                        navigator.geolocation.getCurrentPosition(function (position) {
                            startPos = position;
                            vlat = startPos.coords.latitude;
                            vlon = startPos.coords.longitude;
                            $.get("/api/data/html/get/data_widgets/widget_name", {
                                "vlat": vlat,
                                "vlon": vlon,
                                "name": "Website - Save Coordinates Session"
                            }).done(function (data) {
                            });
                            populateSearchFields();
                        }, showError);
                    })
                    window.onload = function () {

                    };
                }
            } else {
                console.log('Geolocation is not supported for this Browser/OS version yet.');
            }
        }
        function getUrlParameter(sParam) {
            var sPageURL = window.location.search.substring(1);
            var sURLVariables = sPageURL.split('&');

            for (var i = 0; i < sURLVariables.length; i++) {
                var sParameterName = sURLVariables[i].split('=');

                if (sParameterName[0] == sParam) {
                    return decodeURIComponent(sParameterName[1]);
                }
            }
        }
    </script>
    <?php
}
}?>