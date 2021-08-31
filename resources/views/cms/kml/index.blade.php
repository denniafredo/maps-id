@extends('layouts.cms.master')
<?php
    $title = "Upload KML";
?>
@section('title')
    {{ $title }}
@endsection

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <!-- Bootstrap Fileinput -->
    <link rel="stylesheet" href="{{ url('plugins/bootstrap-fileinput/bootstrap-fileinput.min.css') }}" type="text/css">
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{ url('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Bootstrap Fileinput -->
    <script src="{{ url('plugins/bootstrap-fileinput/bootstrap-fileinput.min.js') }}"></script>
    <script src="{{ url('plugins/bootstrap-fileinput/theme.min.js') }}"></script>

    <script>
        $(function () {
            $('#kml').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "ajax": 'ajax/upload-kml',
                "columns": [
                    { data: 'path', name: 'path' },
                    { data: 'delete', name: 'delete' }
                ],
                "createdRow": function (row, data, dataIndex) {
                    $(row).children().last().css({'text-align': 'center'});
                }
            });
        });

        $("#input-kml").fileinput({
            theme: 'fas'
        });

        $(document).on('submit', '.create-kml', function(e) {
            if (!confirm('Apakah data sudah benar?')) {
                e.preventDefault();
                return;
            }
        });

        $(document).on('click', '.btn-delete-kml', function() {
            var uid = $(this).attr('uid');
            var currentButton = $(this);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                dataType: 'JSON',
                type: 'POST',
                url: 'ajax/delete-kml',
                data: {
                    'uid': uid,
                },
                beforeSend: function() {
                    $('.alert-session').remove();
                    $('.btn-delete-kml').prop('readonly', true);
                    currentButton.empty().append('<i class="fas fa-spinner fa-spin"></i>');
                },
                success: function(response) {
                    $('.btn-delete-kml').prop('readonly', false);
                    currentButton.empty().append('<button class="btn btn-sm btn-danger btn-delete-kml"> Hapus Data </button>');

                    if (response.status == 'OK') {
                        $('#kml').DataTable().ajax.reload();
                    } else {
                        console.log("Error Delete Data!");
                    }
                },
                error: function(response) {
                    console.log("Error Delete Data!");
                }
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
                                Hindari penggunaan simbol pada filename.
                            </div>
                            <form role="form" class="create-kml" method="POST" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="card-body">
                                    <div class="form-group">
                                        <label>{{ $title }}</label>
                                        <input id="input-kml" name="kmls[]" type="file" accept=".kml"  multiple>
                                    </div>
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
                    <table id="kml" class="table table-bordered table-hover">
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
    <div class="modal fade" id="modal-edit-kml">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah Data {{ $title }}</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" class="edit-kml" method="POST">
                        {{ csrf_field() }}
                        <div class="card-body">
                            <div class="form-group">
                                <label>{{ $title }}</label>
                                <input type="text" class="form-control old_name" name="kml" placeholder="Ketik disini . . ." required>
                                <input type="hidden" class="uid" name="uid">
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