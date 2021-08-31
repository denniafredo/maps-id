@extends('layouts.cms.master')

@section('title')
    Tambah Data
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

        $(function () {
            $('#example1').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true
            });
        });

        $(".select2-unit").select2({
            tags: true,
            placeholder: "Pilih Unit"
        });

        $(".select2-kingdom").select2({
            tags: true,
            placeholder: "Pilih Kingdom"
        });

        $(".select2-phylum").select2({
            tags: true,
            placeholder: "Pilih Filum"
        });

        $(".select2-class").select2({
            tags: true,
            placeholder: "Pilih Kelas"
        });

        $(".select2-ordo").select2({
            tags: true,
            placeholder: "Pilih Ordo"
        });

        $(".select2-family").select2({
            tags: true,
            placeholder: "Pilih Famili"
        });

        $(".select2-genus").select2({
            tags: true,
            placeholder: "Pilih Genus"
        });

        $(".select2-conservation-status").select2({
            tags: true,
            placeholder: "Pilih Status Konservasi"
        });

        $(".select2-cites-status").select2({
            placeholder: "Pilih Status CITES"
        });

        $(".select2-redlist-status").select2({
            placeholder: "Pilih Status Redlist"
        });

        $(".select2-provinces").select2({
            placeholder: "Pilih Provinsi"
        });

        $("#input-images").fileinput({
            theme: 'fas',
            allowedFileExtensions: ['jpg', 'jpeg', 'png'],
            showUpload: false
        });

        $("#input-videos").fileinput({
            theme: 'fas',
            showUpload: false
        });

        var counterImage = 1;
        $(document).on('change', '#input-images', function(e) {
            $('.image-contributors').empty();
            var html = '';
            html += '<div class="card-header">';
            html += '<h4 class="card-title" style="font-weight: bold;">';
            html += '<span>Kontributor Foto Satwa</span>';
            html += '</h4>';
            html += '<div class="card-body image-contributor-card-body" style="margin-top: 25px">';
            html += '</div>';
            html += '</div>';
            $('.image-contributors').append(html);
        });

        $(document).on('fileloaded', '#input-images', function(e) {
            var htmlDetail = '';
            htmlDetail += '<div class="form-group row">';
            htmlDetail += '<label style="padding-top: 5px;">Kontributor Foto ' + counterImage +'</label>';
            htmlDetail += '<div class="col-sm-10">';
            htmlDetail += '<input type="text" class="form-control input-image-contributors" placeholder="Ketik disini . . .">';
            htmlDetail += '</div>';
            htmlDetail += '</div>';
            $('.image-contributor-card-body').append(htmlDetail);

            counterImage++;
        });

        var counterVideo = 1;
        $(document).on('change', '#input-videos', function(e) {
            $('.video-contributors').empty();
            var html = '';
            html += '<div class="card-header">';
            html += '<h4 class="card-title" style="font-weight: bold;">';
            html += '<span>Kontributor Foto video</span>';
            html += '</h4>';
            html += '<div class="card-body video-contributor-card-body" style="margin-top: 25px">';
            html += '</div>';
            html += '</div>';
            $('.video-contributors').append(html);
        });

        $(document).on('fileloaded', '#input-videos', function(e) {
            var htmlDetail = '';
            htmlDetail += '<div class="form-group row">';
            htmlDetail += '<label style="padding-top: 5px;">Kontributor Video ' + counterVideo +'</label>';
            htmlDetail += '<div class="col-sm-10">';
            htmlDetail += '<input type="text" class="form-control input-video-contributors" placeholder="Ketik disini . . .">';
            htmlDetail += '</div>';
            htmlDetail += '</div>';
            $('.video-contributor-card-body').append(htmlDetail);

            counterVideo++;
        });

        $(document).on('click', '.fileinput-remove-button', function(e) {
            var forWhat = $(this).parent().parent().parent().parent().attr('for');

            if (forWhat == 'image') {
                $('.image-contributors').empty();
                $('#list-image-contributor').val([]);
            } else if (forWhat == 'video') {
                $('.video-contributors').empty();
                $('#list-video-contributor').val([]);
            } else {
                $('.image-contributors').empty();
                $('.video-contributors').empty();
            }
        });

        $(document).on('keyup', '.latin-name', function() {
            var currentVal = $(this).val();

            if (currentVal) {
                currentVal = currentVal.toLowerCase();
                currentVal = currentVal.charAt(0).toUpperCase() + currentVal.slice(1);
            } 

            $(this).val(currentVal)
        });

        $(".body-length-min, .body-length-max").keydown(function (e) {
            // Allow: backspace, delete, tab, escape, enter and .
            if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl/cmd+A
                (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: Ctrl/cmd+C
                (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: Ctrl/cmd+X
                (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                // let it happen, don't do anything
                return;
            }
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
    </script>
    <script>
        // Dev Only
        // $(function() {
        //     var now = new Date();
        //     $('.local-name').val('local ' + now.getHours() + now.getMinutes() + now.getSeconds());
        //     $('.latin-name').val('latin');
        //     $('.label-name').val('label');
        //     $('.description').val('description');
        //     $('.body-length-min').val(1);
        //     $('.body-length-max').val(100);
        //     $('.body-length-max').val(999);
        //     $('.weight').val(50);
        //     $('.height').val(50);
        //     $('.select2-unit').select2().select2('val', '1');
        //     $('.habitat').val('habitat');
        // });
        $(document).on('click', '.btn-circle', function(e) {
            var prev = $('.tab-menu.active').attr('c');
            var nextTab = $(this).attr('c');
            
            if (checkRequiredField(prev)) {
                var targetMenu = '#menu' + nextTab;

                // Add color to circle
                $('.btn-circle.btn-info').removeClass('btn-info').addClass('btn-default');
                $(this).addClass('btn-info').removeClass('btn-default').blur();

                // Clear all show + active
                $('.tab-panel').removeClass('show').removeClass('active');

                // Add show + active to target
                $(targetMenu).addClass('show').addClass('active');
            } else {
                e.preventDefault();
            }
        });

        $(document).on('click', '.class-next-tab', function(e) {
            var prev = $('.tab-menu.active').attr('c');
            var nextTab = $(this).attr('nt');

            if (checkRequiredField(prev)) {
                if (nextTab != 'end') {
                    var targetMenu = '#menu' + nextTab;
                    var nextBtnCircle = '.btn-circle-' + nextTab;

                    // Remove color and set to default
                    $('.btn-circle.btn-info').removeClass('btn-info').addClass('btn-default');

                    // Add color to circle
                    $(nextBtnCircle).addClass('btn-info').removeClass('btn-default').blur();

                    // Clear all show + active
                    $('.tab-panel').removeClass('show').removeClass('active');

                    // Add show + active to target
                    $(targetMenu).addClass('show').addClass('active');
                } else {
                    $('.create-animal').submit();
                }
            } else {
                e.preventDefault();
            }
        });

        function checkRequiredField(currentTab) {
            switch (currentTab) {
                case '1':
                    if(!$('.local-name').val()) {
                        alert('Nama Lokal harus diisi!');
                        return false;
                    } else {
                        if ($('.local-name').val().length > 100) {
                            alert('Nama Lokal maksimal 100 karakter');
                            return false;
                        }
                    }
                    if(!$('.latin-name').val()) {
                        alert('Nama Latin harus diisi!');
                        return false;
                    } else {
                        if ($('.latin-name').val().length > 100) {
                            alert('Nama Latin maksimal 100 karakter');
                            return false;
                        }
                    }
                    if(!$('.label-name').val()) {
                        alert('Label harus diisi!');
                        return false;
                    } else {
                        if ($('.label-name').val().length > 20) {
                            alert('Label maksimal 20 karakter');
                            return false;
                        }
                    }
                    if(!$('.description').val()) {
                        alert('Deskripsi harus diisi!');
                        return false;
                    }
                    if(!$('.body-length-min').val()) {
                        alert('Minimal Panjang Badan harus diisi!');
                        return false;
                    }
                    if(!$('.body-length-max').val()) {
                        alert('Maksimal Panjang Badan harus diisi!');
                        return false;
                    }
                    if(!$('.weight').val()) {
                        alert('Berat Badan harus diisi!');
                        return false;
                    }
                    if(!$('.height').val()) {
                        alert('Tinggi Satwa harus diisi!');
                        return false;
                    }
                    if(!$('.select2-unit').val()) {
                        alert('Unit harus diisi!');
                        return false;
                    }
                    if(!$('.habitat').val()) {
                        alert('Habitat harus diisi!');
                        return false;
                    }
                    return true;
                default:
                    return true;
            }
        }

        $(document).on('submit', '.create-animal', function(e) {
            var imageContributors = $('.input-image-contributors');
            var videoContributors = $('.input-video-contributors');

            var dataImageContributors = [];
            if (imageContributors.length > 0) {
                $.each(imageContributors, function() {
                    dataImageContributors.push($(this).val());
                });
            }

            var dataVideoContributors = [];
            if (imageContributors.length > 0) {
                $.each(videoContributors, function() {
                    dataVideoContributors.push($(this).val());
                });
            }

            $('#list-image-contributor').val(dataImageContributors);
            $('#list-video-contributor').val(dataVideoContributors);
        });
        $('.content').on('click', function (evt) {
            setTimeout(function () {
                map.updateSize();
            }, 200);
        });
    </script>
    <script type="text/javascript">
        var tempMarker = [];
        var indexCounter = 0;

        var view = new ol.View({
            center: ol.proj.fromLonLat([{!! $long !!}, {!! $lat !!}]),
            zoom: {!! $zoom !!}
        });

        var mapSource = new ol.source.Vector({
            // url: 'https://raw.githubusercontent.com/superpikar/indonesia-geojson/master/indonesia.geojson',
            url: 'https://bitbucket.org/bimahp/geojson/raw/1956530d5e74b60a75d78566612076c64acd27b2/id-province.geojson',
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

        // for (var i = 0; i < Markers.length; i++) {
        //     var item = Markers[i];
        //     var longitude = item.lng;
        //     var latitude = item.lat;
        //     var marker = item.marker;
        //
        //     var iconFeature = new ol.Feature({
        //         geometry: new ol.geom.Point(ol.proj.transform([longitude, latitude], 'EPSG:4326', 'EPSG:3857'))
        //     });
        //
        //     var iconStyle = new ol.style.Style({
        //         image: new ol.style.Icon(({
        //             anchor: [0.5, 1],
        //             src: marker
        //         }))
        //     });
        //
        //     iconFeature.setStyle(iconStyle);
        //     features.push(iconFeature);
        //
        // }

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

                var listProvince = [];
                if ($('.option-province').length > 0) {
                    $.each($('.option-province'), function(k, v) {
                        listProvince.push(v.value.toLowerCase());
                    });
                }

                if($.inArray(state.toLowerCase(), listProvince) !== -1) {
                    addMarkerAndTable(vectorSource, state, long, lat);
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

        // Test Click
        $(document).on('click', '.tm', function() {
            CenterMap(109.77144811378561, -7.278322534278615, 2000);
        });

        function CenterMap(long, lat, duration) {
            // console.log("Long: " + long + " Lat: " + lat);
            // map.getView().setCenter(ol.proj.transform([long, lat], 'EPSG:4326', 'EPSG:3857'));
            // map.getView().setZoom(5);

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

        var geolocation = new ol.Geolocation({
            projection: map.getView().getProjection(),
            tracking: true,
            trackingOptions: {
                enableHighAccuracy: true,
                maximumAge: 2000
            }
        });

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
            $('.current-province').text($(this).val());

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
            // src: 'https://cdn.mapmarker.io/api/v1/font-awesome/v5/pin?text=P&size=50&background=AAAAAA&color=FFF&hoffset=1'
            
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
            // src: 'http://cdn.mapmarker.io/api/v1/pin?text=P&size=50&hoffset=1'

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
                    <div class="process">
                        <div class="process-row nav nav-tabs" role="tablist">
                            <div class="process-step" role="tab">
                                <button type="button" class="btn btn-info btn-circle btn-circle-1" c="1" data-toggle="tab"><span style="font-size: 40px">1</span></button>
                                <p style="font-weight: bold">Data Satwa</p>
                            </div>
                            <div class="process-step" role="tab">
                                <button type="button" class="btn btn-default btn-circle btn-circle-2" c="2" data-toggle="tab"><span style="font-size: 40px">2</span></button>
                                <p style="font-weight: bold">Persebaran Satwa</p>
                            </div>
                            <div class="process-step" role="tab">
                                <button type="button" class="btn btn-default btn-circle btn-circle-3" c="3" data-toggle="tab"><span style="font-size: 40px">3</span></button>
                                <p style="font-weight: bold">Galeri Satwa</p>
                            </div>
                        </div>
                    </div>
                    
                    <form role="form" class="create-animal" method="POST" enctype="multipart/form-data" target="_blank">
                        {{ csrf_field() }}
                        <div class="tab-content">
                            @include('cms.fauna.tabs.1')
                            @include('cms.fauna.tabs.2')
                            @include('cms.fauna.tabs.3')
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection