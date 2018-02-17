<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\LocationInfo */

?>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-q2u3nMb2RbCDvn3jni7uYHm79u9banY&libraries=geometry"></script>

    <div class="location-info-view">

        <?php $form = ActiveForm::begin([
//        'action' => ['index'],
            'method' => 'get',
        ]); ?>

        <div class="row">
            <?= $form->field($model, 'device_id', ['options' => ['class' => 'col-lg-3']])->widget(Select2::className(), [
                'data' => \app\models\Device::getUserDevice(),
                'options' => [
                    'placeholder' => Yii::t('app', 'Select Device'),
                ]
            ])->label(false) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary', 'id' => 'search']) ?>
            </div>
        </div>

        <div class="alert alert-danger" id="device-selection"
             style="display: none;"><?= Yii::t('app', 'Please select a device!') ?></div>

        <?php ActiveForm::end(); ?>

        <div id="map_wrapper">
            <div id="mapCanvas" class="mapping"></div>
        </div>

    </div>

<?php
$deviceId = Html::getInputId($model, 'device_id');
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
var markers = [];
var infoWindowContent = [];
var icons = [];

// Load initialize function
// google.maps.event.addDomListener(window, 'load', initMap);

$('#search').on('click', function(e) {
    e.preventDefault();
    $('#device-selection').hide();
    var device = $('#$deviceId').val();
    if (device) {
        $.ajax({
            type: 'post',
            data: {device: device},
            url: "/location-info/details",
            success: function(data) {
                var res = $.parseJSON(data);
                $.each(res, function(index, value) {
                    markers[index] = [
                        '"' + index + '"',
                        value['latitude'],
                        value['longitude']
                    ];
                    icons[index] = value['icon'];
                    infoWindowContent[index] = [
                        '<div class="info_content">' +
                        '<p>speed: ' + value['speed'] + '</p>' +
                        '<p>course: ' + value['course'] + '</p>' +
                        '<p>time: ' + value['time'] + '</p>' +
                        '</div>'
                        ];
                });
                // google.maps.event.addDomListener(window, 'load', initMap);
                initMap();
            }
        });
    } else {
        $('#device-selection').show();
    }
});

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
        mapTypeControl: false,
        streetViewControl: false,
        // zoom: 5
        // center: new google.maps.LatLng(1.1, 1.19),
        // bounds: bounds
    };

    // Display a map on the web page
    map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);
    map.setTilt(50);
    // map.setZoom(5);

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
        // if (google.maps.geometry.poly.containsLocation(position, bermudaTriangle)) {
        //     icon = '/img/markers/green_MarkerA.png';
        // } else {
        //     icon = '/img/markers/red_MarkerA.png';
        // }
        // if (map.getBounds().contains(position)) {
        //     icon = '/img/markers/green_MarkerA.png';
        // } else {
        //     icon = '/img/markers/red_MarkerA.png';
        // }
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0],
            icon: icons[i]
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
        // this.setZoom(5);
        google.maps.event.removeListener(boundsListener);
    });

    //add marker by click
    // google.maps.event.addListener(map, 'click', function(event) {
    //    placeMarker(event.latLng);
    // });

    function placeMarker(location) {
        console.log(location.lat());
        console.log(location.lng());
        marker = new google.maps.Marker({
            position: location,
            map: map
        });
    }

    // bermudaTriangle.addListener('click', function (event) {
    //     // if (marker && marker.setPosition) {
    //     //   marker.setPosition(event.latLng);
    //     //   } else {
    //       marker = new google.maps.Marker({position: event.latLng,map:map});
    //         // }
    //     // infowindow.setContent("Hello, world.");
    //     // var anchor = new google.maps.MVCObject();
    //     // anchor.set("position", event.latLng);
    //     // infowindow.open(map, marker);
    // });

}
JS;

$this->registerJs($js, \yii\web\View::POS_END);
