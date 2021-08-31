@extends('layouts.cms.master')

<?php
$title = "Provinsi";
switch (strtolower($title)) {
    case 'kingdom':
        $url = "cms/master/update-kingdom";
        $urlAjax = "ajax/kingdom";
        break;
    case 'filum':
        $url = "cms/master/update-phylum";
        $urlAjax = "ajax/phylum";
        break;
    case 'kelas':
        $url = "cms/master/update-class";
        $urlAjax = "ajax/class";
        break;
    case 'ordo':
        $url = "cms/master/update-ordo";
        $urlAjax = "ajax/ordo";
        break;
    case 'famili':
        $url = "cms/master/update-family";
        $urlAjax = "ajax/family";
        break;
    case 'genus':
        $url = "cms/master/update-genus";
        $urlAjax = "ajax/genus";
        break;
    case 'status konservasi':
        $url = "cms/master/update-conservation-status";
        $urlAjax = "ajax/conservation-status";
        break;
    case 'provinsi':
        $url = "cms/master/update-province";
        $urlAjax = "ajax/province";
        break;
    default:
        $url = strtolower($title);
        $urlAjax = "ajax/notfound";
        break;
}
?>

@section('title')
    Master - {{ $title }}
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
    <script>
        $(function () {
            $('#taxonomic').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "ajax": '{!! $urlAjax !!}',
                "columns": [
                    {data: 'name', name: 'name'},
                    {data: 'edit', name: 'edit'}
                ],
                "createdRow": function (row, data, dataIndex) {
                    $(row).children().last().css({'text-align': 'center'});
                }
            });
        });

        $(document).on('submit', '.create-taxonomic', function (e) {
            if (!confirm('Apakah data sudah benar?')) {
                e.preventDefault();
                return;
            }
        });

        $(document).on('click', '.btn-edit-taxonomic', function () {
            long = $(this).attr('taxonomic_long');
            lat = $(this).attr('taxonomic_lat')
            $('.uid').val($(this).attr('uid'));
            $('#latEdit').val(lat);
            $('#longEdit').val(long);
            $('.old_name').val($(this).attr('taxonomic_name'));
            $('#modal-edit-taxonomic').modal('show');

            coordinate = [];
            coordinate[0] = long;
            coordinate[1] = lat;


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
            console.log(coordinate);
            var feature = new ol.Feature(
                new ol.geom.Point(ol.proj.transform([long, lat], 'EPSG:4326', 'EPSG:3857'))
            );
            feature.setStyle(iconStyle);
            vectorSource.clear();
            vectorSource.addFeature(feature);
        });
    </script>
    <script type="text/javascript">
        var Markers =
            [
                {
                    lat: -2.5575866,
                    lng: 119.4184973,
                    marker: 'https://cdn.mapmarker.io/api/v1/font-awesome/v5/icon-stack?size=50&color=DC4C3F&icon=fa-star-solid'
                },
                {
                    lat: -1.5341211637034746,
                    lng: 127.73240175312499,
                    marker: 'http://cdn.mapmarker.io/api/v1/pin?text=P&size=50&hoffset=1'
                },
            ];

        var view = new ol.View({
            center: ol.proj.fromLonLat([120.64622011249998, -1.956897567914666]),
            // -1.9684722,119.7570783
            // -2.1825596,120.6887273
            // -2.0447346,122.3600873 // laut
            zoom: 6
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

        mapEdit.on('click', function (evt) {
            var longLat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
            var lat = longLat[1];
            var long = longLat[0];

            console.log(long, lat);
            $('#longEdit').val(long);
            $('#latEdit').val(lat);

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
        <div class="col-8 offset-2">
            <div id="accordion">
                <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                                Klik disini untuk tambah data baru
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne" class="panel-collapse collapse in">
                        <div class="card-body">
                            <div class="alert alert-warning alert-dismissible">
                                <h5><i class="icon fas fa-exclamation-triangle"></i> Perhatian!</h5>
                                Data yang sudah dibuat tidak dapat dihapus. <br>
                                Mohon pastikan data yang dimasukkan sudah benar.
                            </div>
                            <form role="form" class="create-taxonomic" method="POST">
                                {{ csrf_field() }}
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>{{ $title }}</label>
                                        <input type="text" class="form-control" name="taxonomic" placeholder="Ketik disini . . ." required>
                                    </div>
                                    <div class="form-group">
                                        <label>Pilih titik tengah provinsi di peta (Provinsi di kursor: <span class="current-province"></span>)</label>
                                        <input type="text" class="form-control" id="lat" name="lat" hidden>
                                        <input type="text" class="form-control" id="long" name="long" hidden>
                                        <div id="map" class="map"></div>
                                        <div id="popup" class="ol-popup">
                                            <a href="#" id="popup-closer" class="ol-popup-closer"></a>
                                            <div id="popup-content"></div>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" class="btn btn-block btn-primary">Tambah</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-8 offset-2">
            <div class="card">
                <div class="card-body">
                    <table id="taxonomic" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modal-edit-taxonomic">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah Data {{ $title }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" class="edit-taxonomic" method="POST" action={{ url($url) }}>
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group">
                                <label>{{ $title }}</label>
                                <input type="text" class="form-control old_name" name="taxonomic"
                                       placeholder="Ketik disini . . ." required>
                                <input type="hidden" class="uid" name="uid">
                            </div>
                            <div class="form-group">
                                <label>Pilih titik tengah provinsi di peta (Provinsi di kursor:
                                    <span class="current-provinceEdit"></span>)</label>
                                <input type="text" class="form-control" id="latEdit" name="lat" hidden>
                                <input type="text" class="form-control" id="longEdit" name="long" hidden>
                                <div id="mapEdit" class="map"></div>
                                <div id="popupEdit" class="ol-popup">
                                    <a href="#" id="popup-closerEdit" class="ol-popup-closer"></a>
                                    <div id="popup-contentEdit"></div>
                                </div>
                            </div>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-block btn-info">Ubah</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection