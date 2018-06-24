<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\widgets\Select2;
use app\components\pdp\PersianDatePicker;
use yii\web\View;
use kartik\spinner\Spinner;

/* @var $this yii\web\View */
/* @var $model app\models\LocationInfo */

$this->registerCssFile('/css/mapbox-gl.css');
$this->registerJsFile('/js/mapbox-gl.js', ['position' => View::POS_HEAD]);
?>

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
                <div class="panel panel-info panel-last-location"
                     style="display: <?= isset($lastLocationInfo['speed']) ? 'block' : 'none' ?>">
                    <div class="panel-heading"><?= Yii::t('app', 'Last location info') ?></div>
                    <div class="panel-body">
                        <p><?= Yii::t('app', 'Speed') ?> : <span
                                    id="last-location-speed"><?= isset($lastLocationInfo['speed']) ? $lastLocationInfo['speed'] : '' ?></span>
                        </p>
                        <p><?= Yii::t('app', 'Course') ?> : <span
                                    id="last-location-course"><?= isset($lastLocationInfo['course']) ? $lastLocationInfo['course'] : '' ?></span>
                        </p>
                        <p><?= Yii::t('app', 'Time') ?> : <span
                                    id="last-location-time"><?= isset($lastLocationInfo['time']) ? $lastLocationInfo['time'] : '' ?></span>
                        </p>
                        <p><?= Yii::t('app', 'Address') ?> : <span
                                    id="last-location-address"><?= isset($lastLocationInfo['address']) ? $lastLocationInfo['address'] : '' ?></span>
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-9">
                <div id="map_wrapper">
                    <div id="map_canvas" class="mapping"></div>
                    <div id="load-spin" class="well col-lg-12" style="height: 100%; display: none;direction: ltr">
                        <div class="col-lg-12" style="margin-top: 250px;"></div>
                        <?= Spinner::widget([
                            'preset' => Spinner::LARGE,
                            'color' => 'blue',
                            'align' => 'center',
                            'caption' => 'Loading map &hellip;',
                        ]) ?>
                        <div class="clearfix"></div>
                    </div>
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

#map_canvas {
    width: 100%;
    height: 100%;
}
CSS;
$this->registerCss($css);
?>

<script type="text/javascript">
var firstDevice;
var markers = [];
var infoWindowContent = [];
var icons = [];
var device;
var speed;
var fromDate;
var toDate;
var pointOnMap = [];
var routeMap = [];
var map;
var coordinates = [];

firstDevice = JSON.parse('<?= $firstDeviceInfo ?>');

function loadMap() {
    mapboxgl.accessToken = 'pk.eyJ1IjoiYS1ob3NzZWluYWJhZGkiLCJhIjoiY2ppYmU4aHdwMDFjMDNxdXB1dmptbndkMSJ9.plf7LGhARLDHQWd7JC7rng';
    map = new mapboxgl.Map({
        container: 'map_canvas',
        style: 'mapbox://styles/mapbox/streets-v10'
    });
}
if (firstDevice.length) {
    loadMap();
    
    var i = 0;
    prepareCoordinates(firstDevice);
    
    initMap();
}

function ajaxTrace()
{
    device = $('#<?= $deviceId ?>').val();
    fromDate = $('#<?= $fromDate ?>').val();
    toDate = $('#<?= $toDate ?>').val();
    speed = $('#<?= $speed ?>').val();
    $('#alert-nodata').hide();
    if (device) {
        $('#map_canvas').hide();
        $('#load-spin').show();
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
                    prepareCoordinates(res);
                    if (res[res.length - 1]['speed']) {
                        $('.panel-last-location').show();
                    }
                    $('#last-location-speed').html(res[res.length - 1]['speed']);
                    $('#last-location-course').html(res[res.length - 1]['course']);
                    $('#last-location-time').html(res[res.length - 1]['time']);
                    $('#last-location-address').html(res[res.length - 1]['address']);

                    loadMap();
                    initMap();
                } else {
                    $('#map_canvas').show();
                    $('#load-spin').hide();
                    $('#alert-nodata').show();
                }
            }
        });
    }
}

function prepareCoordinates(pointList) {
    pointList.forEach(function(value, index) {
        if (index == 0 || (index + 1) == firstDevice.length) {
            pointOnMap[index] = {
                "type": "Feature",
                "geometry": {
                    "type": "Point",
                    "coordinates": [value['longitude'], value['latitude']]
                },
                "properties": {
                    "description": '<div class="info_content">' +
                    '<br>' +
                    '<p>address: ' + value['address'] + '</p>' +
                    '<p>time: ' + value['created_at'] + '</p>' +
                    '</div>',
                    "icon": "car",
                    "device_id": value['id'],
                    "iconImg": value['icon']
                }
            };
        } else {
            routeMap[i] = {
                "type": "Feature",
                "geometry": {
                    "type": "LineString",
                    "coordinates": [
                        [pointList[index - 1]['longitude'], pointList[index - 1]['latitude']],
                        [value['longitude'], value['latitude']]
                    ]
                },
                "properties": {
                    "LTYPE": "MFG"
                }
            };
            i += 1;
        }


        coordinates[index] = [value['longitude'], value['latitude']];
    });
}

function initMap() {
    var geoJson = {
        "type": "FeatureCollection",
        "features": pointOnMap
    };
    
    geoJson.features.forEach(function (marker) {
        // create a DOM element for the marker
        var el = document.createElement('div');
        el.className = 'marker';
        el.style.backgroundImage = 'url(' + marker.properties.iconImg + ')';
        el.style.width = '20px';
        el.style.height = '34px';

        el.addEventListener('mouseover', function() {
            $('.mapboxgl-popup.mapboxgl-popup-anchor-bottom').hide();
            $('.mapboxgl-popup.mapboxgl-popup-anchor-top').hide();
            var coordinates = marker.geometry.coordinates.slice();
            var description = marker.properties.description;

            new mapboxgl.Popup()
                .setLngLat(coordinates)
                .setHTML(description)
                .addTo(map);
        });

        el.addEventListener('mouseenter', function() {
            el.style.cursor = 'pointer';
        });

        // add marker to map
        new mapboxgl.Marker(el)
            .setLngLat(marker.geometry.coordinates)
            .addTo(map);
    });
    
    var bounds = coordinates.reduce(function (bounds, coord) {
        return bounds.extend(coord);
    }, new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]));

    map.on('load', function () {
        map.addSource('mapDataSourceId', {
            type: "geojson",
            data: {
                "type": "FeatureCollection",
                "features": routeMap
            }
        });
        
        map.addLayer({
            'id': 'route',
            'type': 'line',
            'source': 'mapDataSourceId',
            'filter': ['==', '$type', 'LineString'],
            'layout': {
              'line-join': 'round',
              'line-cap': 'round'
            },
            'paint': {
              'line-color': '#3cb2d0',
              'line-width': {
                'base': 4,
                'stops': [
                  [1, 0.5],
                  [8, 3],
                  [15, 6],
                  [22, 8]
                ]
              }
            }
        });
        
//        map.addLayer({
//            'id': 'location',
//            'type': 'circle',
//            'source': 'mapDataSourceId',
//            'filter': ['==', '$type', 'Point'],
//            'paint': {
//              'circle-color': 'green',
//              'circle-radius': {
//                'base': 1.5,
//                'stops': [
//                  [1, 1],
//                  [6, 3],
//                  [10, 8],
//                  [22, 12]
//                ]
//              }
//            }
//        });

        map.fitBounds(bounds, {
            padding: 40
        });
        
        var url = '/img/arrow.png';
          map.loadImage(url, function(err, image) {
            if (err) {
              console.error('err image', err);
              return;
            }
            map.addImage('arrow', image);
            map.addLayer({
              'id': 'arrow-layer',
              'type': 'symbol',
              'source': 'mapDataSourceId',
              'layout': {
                'symbol-placement': 'line',
                'symbol-spacing': 1,
                'icon-allow-overlap': true,
                // 'icon-ignore-placement': true,
                'icon-image': 'arrow',
                'icon-size': 0.045,
                'visibility': 'visible'
              }
            });
          });
    });
}
</script>

<?php
$js = <<< JS
$('#search').on('click', function(e) {
    e.preventDefault();
    $('#device-selection').hide();
    if ($('#$deviceId').val()) {
        ajaxTrace();
    } else {
        $('#device-selection').show();
    }
});
JS;
$this->registerJs($js, View::POS_END);