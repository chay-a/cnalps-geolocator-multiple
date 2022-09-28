<?php

/**
 * Plugin Name: cnalps-geo multiple
 */


/**
 * Minimalistic geolocator Shortcode
 *
 * @param array $atts Shortcode attributes. Default empty.
 * @return string Shortcode output.
 */
function cnalps_register_geolocate_multiple_shortcode($atts = [])
{
    // normalize attribute keys, lowercase
    $atts = array_change_key_case((array) $atts, CASE_LOWER);
    $zoom = $atts['zoom'];
    $id = $atts['id'];
    $src = $atts['src'];

    //https://mocki.io/v1/f550e470-a665-4cc3-bf0b-22ad935d56b1

    return "
    <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.8.0/leaflet.css\">
    <script src=\"https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.8.0/leaflet.js\"></script>

    <link rel=\"stylesheet\" href=\"/wp-content/plugins/cnalps-geolocator-multiple/Leaflet.markercluster-1.4.1/dist/MarkerCluster.Default.css\">
    <script src=\"/wp-content/plugins/cnalps-geolocator-multiple/Leaflet.markercluster-1.4.1/dist/leaflet.markercluster.js\"></script>

    <div id=\"cnalps-map-$id\" style=\"width: 300px; height: 300px;\"></div>
    <script>
        fetch('$src')
            .then(response => {
                return response.json()
            })
            .then(response => {

                let map$id = L.map('cnalps-map-$id');

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href=\"https://www.openstreetmap.org/copyright\">OpenStreetMap</a> contributors'
                }).addTo(map$id);

                let markersCoord = [];
                let markers = L.markerClusterGroup();
                response.forEach(marker => {
                    markers.addLayer(L.marker([marker.lat, marker.lon]).addTo(map$id)
                    .bindPopup(marker.title)
                    .openPopup());
                    markersCoord.push([marker.lat, marker.lon]);
                });

                map$id.addLayer(markers).fitBounds(markersCoord, {padding:[50, 50]});

            });

        
    </script>
    ";
}


add_shortcode('CNAlpsGeolocateMultiple', 'cnalps_register_geolocate_multiple_shortcode');
