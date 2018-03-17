<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\pdp\PersianDatePicker;
use app\models\Helper;

/* @var $this yii\web\View */
/* @var $model app\models\LocationInfo */
?>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB-q2u3nMb2RbCDvn3jni7uYHm79u9banY&libraries=geometry&libraries=places"></script>

    <div class="location-info-view">

        <?php $form = ActiveForm::begin([
            'method' => 'get',
        ]); ?>

        <div class="row">
            <?= $form->field($model, 'device_id', ['options' => ['class' => 'col-lg-3']])->widget(Select2::className(), [
                'data' => \app\models\Device::getUserDevice(),
                'options' => [
                    'placeholder' => Yii::t('app', 'Select Device'),
                    'value' => $firstDevice
                ]
            ])->label(false) ?>

            <?= $form->field($model, 'from_date', ['options' => ['class' => 'col-lg-2']])->widget(
                PersianDatePicker::className(), [
                'options' => [
                    'placeholder' => Yii::t('app', 'From Date'),
                ]
            ])->label(false) ?>

            <?= $form->field($model, 'to_date', ['options' => ['class' => 'col-lg-2']])->widget(
                PersianDatePicker::className(), [
                'options' => [
                    'placeholder' => Yii::t('app', 'To Date'),
                ]
            ])->label(false) ?>

            <?= $form->field($model, 'speed', ['options' => ['class' => 'col-lg-2']])->textInput([
                'type' => 'number',
                'min' => '0',
                'placeholder' => Yii::t('app', 'Speed'),
            ])->label(false) ?>

            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary', 'id' => 'search']) ?>
            </div>
        </div>

        <div class="alert alert-danger" id="device-selection"
             style="display: none;"><?= Yii::t('app', 'Please select a device!') ?></div>

        <div class="alert alert-danger" id="alert-nodata"
             style="display: none;"><?= Yii::t('app', 'No Data!') ?></div>

        <?php ActiveForm::end(); ?>

        <div class="row">
            <div class="col-lg-3">
            </div>
            <div class="col-lg-9">
                <div id="map_wrapper">
                    <div id="mapCanvas" class="mapping"></div>
                </div>
            </div>
        </div>

    </div>

<?php
$deviceId = Html::getInputId($model, 'device_id');
$speed = Html::getInputId($model, 'speed');
$fromDate = Html::getInputId($model, 'from_date');
$toDate = Html::getInputId($model, 'to_date');
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
var firstDevice;
var markers = [];
var infoWindowContent = [];
var icons = [];
var device;
var speed;
var fromDate;
var toDate;

firstDevice = $.parseJSON('$output');
if (firstDevice.length) {
    $.each(firstDevice, function(index, value) {
        markers[index] = [
            '"' + index + '"',
            value['latitude'],
            value['longitude']
        ];
        icons[index] = value['icon'];
        infoWindowContent[index] = [
            '<div class="info_content">' +
            '<br>' +
            '<p>speed: ' + value['speed'] + '</p>' +
            '<p>course: ' + value['course'] + '</p>' +
            '<p>time: ' + value['time'] + '</p>' +
            '</div>'
            ];
    });
    initMap();
}

$('#search').on('click', function(e) {
    e.preventDefault();
    $('#device-selection').hide();
    if ($('#$deviceId').val()) {
        ajaxTrace();
    } else {
        $('#device-selection').show();
    }
});

function ajaxTrace()
{
    device = $('#$deviceId').val();
    fromDate = $('#$fromDate').val();
    toDate = $('#$toDate').val();
    speed = $('#$speed').val();
    $('#alert-nodata').hide();
    if (device) {
        $.ajax({
            type: 'post',
            data: {device: device, fromDate: fromDate, toDate: toDate, speed: speed},
            url: "/location-info/trace",
            success: function(data) {
                var res = $.parseJSON(data);
                markers = [];
                icons = [];
                infoWindowContent = [];
                if (res.length) {
                    $.each(res, function(index, value) {
                        markers[index] = [
                            '"' + index + '"',
                            value['latitude'],
                            value['longitude']
                        ];
                        icons[index] = value['icon'];
                        infoWindowContent[index] = [
                            '<div class="info_content">' +
                            '<br>' +
                            '<p>speed: ' + value['speed'] + '</p>' +
                            '<p>course: ' + value['course'] + '</p>' +
                            '<p>time: ' + value['time'] + '</p>' +
                            '</div>'
                            ];
                    });
                    // google.maps.event.addDomListener(window, 'load', initMap);
                    initMap();
                } else {
                    $('#alert-nodata').show();
                }
            }
        });
    }
}

function initMap() {
    var map;
    var bounds = new google.maps.LatLngBounds();
    
    var mapOptions = {
        // mapTypeId: 'roadmap',
        mapTypeControl: true,
        mapTypeControlOptions: {
            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
        },
        streetViewControl: false
        // center: {lat: 40, lng: 41},
        // zoom: 15,
        // bounds: bounds
    };

    // Display a map on the web page
    map = new google.maps.Map(document.getElementById("mapCanvas"), mapOptions);
    map.setTilt(50);
    // map.setZoom(5);

    // Add multiple markers to map
    var infoWindow = new google.maps.InfoWindow(), marker, i;
    // var icon = '';

    var polyline = new google.maps.Polyline({
          // strokeColor: '#FF0000',
          // strokeOpacity: 1,
          // strokeWeight: 2,
          map: map,
          icons: [{
              icon: {
                  path: google.maps.SymbolPath.FORWARD_CLOSED_ARROW,
                  strokeColor:'#0000ff',
                  fillColor:'#0000ff',
                  fillOpacity:1
              },
              repeat:'50px',
              path:[]
          }]
    });
        
    var geocoder = new google.maps.Geocoder;
    var place_id;
    var placesService = new google.maps.places.PlacesService(map);

    // Place each marker on the map
    for( i = 0; i < markers.length; i++ ) {
        var position = new google.maps.LatLng(markers[i][1], markers[i][2]);
        bounds.extend(position);
        if (i == 0 || (i + 1) == markers.length) {
        marker = new google.maps.Marker({
            position: position,
            map: map,
            title: markers[i][0],
            icon: icons[i]
        });
        }
        polyline.getPath().push(position);
        
        geocoder.geocode({'location': position}, function(results, status) {
            if (status === google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    // var placesService = new google.maps.places.PlacesService(map);
                    placesService.getDetails({placeId: results[1].place_id},
                    function(results) {
                        place_id = results.formatted_address;
                    });
                }
            }
        });
        
        infoWindow.setContent(infoWindowContent[i][0] + '<br>' + place_id);
        // Add info window to marker
        google.maps.event.addListener(marker, 'click', (function(marker, i) {
            return function() {
                infoWindow.open(map, marker);
            }
        })(marker, i));
    }
    map.fitBounds(bounds);

    // Set zoom level
    var boundsListener = google.maps.event.addListener(map, 'idle', function(event) {
        map.setZoom(16);
        google.maps.event.removeListener(boundsListener);
    });
}
JS;

$this->registerJs($js, \yii\web\View::POS_END);
