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

        $(document).on('keyup', '.latin-name', function() {
            var currentVal = $(this).val();

            if (currentVal) {
                currentVal = currentVal.toLowerCase();
                currentVal = currentVal.charAt(0).toUpperCase() + currentVal.slice(1);
                console.log(currentVal);
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
        $(document).on('click', '.class-next-tab', function(e) {
            e.preventDefault();
            var prev = $('.tab-menu.active').attr('c');
            var nextTab = $(this).attr('nt');
            var currentTab = $(this).attr('ct');

            if (checkRequiredField(currentTab)) {
                if (currentTab == 'end') {
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
                    $('.update-animal').submit();
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
                    }
                    if(!$('.latin-name').val()) {
                        alert('Nama Latin harus diisi!');
                        return false;
                    }
                    if(!$('.label-name').val()) {
                        alert('Label harus diisi!');
                        return false;
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

        $(document).on('click', '.btn-update-data-animal', function(e) {
            e.preventDefault();
            window.location.href = $(location).attr('href').replace(/.$/, $(this).attr('p'));
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
                        @include('cms.overview.update.tabs.1')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection