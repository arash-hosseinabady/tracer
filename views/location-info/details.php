<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LocationInfo */

?>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-q2u3nMb2RbCDvn3jni7uYHm79u9banY&libraries=geometry"></script>

<div class="location-info-view">

    <div id="map_wrapper">
        <div id="mapCanvas" class="mapping"></div>
    </div>

</div>

<?php
$css = <<< CSS
#map_wrapper {
    height: 600px;
}

#mapCanvas {
    width: 100%;
    height: 100%;
}
CSS;
$this->registerCss($css);

$js = <<< JS
function initMap() {
    var map;
    var triangleCoords = [
          {lat: 1.01, lng: 1.18},
          {lat: 1.003, lng: 1.18},
          {lat: 1, lng: 1.2},
          {lat: 1.01, lng: 1.2}
        ];
    var bounds = new google.maps.LatLngBounds(
        // new google.maps.LatLng(1.01, 1.18),
        // new google.maps.LatLng(1.003, 1.18),
        // new google.maps.LatLng(1, 1.2),
        // new google.maps.LatLng(1.01, 1.2)
    );

    var mapOptions = {
        mapTypeId: 'roadmap',
        // center: new google.maps.LatLng(1.1, 1.19),
        // bounds: bounds
    };

    // Display a map on the web page
    map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);
    map.setTilt(50);

    // var lastValidCenter = map.getCenter();

    // Multiple markers location, latitude, and longitude
    var markers = [
        ['A', 1.02, 1.19],
        ['B', 1.004, 1.199],
        // ['C', 1, 1.18],
        // ['D', 1.009, 1.18],
        // ['E', 1.009, 1.17],
    ];

    // Info window content
    var infoWindowContent = [
        ['<div class="info_content">' +
        '<h3>Brooklyn Museum</h3>' +
        '<p>The Brooklyn Museum is an art museum located in the New York City borough of Brooklyn.</p>' + '</div>'],
        ['<div class="info_content">' +
        '<h3>Brooklyn Public Library</h3>' +
        '<p>The Brooklyn Public Library (BPL) is the public library system of the borough of Brooklyn, in New York City.</p>' +
        '</div>'],
        ['<div class="info_content">' +
        '<h3>Prospect Park Zoo</h3>' +
        '<p>The Prospect Park Zoo is a 12-acre (4.9 ha) zoo located off Flatbush Avenue on the eastern side of Prospect Park, Brooklyn, New York City.</p>' +
        '</div>']
    ];

    // Add multiple markers to map
    var infoWindow = new google.maps.InfoWindow(), marker, i;
    var icon = '';

    var bermudaTriangle = new google.maps.Polygon({
          paths: triangleCoords,
          strokeColor: '#FF0000',
          strokeOpacity: 0.8,
          strokeWeight: 2,
          fillColor: '#ff0000',
          fillOpacity: 0.35
        });
        bermudaTriangle.setMap(map);

    // Place each marker on the map
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        if (google.maps.geometry.poly.containsLocation(position, bermudaTriangle)) {
            icon = '/img/markers/green_MarkerA.png';
        } else {
            icon = '/img/markers/red_MarkerA.png';
        }
        // if (map.getBounds().contains(position)) {
        //     icon = '/img/markers/green_MarkerA.png';
        // } else {
        //     icon = '/img/markers/red_MarkerA.png';
        // }
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0],
            //icon: icon
        });

        // Add info window to marker
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.setContent(infoWindowContent[i][0]);
                infoWindow.open(map, marker);
            }
        })(marker, i));

        // Center the map to fit all markers on the screen
        map.fitBounds(bounds);
    }

    // Set zoom level
    var boundsListener = google.maps.event.addListener((map), 'bounds_changed', function(event) {
        this.setZoom(14);
        google.maps.event.removeListener(boundsListener);
    });

    //add marker by click
    google.maps.event.addListener(map, 'click', function(event) {
       placeMarker(event.latLng);
    });

    function placeMarker(location) {
        marker = new google.maps.Marker({
            position: location,
            map: map
        });
    }

    bermudaTriangle.addListener('click', function (event) {
        if (marker && marker.setPosition) {
          marker.setPosition(event.latLng);
          } else {
          marker = new google.maps.Marker({position: event.latLng,map:map});
            }
        // infowindow.setContent("Hello, world.");
        // var anchor = new google.maps.MVCObject();
        // anchor.set("position", event.latLng);
        // infowindow.open(map, marker);
    });

}
// Load initialize function
google.maps.event.addDomListener(window, 'load', initMap);

JS;

$this->registerJs($js, \yii\web\View::POS_END);
