@extends('layouts.cms.master')

@section('title')
    Overview Detail
@endsection

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ url('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <!-- Openlayers -->
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.2.1/css/ol.css" type="text/css"> --}}
    <link rel="stylesheet" href="{{ url('css/ol.css') }}" type="text/css">

    <!-- Bootstrap Fileinput -->
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-fileinput/bootstrap-fileinput.min.css') }}" type="text/css">

    <style>
        .process-step .btn:focus{outline:none}
        .process{display:table;width:100%;position:relative}
        .process-row{display:table-row}
        .process-step button[disabled]{opacity:1 !important;filter: alpha(opacity=100) !important}
        .process-row:before{top:40px;bottom:0;position:absolute;content:" ";width:100%;height:1px;background-color:#ccc;z-order:0}
        .process-step{display:table-cell;text-align:center;position:relative}
        .process-step p{margin-top:4px}
        .btn-circle{width:80px;height:80px;text-align:center;font-size:12px;border-radius:50%}

        .map {
            width: 100%;
            height:400px;
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

    <!-- Select2 -->
    <script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- Openlayers -->
    {{-- <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.2.1/build/ol.js"></script> --}}
    <script src={{url('js/ol.js')}}></script>

    <!-- Bootstrap Fileinput -->
    <script src="{{ url('plugins/bootstrap-fileinput/bootstrap-fileinput.min.js') }}"></script>
    <script src="{{ url('plugins/bootstrap-fileinput/theme.min.js') }}"></script>

    <script>
        var urlPlaceholder = "{{url('/dist/marker/')}}";

        $(".select2-provinces").select2({
            placeholder: "Pilih Provinsi"
        });

        $(document).on('click', '.btn-update-data-animal', function(e) {
            e.preventDefault();
            window.location.href = $(location).attr('href').replace(/.$/, $(this).attr('p'));
        });
    </script>
    <script type="text/javascript">
        var Markers = [];
        var Markers2 = [];
        var tempMarker = [];
        var indexCounter = 0;

        $(function() {
            var tr = $('.tbody-animal-mapping tr');

            if (tr.length > 0) {
                var lat = 0;
                var long = 0;
                $.each(tr, function() {
                    lat = $(this).children().first().next().next().text();
                    long = $(this).children().first().next().text();

                    Markers.push({
                        lat: lat, 
                        lng: long,
                        marker: urlPlaceholder+'/pin.png'
                    })
                    // marker: 'http://cdn.mapmarker.io/api/v1/pin?text=P&size=50&hoffset=1'

                });
            }

            var view = new ol.View({
                center: ol.proj.fromLonLat([122.3600873, -2.0447346]),
                zoom: 7
            });

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

            // Add Vector
            var vector = new ol.layer.Vector({
                source: mapSource
            });
            map.addLayer(vector);

            var features = [];

            var indexCounter = 1;
            for (var i = 0; i < Markers.length; i++) {
                var item = Markers[i];
                var longitude = item.lng;
                var latitude = item.lat;
                var marker = item.marker;

                var iconFeature = new ol.Feature({
                    geometry: new ol.geom.Point(ol.proj.transform([longitude, latitude], 'EPSG:4326', 'EPSG:3857'))
                });

                var iconStyle = new ol.style.Style({
                    image: new ol.style.Icon(({
                        anchor: [0.5, 1],
                        src: marker
                    }))
                });
                
                iconFeature.setStyle(iconStyle);
                iconFeature.setId(indexCounter);
                features.push(iconFeature);
                
                indexCounter++;
            }

            var vectorSource = new ol.source.Vector({
                features: features
            });

            var vectorLayer = new ol.layer.Vector({
                source: vectorSource
            });
            map.addLayer(vectorLayer);


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
                    var state = feature.get('state');
                    $('.current-province').text(state);
                }
            });

            
            map.on('click', function(evt) {
                if (map.hasFeatureAtPixel(evt.pixel) === true) {
                    var coordinate = evt.coordinate;

                    var feature = map.forEachFeatureAtPixel(evt.pixel, function (feat, layer) {
                            return feat;
                        }
                    );

                    var state = feature.get('state');

                    var longLat = ol.proj.transform(evt.coordinate, 'EPSG:3857', 'EPSG:4326');
                    var lat = longLat[1];
                    var long = longLat[0];

                    var iconStyle = new ol.style.Style({
                        image: new ol.style.Icon(({
                            anchor: [0.5, 46],
                            anchorXUnits: 'fraction',
                            anchorYUnits: 'pixels',
                            size: [48, 48],
                            opacity: 1,
                            src: urlPlaceholder+'/pin.png'
                        }))
                    });
                    // src: 'http://cdn.mapmarker.io/api/v1/pin?text=P&size=50&hoffset=1'

                    var feature = new ol.Feature(
                        new ol.geom.Point(evt.coordinate)
                    );
                    feature.setStyle(iconStyle);
                    feature.setId(indexCounter);

                    var listProvince = [];
                    if ($('.option-province').length > 0) {
                        $.each($('.option-province'), function(k, v) {
                            listProvince.push(v.value.toLowerCase());
                        });
                    }

                    if($.inArray(state.toLowerCase(), listProvince) !== -1) {
                        addMarkerAndTable(vectorSource, state, long, lat)
                    }
                }
            });

            function addMarkerAndTable(vectorSource, state, long, lat) {
                var isError = false;
                
                $.each($('.tr-animal-mapping'), function() {
                    if (
                        $(this).children().first().text() == state &&
                        $(this).children().first().next().text() == long && 
                        $(this).children().first().next().next().text() == lat
                    ) {
                        isError = true;
                    }
                });

                if (isError) {
                    alert('Provinsi: ' + state + ', Lat: ' + lat + ', Long: ' + long + ' sudah pernah ditambahkan sebelumnya!');
                    return ;
                }

                var iconStyle = new ol.style.Style({
                    image: new ol.style.Icon(({
                        anchor: [0.5, 46],
                        anchorXUnits: 'fraction',
                        anchorYUnits: 'pixels',
                        size: [48, 48],
                        opacity: 1,
                        src: urlPlaceholder+'/pin.png'
                    }))
                });
                // src: 'http://cdn.mapmarker.io/api/v1/pin?text=P&size=50&hoffset=1'

                var feature = new ol.Feature(
                    new ol.geom.Point(ol.proj.transform([long, lat], 'EPSG:4326', 'EPSG:3857'))
                );
                feature.setStyle(iconStyle);
                feature.setId(indexCounter);

                vectorSource.addFeature(feature);

                var html = '<tr class="tr-animal-mapping">';
                html += '<td>' + state + '</td>';
                html += '<td>' + long + '</td>';
                html += '<td>' + lat + '</td>';
                html += '<td class="text-center" style="cursor: pointer"><i class="fas fa-trash remove-marker-animal-mapping" fid="' + indexCounter + '" long="'+ long +'" lat="' + lat + '"></i></td>';
                html += '</tr>';
                $('.tbody-animal-mapping').append(html);

                indexCounter++;
                tempMarker.push([long, lat]);

                addToHiddenProvince();
            }

            function addToHiddenProvince() {
                var provinces = [];
                $.each($('.tbody-animal-mapping tr'), function(item) {

                    provinces.push(
                        {
                            'name' : $(this).children().first().text(),
                            'long' : $(this).children().first().next().text(),
                            'lat' : $(this).children().first().next().next().text()
                        }
                    );
                });
                $('.list-province').val(JSON.stringify(provinces));
            }

            function CenterMap(long, lat, duration) {
                flyTo(ol.proj.transform([long, lat], 'EPSG:4326', 'EPSG:3857'), duration)
            }

            function flyTo(location, duration) {
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
                        view.animate({
                            zoom: 8,
                            duration: duration / 2
                        });
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
                style : iconStyle
            });
            map.addLayer(iconLayer);        

            $(document).on('change', '.select2-provinces', function() {
                var lat = $('.select2-provinces').find(':selected').data('lat');
                var long = $('.select2-provinces').find(':selected').data('long');
                CenterMap(long, lat, 2000);
            });

            var tempHoverID = null;
            $(document).on('mouseenter', '.remove-marker-animal-mapping', function() {
                var long = $(this).attr('long');
                var lat = $(this).attr('lat');
                CenterMap(long, lat, 0);

                tempHoverID = $(this).attr('fid');

                var iconStyle = new ol.style.Style({
                    image: new ol.style.Icon(({
                        anchor: [0.5, 46],
                        anchorXUnits: 'fraction',
                        anchorYUnits: 'pixels',
                        size: [48, 48],
                        opacity: 1,
                        src: urlPlaceholder+'/gray-pin.png'
                    }))
                });
                feature = vectorSource.getFeatureById($(this).attr('fid'));
                feature.setStyle(iconStyle);
            });

            $(document).on('mouseleave', '.remove-marker-animal-mapping', function() {
                var iconStyle = new ol.style.Style({
                    image: new ol.style.Icon(({
                        anchor: [0.5, 46],
                        anchorXUnits: 'fraction',
                        anchorYUnits: 'pixels',
                        size: [48, 48],
                        opacity: 1,
                        src: urlPlaceholder+'/pin.png'
                    }))
                });
                feature = vectorSource.getFeatureById($(this).attr('fid'));
                feature.setStyle(iconStyle);

                tempHoverID = null;
            });

            $(document).on('click', '.remove-marker-animal-mapping', function() {
                // Removing Feature
                feature = vectorSource.getFeatureById($(this).attr('fid'));
                vectorSource.removeFeature(feature);
                $(this).parent().parent().remove();

                tempHoverID = null;
                addToHiddenProvince();
            });

            $(document).on('change', '.local-name', function() {
                $('.current-local-name').text($(this).val());
            });

            $(document).on('click', '.class-next-tab', function(e) {
                e.preventDefault();
                $('.update-animal').submit();
            });

            $(document).on('submit', '.update-animal', function() {
                // var imageContributors = $('.input-image-contributors');
                // var videoContributors = $('.input-video-contributors');

                // var dataImageContributors = [];
                // if (imageContributors.length > 0) {
                //     $.each(imageContributors, function() {
                //         dataImageContributors.push($(this).val());
                //     });
                // }

                // var dataVideoContributors = [];
                // if (imageContributors.length > 0) {
                //     $.each(videoContributors, function() {
                //         dataVideoContributors.push($(this).val());
                //     });
                // }

                // $('#list-image-contributor').val(dataImageContributors);
                // $('#list-video-contributor').val(dataVideoContributors);
            });

            // Manual Input Lat Long
            $(document).on('click', '.btn-manual-input-latlong', function(e) {
                e.preventDefault();
                var currentProvince = $('.select2-provinces').val();
                var currentLat = $('.input-lat').val();
                var currentLong = $('.input-long').val();

                if (!currentProvince) {
                    alert('Silahkan pilih Provinsi terlebih dahulu!');
                    return;
                }

                if (!currentLat) {
                    alert('Silahkan masukkan Latitude terlebih dahulu!');
                    return;
                }

                if (!currentLong) {
                    alert('Silahkan masukkan Longitude terlebih dahulu!');
                    return;
                }

                addMarkerAndTable(vectorSource, currentProvince, currentLong, currentLat);

                // Clear Input
                $('.input-lat').val('');
                $('.input-long').val('');
            });

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
                    <div class="tab-content">
                        @include('cms.overview.update.tabs.2')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection