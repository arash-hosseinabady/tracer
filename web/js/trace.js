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
var arrowImgUrl = '/img/arrow.png';
var i = 0;
var j = 0;
var ajaxRes;
var geoJson;
var bounds;
var coordinate;
var description;
var markerEl;

function loadMap() {
    map = null;
    $('#map_wrapper').prepend('<div id="map_canvas" class="mapping" style="width: 100%; height: 100%;"></div>');
    mapboxgl.accessToken = 'pk.eyJ1IjoiYS1ob3NzZWluYWJhZGkiLCJhIjoiY2ppYmU4aHdwMDFjMDNxdXB1dmptbndkMSJ9.plf7LGhARLDHQWd7JC7rng';
    map = new mapboxgl.Map({
        container: 'map_canvas',
        style: 'mapbox://styles/mapbox/streets-v10'
    });
}
if (firstDevice.length) {
    loadMap();

    prepareCoordinates(firstDevice);

    initMap();
}

function ajaxTrace() {
    device = $('#locationinfo-device_id').val();
    fromDate = $('#locationinfo-from_date').val();
    toDate = $('#locationinfo-to_date').val();
    speed = $('#locationinfo-speed').val();
    $('#alert-nodata').hide();
    if (device) {
        $('#map_canvas').remove();
        $('#load-spin').show();
        $.ajax({
            type: 'post',
            data: {device: device, fromDate: fromDate, toDate: toDate, speed: speed},
            url: "/location-info/trace",
            success: function (data) {
                ajaxRes = $.parseJSON(data);
                markers = [];
                icons = [];
                infoWindowContent = [];
                if (ajaxRes.length) {
                    prepareCoordinates(ajaxRes);
                    if (ajaxRes[ajaxRes.length - 1]['speed']) {
                        $('.panel-last-location').show();
                    }
                    $('#last-location-speed').html(ajaxRes[ajaxRes.length - 1]['speed']);
                    $('#last-location-course').html(ajaxRes[ajaxRes.length - 1]['course']);
                    $('#last-location-time').html(ajaxRes[ajaxRes.length - 1]['time']);
                    $('#last-location-address').html(ajaxRes[ajaxRes.length - 1]['address']);

                    loadMap();
                    initMap();
                } else {
                    $('#alert-nodata').show();
                }
                $('#load-spin').hide();
            }
        });
    }
}

function prepareCoordinates(pointList) {
    pointOnMap = [];
    routeMap = [];
    i = 0;
    j = 0;
    pointList.forEach(function (value, index) {
        if (index == 0 || (index + 1) == pointList.length) {
            pointOnMap[i] = {
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
            i += 1;
        }

        if (pointList[index + 1]) {
            routeMap[j] = {
                "type": "Feature",
                "geometry": {
                    "type": "LineString",
                    "coordinates": [
                        [value['longitude'], value['latitude']],
                        [pointList[index + 1]['longitude'], pointList[index + 1]['latitude']]
                    ]
                },
                "properties": {
                    "LTYPE": "MFG"
                }
            };
            j += 1;
        }

        coordinates[index] = [value['longitude'], value['latitude']];
    });
}

function initMap() {
    map.on('load', function () {
        geoJson = {
            "type": "FeatureCollection",
            "features": pointOnMap
        };

        bounds = coordinates.reduce(function (bounds, coord) {
            return bounds.extend(coord);
        }, new mapboxgl.LngLatBounds(coordinates[0], coordinates[0]));

        geoJson.features.forEach(function (marker) {
            // create a DOM element for the marker
            markerEl = document.createElement('div');
            markerEl.className = 'marker';
            markerEl.style.backgroundImage = 'url(' + marker.properties.iconImg + ')';
            markerEl.style.width = '20px';
            markerEl.style.height = '34px';

            markerEl.addEventListener('mouseover', function () {
                $('.mapboxgl-popup.mapboxgl-popup-anchor-bottom').hide();
                $('.mapboxgl-popup.mapboxgl-popup-anchor-top').hide();
                coordinate = marker.geometry.coordinates.slice();
                description = marker.properties.description;

                new mapboxgl.Popup()
                    .setLngLat(coordinate)
                    .setHTML(description)
                    .addTo(map);
            });

            markerEl.addEventListener('mouseenter', function () {
                markerEl.style.cursor = 'pointer';
            });

            // add marker to map
            new mapboxgl.Marker(markerEl)
                .setLngLat(marker.geometry.coordinates)
                .addTo(map);
        });

        map.addSource('mapDataSourceId', {
            type: "geojson",
            data: {
                "type": "FeatureCollection",
                "features": routeMap
            }
        });

        setLineLayer();

        map.fitBounds(bounds, {
            padding: 40
        });

        map.loadImage(arrowImgUrl, function (err, image) {
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

$('#search').on('click', function (e) {
    e.preventDefault();
    $('#device-selection').hide();
    if ($('#locationinfo-device_id').val()) {
        ajaxTrace();
    } else {
        $('#device-selection').show();
    }
});

function setLineLayer() {
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

    // map.addLayer({
    //     'id': 'location',
    //     'type': 'circle',
    //     'source': 'mapDataSourceId',
    //     'filter': ['==', $('type'), 'Point'],
    //     'paint': {
    //       'circle-color': 'green',
    //       'circle-radius': {
    //         'base': 1.5,
    //         'stops': [
    //           [1, 1],
    //           [6, 3],
    //           [10, 8],
    //           [22, 12]
    //         ]
    //       }
    //     }
    // });
}