   <!-- <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB6mxALAUM4NWz8vU_HLypco6Pi_HILUt8&libraries=places">    -->
	   <div style="display: none">
      <input
        id="pac-input"
        class="controls"
        type="text"
        placeholder="Enter a location"
      />
    </div>
    <div id="map"></div>
    <div id="infowindow-content">
      <span id="place-name" class="title"></span><br />
      <strong>Place ID:</strong> <span id="place-id"></span><br />
      <span id="place-address"></span>
    </div>

    <!-- 
     The `defer` attribute causes the callback to execute after the full HTML
     document has been parsed. For non-blocking uses, avoiding race conditions,
     and consistent behavior across browsers, consider loading using Promises
     with https://www.npmjs.com/package/@googlemaps/js-api-loader.
    -->
