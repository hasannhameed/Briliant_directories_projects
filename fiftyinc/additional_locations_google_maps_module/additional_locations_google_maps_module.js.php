<script>
    //the maping array to know what address component types are link to which inputs on the contact details form
    var inputsArray = {
        "country_code" : {
            "country" : "short_name"
        },
        "state_code" : {
            "administrative_area_level_1" : "short_name"
        },
        "city" : {
            "locality" : "long_name",
            "colloquial_area" : "long_name",
            "sublocality" : "long_name",
            "sublocality_level_1" : "long_name",
            "sublocality_level_2" : "long_name",
            "sublocality_level_3" : "long_name",
            "sublocality_level_4" : "long_name",
            "sublocality_level_5" : "long_name",
            "neighborhood" : "long_name",
            "political" : "long_name",
            "ward" : "long_name"            
        },
        "zip_code" : {
            "postal_code" : "short_name",
            "postal_prefix" : "short_name"
        },
        "address1" : {
            "first_grouping" : {
                "street_number" : "short_name",
                "route" : "short_name"
            },
            "route" : "short_name",
            "premise" : "long_name",
            "subpremise" : "long_name",
            "intersection" : "long_name",
            "park" : "long_name",
            "point_of_interest" : "long_name"
        },
        "country_ln" : {
            "country" : "long_name"
        },
        "state_ln" : {
            "administrative_area_level_1" : "long_name"
        }
    };
    //make an array with all the read only inputs based on their names
    var readOnlyInputs = [
        "country_code", 
        "state_code",
        "state_ln",
        "country_ln"
    ]
    //function that triggers those read only inputs
    $.each(readOnlyInputs, function(indexes, names){
        $('input[name="'+names+'"]').prop("readonly", true);
    });
    //important variables
    var map,
        autocomplete,
        geo = new google.maps.Geocoder(),
        uLat = "<?php echo $post[lat]; ?>",
        uLng = "<?php echo $post[lon]; ?>";

    //if uLat and uLong are empty load the map in the center of the world
    if (uLat == "" || uLng == "") {
        uLat = 0;
        uLng = 0;
    };
    var markerInitLatLng = new google.maps.LatLng(parseFloat(uLat), parseFloat(uLng)),
        marker =  new google.maps.Marker({
        draggable: true,
        position: markerInitLatLng,
        map: map,
        title: "Your current location"
    });
    //if no location saved zoom will be 2
    if (uLat == "" || uLng == "") {
        var mapZoom = 1;
    } else {
        var mapZoom = 16;
    };
    //function that initializes the Map
    function initialize() {
        map = new google.maps.Map(document.getElementById('map-canvas'), {
            zoom: mapZoom,
            center: {lat: parseFloat(uLat), lng: parseFloat(uLng)}
        });
        var input = /** @type {HTMLInputElement} */(
          document.getElementById('pac-input')),
            searchInput = $('#pac-input'),
        //the init of the search locations input
            autocomplete = new google.maps.places.Autocomplete(input);
        //bind the change of the autocomplete to the make search function
        google.maps.event.addListener(autocomplete, 'place_changed', makeSearch);
        //when hitting enter on the location search field prevent the submition of the form
        searchInput.keydown(function(e) { 
            
            if (e.keyCode == 13) { 
                e.preventDefault(); 
            }
        }); 
        //load marker on the map
        marker.setMap(map);
        //trigger the result when the marker has been dragged
        google.maps.event.addListener(marker, 'dragend', function (event) {
            uLat = marker.getPosition().lat();
            uLng = marker.getPosition().lng();
        });
    }//END initialize function
    //call the init map function the html content has been loaded
    google.maps.event.addDomListener(window, 'load', initialize);

    //this is the function that takes a google response and fills the input location fields basing itself in the maping array
    function fillAddressFields(results){
        var results = results;
        console.log(results);
        saveLocationParams = {};
        //loop through the maping array to get all the values from the inputs that need to be filled with the new information
        $.each(inputsArray, function(findex, fvalue){
            //clean each field before setting new info
            $('input[name="'+findex+'"]').val("");
            
            //loop that checks the second level of the array 
            $.each(fvalue, function(sindex, svalue){
                var flag = true;

                //check if svalue is an object
                if ($.type(svalue) == "object") {
                    var currentConcat = "",
                    //get length of the concat array
                        concatLength = Object.keys(svalue).length,
                        concatCounter = 0;

                    //loop on each element of the svalue elements for the address components concatenation
                    $.each(svalue, function(conkey, convalue){
                        
                        //run a loop on the address components for each svalue elements
                        $.each(results[0].address_components, function(inskey, insvalue){

                            //if the element of the svalue match, concat the result to the array currentConcat
                            if (insvalue.types[0] == conkey) {
                                currentConcat += insvalue[convalue]+" ";
                                concatCounter++;
                            };
                        });//END each on address components
                    });//END each on svalue for the address components concatenation

                    //if all the elements matched then this new location with the concatenation of address components will be the one used
                    if (concatCounter == concatLength) {
                        $('input[name="'+findex+'"]').val(currentConcat);
                        saveLocationParams[findex] = currentConcat;
                        flag = false;
                        return false;
                    }

                //if svalue is not an object the system will continue with the default computations
                } else {
                    //run a loop on the address components for the svalue being searched
                    $.each(results[0].address_components, function(key, value){

                        //if the svalue element matches it will be saved
                        if (value.types[0] == sindex) {
                            $('input[name="'+findex+'"]').val(value[svalue]);
                            saveLocationParams[findex] = value[svalue];
                            flag = false;
                            return false;
                        };
                    });//END each on the address components
                }
                return flag;
            });//END each on the second level of the array
        });//END each on the first level of the array
        console.log(saveLocationParams);
    }//END fillAddressFields function

    //function that triggers when someone selects an option from the autocomplete
    function makeSearch() {
        var locationValue = $('#pac-input').val();
        geo.geocode( { 'address': locationValue }, function(results, status) {

            if (status == google.maps.GeocoderStatus.OK) {
                //the system will move the marker to the center of the new choosen location
                marker.setPosition(results[0].geometry.location);
                map.panTo(results[0].geometry.location);
                map.setZoom(16);
                //update lat and lng with the new coordinates of the resulting location
                //update Lat
                $('input[name="lat"]').val(results[0].geometry.location.lat());
                //update Lng
                $('input[name="lon"]').val(results[0].geometry.location.lng());  
                $('input[name="place_id"]').val(results[0].place_id);  
                fillAddressFields(results);
                

            } else {
                alert(status);
            }
        });//END on the geo.geocode
    }//END make search function
    //capture the draggin marker event and update the lat and lng of the member
    google.maps.event.addListener(marker, 'dragend', function (event) {
        var markerCoordinates = marker.getPosition(),
            userLat = marker.getPosition().lat(),
            userLng = marker.getPosition().lng();
        //update Lat
        $('input[name="lat"]').val(userLat);
        //update Lng
        $('input[name="lon"]').val(userLng);

        //change the address fields if the checkbox for this functionality is checked 
        if ($(".copy-address-dragg").is(':checked')) {
            geo.geocode( { 'latLng': markerCoordinates }, function(results, status) {

                if (status == google.maps.GeocoderStatus.OK) {

                    $('input[name="place_id"]').val(results[0].place_id);  
                    fillAddressFields(results);

                } else {
                    alert(status);
                }
            });
        }
    });//END dragend event listener
    //function for add location link
    $('#sub_accounts_form').submit(function(e){
        saveLocation(saveLocationParams);
    });
    var saveLocation = function(geoResponse) { 
        console.log(geoResponse);
        $.ajax({
            url: '/api/data/html/get/data_widgets/widget_name',
            type: 'GET',
            dataType: 'json',
            data: {
                "name": 'website_save_location_system',
                "response" : geoResponse
            }
        })
        .done(function(data) {
            console.log("success");
            console.log(data);
        })
        .fail(function(data) {
            console.log("error");
            console.log(data);
        });
        
    }
</script>   