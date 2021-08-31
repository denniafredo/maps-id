@extends('layouts.cms.master')

<?php 
    $url = url('cms/overview/delete/photo/' . $uid);
    $detailImage = json_decode($detailImage, TRUE);
    $detailVideo = json_decode($detailVideo, TRUE);
?>

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
    <script src="{{ url('plugins/bootstrap-fileinput/piexif.min.js') }}"></script>
    
    <script>
        $(document).on('click', '.btn-update-data-animal', function(e) {
            e.preventDefault();
            window.location.href = $(location).attr('href').replace(/.$/, $(this).attr('p'));
        });
    </script>
    <script>        
        var JSONStringImage = '{!! $detailImage !!}';
        var JSONObjectImage = JSON.parse(JSONStringImage);
        var initialPreviewImage = [];
        var isOldImageExist = false;
        var isOldVideoExist = false;

        if (JSONObjectImage.length > 0) {
            isOldImageExist = true;
            var counterImage = 1;
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

            $.each(JSONObjectImage, function(k, v) {
                initialPreviewImage.push(v.path);
                v.url = '{!! $url !!}';
                v.contributor = v.contributor.replace(/\|\|\|/g, "'");

                var htmlDetail = '';
                htmlDetail += '<div class="form-group row">';
                htmlDetail += '<label class="label-image-contributor" style="padding-top: 5px;">Kontributor Foto ' + counterImage +'</label>';;
                htmlDetail += '<div class="col-12 row">';
                htmlDetail += '<div class="col-8 d-inline">';
                htmlDetail += '<input type="text" class="form-control old-input-image-contributors" uid="' + v.id + '" value="' + v.contributor + '" placeholder="Ketik disini . . .">';
                htmlDetail += '</div>';
                htmlDetail += '<div class="col-4 custom-control custom-radio d-inline" style="padding-top: 7px;">';
                htmlDetail += '<input type="radio" id="customRadio" name="default_image" class="custom-control-input radio-image old-image" value="old_' + v.id + '">';
                htmlDetail += '<label class="custom-control-label label-default-image" for="customRadio">Default Foto</label>';
                htmlDetail += '</div>';
                htmlDetail += '</div>';
                htmlDetail += '</div>';
                $('.image-contributor-card-body').append(htmlDetail);
                
                counterImage++;
            });

            var idDefaultImage = $('.update-animal').attr('did') ? $('.update-animal').attr('did') : null;
            if (idDefaultImage) {
                $.each($('.old-image'), function() {
                    console.log($(this).val());
                    if ($(this).val() == 'old_' + idDefaultImage) {
                        $(this).prop('checked', 'checked');
                    }
                });
            }
            
            reNumberingContributors('image');
        }

        $("#input-images").fileinput({
            theme: 'fas',
            showUpload: false,
            overwriteInitial: false,
            initialPreview: initialPreviewImage,
            initialPreviewAsData: true, // identify if you are sending preview data only and not the raw markup
            initialPreviewFileType: 'image', // image is the default and can be overridden in config below
            initialPreviewConfig: JSONObjectImage,
        });

        var JSONStringVideo = '{!! $detailVideo !!}';
        var JSONObjectVideo = JSON.parse(JSONStringVideo);
        var initialPreviewVideo = [];

        if (JSONObjectVideo.length > 0) {
            isOldVideoExist = true;
            var counterVideo = 1;
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
            $.each(JSONObjectVideo, function(k, v) {
                initialPreviewVideo.push(v.path);
                v.url = '{!! $url !!}';
                v.contributor = v.contributor.replace(/\|\|\|/g, "'");

                var htmlDetail = '';
                htmlDetail += '<div class="form-group row">';
                htmlDetail += '<label class="label-video-contributor" style="padding-top: 5px;">Kontributor Video ' + counterVideo +'</label>';
                htmlDetail += '<div class="col-sm-10">';
                htmlDetail += '<input type="text" class="form-control old-input-video-contributors" uid="' + v.id + '" value="' + v.contributor + '"  placeholder="Ketik disini . . .">';
                htmlDetail += '</div>';
                htmlDetail += '</div>';
                $('.video-contributor-card-body').append(htmlDetail);

                counterVideo++;
            })
        }

        $("#input-videos").fileinput({
            theme: 'fas',
            showUpload: false,
            overwriteInitial: false,
            initialPreview: initialPreviewVideo,
            initialPreviewAsData: true,
            initialPreviewFileType: 'video',
            initialPreviewConfig: JSONObjectVideo,
        });

        $(document).on('change', '#input-images', function(e) {
            if (!isOldImageExist) {
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
            }
        });

        $(document).on('fileloaded', '#input-images', function(e) {
            var htmlDetail = '';
            htmlDetail += '<div class="form-group row">';
            htmlDetail += '<label class="label-image-contributor" style="padding-top: 5px;">Kontributor Foto ' + counterImage +'</label>';
            htmlDetail += '<div class="col-12 row">';
            htmlDetail += '<div class="col-8 d-inline">';
            htmlDetail += '<input type="text" class="form-control input-image-contributors" placeholder="Ketik disini . . .">';
            htmlDetail += '</div>';
            htmlDetail += '<div class="col-4 custom-control custom-radio d-inline" style="padding-top: 7px;">';
            htmlDetail += '<input type="radio" id="customRadio" name="default_image" class="custom-control-input radio-image new-image">';
            htmlDetail += '<label class="custom-control-label label-default-image" for="customRadio">Default Foto</label>';
            htmlDetail += '</div>';
            htmlDetail += '</div>';
            htmlDetail += '</div>';
            $('.image-contributor-card-body').append(htmlDetail);

            counterImage++;
            reNumberingContributors('image');
        });

        $(document).on('change', '#input-videos', function(e) {
            if (!isOldVideoExist) {
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
            }
        });

        $(document).on('fileloaded', '#input-videos', function(e) {
            var htmlDetail = '';
            htmlDetail += '<div class="form-group row">';
            htmlDetail += '<label class="label-video-contributor" style="padding-top: 5px;">Kontributor Video ' + counterVideo +'</label>';
            htmlDetail += '<div class="col-sm-10">';
            htmlDetail += '<input type="text" class="form-control input-video-contributors" placeholder="Ketik disini . . .">';
            htmlDetail += '</div>';
            htmlDetail += '</div>';
            $('.video-contributor-card-body').append(htmlDetail);

            counterVideo++;
            reNumberingContributors('video');
        });

        $(document).on('click', '.fileinput-remove-button', function(e) {
            var forWhat = $(this).parent().parent().parent().parent().attr('for');

            if (forWhat == 'image') {
                $('.input-image-contributors').parent().parent().remove();
                $('#list-image-contributor').val([]);

                if ($('.old-input-image-contributors').length <= 0) {
                    $('.image-contributors').empty();
                    isOldImageExist = false;
                }
                
                reNumberingContributors('image');
            } else if (forWhat == 'video') {
                $('.input-video-contributors').parent().parent().remove();
                $('#list-video-contributor').val([]);

                if ($('.old-input-video-contributors').length <= 0) {
                    $('.video-contributors').empty();
                    isOldVideoExist = false;
                }

                reNumberingContributors('video');
            } else {
                $('.image-contributors').empty();
                $('.video-contributors').empty();
            }
        });

        $('#input-images, #input-videos').on('filedeleted', function(event, key, jqXHR, data) {
            var key = key;

            if (key.includes('image_')) {
                key = key.replace('image_', '');
                $('.old-input-image-contributors[uid="' + key + '"]').parent().parent().parent().remove();

                if ($('.old-input-image-contributors').length <= 0 && $('.input-image-contributors').length <= 0) {
                    $('.image-contributors').empty();
                    isOldImageExist = false;
                }

                reNumberingContributors('image');
            } else if (key.includes('video_')) {
                key = key.replace('video_', '');
                $('.old-input-video-contributors[uid="' + key + '"]').parent().parent().remove();

                if ($('.old-input-video-contributors').length <= 0 && $('.input-video-contributors').length <= 0) {
                    $('.video-contributors').empty();
                    isOldVideoExist = false;
                }
                
                reNumberingContributors('video');
            } else {
                alert('Terjadi kesalahan');
                event.preventDefault();
                return;
            }
        });

        function reNumberingContributors(type) {
            $counter = 1;
            if (type == 'image') {
                $.each($('.label-image-contributor'), function() {
                    $(this).text('Kontributor Foto ' + $counter++);
                });

                $counter = 1; // Reset counter to 1
                $counterNew = 1;
                var name = '';
                $.each($('.radio-image'), function() {
                    name = 'customRadio' + $counter++;
                    $(this).attr('id', name);
                    $(this).next().attr('for', name);

                    if ($(this).hasClass('new-image')) {
                        $(this).val('new_' + $counterNew++);
                    }
                });
            } else if (type == 'video') {
                $.each($('.label-video-contributor'), function() {
                    $(this).text('Kontributor Video ' + $counter++);
                });
            } else {
                // alert('Terjadi kesalahan');
                // return;
            }
        }
    </script>
    <script>
        $(document).on('submit', '.update-animal', function(e) {
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

            // Old Contributors
            var oldImageContributors = $('.old-input-image-contributors');
            var oldVideoContributors = $('.old-input-video-contributors');

            var dataOldImageContributors = [];
            if (oldImageContributors.length > 0) {
                $.each(oldImageContributors, function() {
                    var temp = {};
                    temp.id = $(this).attr('uid');
                    temp.contributor = $(this).val();
                    dataOldImageContributors.push(temp);
                });
            }

            var dataOldVideoContributors = [];
            if (oldVideoContributors.length > 0) {
                $.each(oldVideoContributors, function() {
                var temp = {};
                    temp.id = $(this).attr('uid');
                    temp.contributor = $(this).val();
                    dataOldVideoContributors.push(temp);
                });
            }

            $('#list-old-image-contributor').val(JSON.stringify(dataOldImageContributors));
            $('#list-old-video-contributor').val(JSON.stringify(dataOldVideoContributors));
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
                        @include('cms.overview.update.tabs.3')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection