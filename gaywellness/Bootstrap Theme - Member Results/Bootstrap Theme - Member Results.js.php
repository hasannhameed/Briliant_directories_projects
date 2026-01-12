<script attribute='text/javascript'>
	var map;
	var members = {};
	function initializeMap() {
		map = new google.maps.Map(document.getElementById('map-bounds'), {
		center: {lat: 0, lng: 0},
		zoom: 8,
		disableDefaultUI: true,
		});
	}
	function toogleShapeBox(user_id,node){
		if($(node).is(':checked') == true){
			members['user_id_'+user_id].boundsShape.setMap(map);
		}else{
			members['user_id_'+user_id].boundsShape.setMap(null);
		}
	}

	function loadMarker(lat,lng,showInfoWindow,title){
		var contentString = '';
		var marker = new google.maps.Marker({
          position: {lat:lat,lng:lng},
          map: map,
          title: 'Bounds Box Objects'
        });
        
		if(showInfoWindow === true){
			
			contentString = '<table>';
			
			$.each(members,function(index,value){
				contentString += '<tr>';
				contentString += '<td><input type="checkbox" checked="true" onchange="toogleShapeBox('+value.user_id+',this)"><td>';
				contentString += '<td>'+value.name+'<td>';
				contentString += '</tr>';
			});

			contentString += '</table>';

			var infowindow = new google.maps.InfoWindow({
			  content: contentString
			});

			marker.addListener('click', function() {
			  infowindow.open(map, marker);
			});
			
		}else{
			marker.setIcon('https://maps.google.com/mapfiles/ms/icons/green-dot.png');
			var infowindow = new google.maps.InfoWindow({
			  content: title
			});

			marker.addListener('click', function() {
			  infowindow.open(map, marker);
			});
			
		}
	}

	function drawBounds(lat,lng,swlat,swlng,nelat,nelng,color,address,user_id,name){

		latlngs = [];

		latlngs.push({ lat: swlat, 	lng: swlng });//bottom left
		latlngs.push({ lat: swlat, 	lng: lng });//bottom center
		latlngs.push({ lat: swlat, 	lng: nelng });//bottom right
		latlngs.push({ lat: lat, 	lng: nelng });// center right
		latlngs.push({ lat: nelat, 	lng: nelng });//top right
		latlngs.push({ lat: nelat, 	lng: lng });//top center
		latlngs.push({ lat: nelat, 	lng: swlng });//top left
		latlngs.push({ lat: lat, 	lng: swlng });//center left


		members['user_id_'+user_id] = {
			boundsShape:new google.maps.Polygon({
				paths: latlngs,
				strokeColor: color,
				strokeOpacity: 0.8,
				strokeWeight: 3,
				fillColor: 'color',
				fillOpacity: 0.35
			}),
			name:name,
			user_id:user_id,
			color:color
		}

		members['user_id_'+user_id].boundsShape.setMap(map);
	}

    <?php
    if(checkIfMobile() && $dc['sidebar_position_mobile'] == 'top'){?>
    let sidebarPositionTop = document.querySelectorAll('.content_w_sidebar.member_results > div');
    if (typeof sidebarPositionTop != 'undefined' && sidebarPositionTop.length > 0) {
        let sidebarHTML = sidebarPositionTop[1];
        sidebarPositionTop[1].remove();
        sidebarPositionTop[0].insertAdjacentElement('beforebegin', sidebarHTML);
    }
    <?php }?>

</script>