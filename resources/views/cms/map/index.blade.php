@extends('layouts.cms.master')

@section('title')
    Ubah Titik Tengah Peta
@endsection

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <!-- Openlayers -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.2.1/css/ol.css" type="text/css">
    <style>
        .map {
            width: 100%;
            height: 400px;
        }

        .map:-webkit-full-screen {
            height: 100%;
            margin: 0;
        }

        .map:-ms-fullscreen {
            height: 100%;
        }

        .map:fullscreen {
            height: 100%;
        }

        .ol-rotate {
            top: 3em;
        }
    </style>
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{ url('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Openlayers -->
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.2.1/build/ol.js"></script>
    <script type="text/javascript">
        var view = new ol.View({
            center: ol.proj.fromLonLat([{!! $long !!}, {!! $lat !!}]),
            zoom: {!! $zoom !!}
        });
        // https://codebeautify.org/jsonviewer
        var mapSource = new ol.source.Vector({
            url: '{!! url("geojson/indonesia.geojson") !!}',
            format: new ol.format.GeoJSON()
        });

        var map = new ol.Map({
            target: 'map',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ],
            view: view
        });

        var mapEdit = new ol.Map({
            target: 'mapEdit',
            layers: [
                new ol.layer.Tile({
                    source: new ol.source.OSM()
                })
            ],
            view: view
        });

        // Add Vector
        var vector = new ol.layer.Vector({
            source: mapSource
        });
        map.addLayer(vector);
        mapEdit.addLayer(vector);

        var features = [];

        var vectorSource = new ol.source.Vector({
            features: features
        });

        var vectorLayer = new ol.layer.Vector({
            source: vectorSource
        });
        map.addLayer(vectorLayer);
        mapEdit.addLayer(vectorLayer);


        // Popup
        var container = document.getElementById('popup');
        var content = document.getElementById('popup-content');
        var closer = document.getElementById('popup-closer');

        var overlay = new ol.Overlay({
            element: container,
            autoPan: true,
            autoPanAnimation: {
                duration: 250
            }
        });
        map.addOverlay(overlay);
        mapEdit.addOverlay(overlay);
        closer.onclick = function() {
            overlay.setPosition(undefined);
            closer.blur();
            return false;
        };

        // map on pointermove / hover
        map.on('pointermove', function (event) {
            if (map.hasFeatureAtPixel(event.pixel) === true) {
                var coordinate = event.coordinate;

                var feature = map.forEachFeatureAtPixel(event.pixel, function (feat, layer) {
                        return feat;
                    }
                );

                // console.log(feature);
                var state = feature.get('state');
                // var longLat = ol.proj.transform(event.coordinate, 'EPSG:3857', 'EPSG:4326');
                // // var lat = longLat[1];
                // // var long = longLat[0];
                // // content.innerHTML = '<div style="background: white; padding: 5px"><b>Provinsi: ' + state + '</div>';
                // // overlay.setPosition(coordinate);
                $('.current-province').text(state);
            } else {
                // overlay.setPosition(undefined);
                // closer.blur();
            }
        });

        map.on('click', function(evt) {
            var longLat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
            var lat = longLat[1];
            var long = longLat[0];

            console.log(long, lat);
            $('#long').val(long);
            $('#lat').val(lat);
            $('#zoom').val(view.getZoom());

            var iconStyle = new ol.style.Style({
                image: new ol.style.Icon(({
                    anchor: [0.5, 46],
                    anchorXUnits: 'fraction',
                    anchorYUnits: 'pixels',
                    size: [48, 48],
                    opacity: 1,
                    src: 'http://cdn.mapmarker.io/api/v1/pin?text=P&size=50&hoffset=1'
                }))
            });

            var feature = new ol.Feature(
                new ol.geom.Point(evt.coordinate)
            );
            feature.setStyle(iconStyle);
            vectorSource.clear();
            vectorSource.addFeature(feature);
            

            var coordinate = evt.coordinate;
            var feature = map.forEachFeatureAtPixel(evt.pixel, function (feat, layer) {
                    return feat;
                }
            );

            var state = feature.get('state');
            
            console.log("['name' => '" + state + "', 'latitude' => " + lat + ", 'longitude' => " + long + "],");
        });

        mapEdit.on('pointermove', function (event) {
            if (mapEdit.hasFeatureAtPixel(event.pixel) === true) {
                var coordinate = event.coordinate;

                var feature = mapEdit.forEachFeatureAtPixel(event.pixel, function (feat, layer) {
                        return feat;
                    }
                );

                // console.log(feature);
                var state = feature.get('state');
                // var longLat = ol.proj.transform(event.coordinate, 'EPSG:3857', 'EPSG:4326');
                // // var lat = longLat[1];
                // // var long = longLat[0];
                // // content.innerHTML = '<div style="background: white; padding: 5px"><b>Provinsi: ' + state + '</div>';
                // // overlay.setPosition(coordinate);
                $('.current-provinceEdit').text(state);
            } else {
                // overlay.setPosition(undefined);
                // closer.blur();
            }
        });

        function CenterMap(long, lat) {
            flyTo(ol.proj.transform([long, lat], 'EPSG:4326', 'EPSG:3857'))
        }


        function flyTo(location, done) {
            var duration = 2000;
            var zoom = view.getZoom();
            var parts = 2;
            var called = false;
            function callback(complete) {
                --parts;
                if (called) {
                    return;
                }
                if (parts === 0 || !complete) {
                    called = true;
                    // done(complete);
                }
            }
            view.animate({
                center: location,
                duration: duration
            }, callback);
            view.animate({
                zoom: zoom - 1,
                duration: duration / 2
            }, {
                zoom: zoom,
                duration: duration / 2
            }, callback);
        }
        var iconStyle = new ol.style.Style({
            image: new ol.style.Icon({
                anchor: [0.5, 100],
                anchorXUnits: 'fraction',
                anchorYUnits: 'pixels',
                opacity: 1.0,
                src: './_img/marker_.png'
            })
        });

        // add an empty iconFeature to the source of the layer
        var iconFeature = new ol.Feature();
        var iconSource = new ol.source.Vector({
            features: [iconFeature]
        });
        var iconLayer = new ol.layer.Vector({
            source: iconSource,
            style: iconStyle
        });
        map.addLayer(iconLayer);
        mapEdit.addLayer(iconLayer);

        $('.content').on('click', function (evt) {
            setTimeout(function () {
                map.updateSize();
                mapEdit.updateSize();
            }, 200);
        });
    </script>
@endsection

@section('content')
    @if(Session::has('success'))
        <div class="alert alert-success alert-dismissible alert-session">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Berhasil!</h5>
            {{ Session::get('success') }}
        </div>
    @endif
    @if(Session::has('error'))
        <div class="alert alert-danger alert-dismissible alert-session">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-ban"></i> Gagal!</h5>
            Penambahan data gagal dilakukan. <br>
            Detail: <span>{{ Session::get('error') }}</span>
        </div>
    @endif
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <form role="form" class="update-map" method="POST">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group">
                                <label>Pilih titik tengah & Zoom level</label>
                                <input type="text" class="form-control" id="lat" name="lat" hidden>
                                <input type="text" class="form-control" id="long" name="long" hidden>
                                <input type="text" class="form-control" id="zoom" name="zoom" hidden>
                                <div id="map" class="map"></div>
                                <div id="popup" class="ol-popup">
                                    <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                                    <div id="popup-content"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-block btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection