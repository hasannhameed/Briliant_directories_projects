<script>
// Get a query param from the URL, e.g. q=for_a_walk
function gcGetQueryParam(name) {
    var query = window.location.search || "";
    var regex = new RegExp("[?&]" + name + "=([^&#]*)", "i");
    var match = regex.exec(query);
    if (!match) { return null; }
    return decodeURIComponent(match[1].replace(/\+/g, " "));
}

// Lookup table: q value -> label
var gcLookup = {
    // Open To
    "friends": "Friends",
    "dates": "Dates",
    "network_social": "Network / Social",
    "fun_more": "Fun & More",

    // How They Like to Meet
    "for_a_walk": "For a Walk or Hike",
    "coffee_drinks": "For Coffee or Drinks",
    "exercise_sports": "For Exercise / Sports",
    "massage_exchange": "For a Massage Exchange",
    "hike_bike": "For a Hike or Bike Ride",
    "class_event": "For a Class / Event",
    "networking": "For Networking",
    "support_mentorship": "For Support or Mentorship",
    "exploring_city": "For Exploring the City",
    "something_else": "For Something Else..."
};

(function () {
    // Read ?q= from URL
    var qValue = gcGetQueryParam("q");
    if (!qValue) { return; }

    // Use lookup or prettify unknown keys
    var label = gcLookup[qValue];
    if (!label) {
        label = qValue
            .replace(/_/g, " ")
            .replace(/\b\w/g, function (c) { return c.toUpperCase(); });
    }

    // Find the span and inject text
    var span = document.getElementById("gc-city-search");
    if (!span) { return; }

    // Overwrite the &nbsp; with " – Label"
    span.innerHTML = "&nbsp;– " + label;
})();

</script>
