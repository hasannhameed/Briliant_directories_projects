<input id="pac-input" class="controls google-writen-location" type="text" placeholder="Enter your complete address. Example: 350 Fifth Avenue, New York, NY 10118" autocomplete="off"> 
<div id="map-canvas"></div>
<label>
    <input type="checkbox" class="copy-address-dragg">
    Update your address when dragging the marker
</label>

<input type="hidden" name="country_code" value="<?php echo $post[country_code]; ?>">
<input type="hidden" name="state_code" value="<?php echo $post[state_code]; ?>">
<input type="hidden" name="lat" value="<?php echo $post[lat]; ?>">
<input type="hidden" name="lon" value="<?php echo $post[lon]; ?>">