@extends('layouts.cms.master')

<?php
    $title = "Status Konservasi";
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
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{ url('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

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
                    { data: 'name', name: 'name' },
                    { data: 'edit', name: 'edit' }
                ],
                "createdRow": function (row, data, dataIndex) {
                    $(row).children().last().css({'text-align': 'center'});
                }
            });
        });

        $(document).on('submit', '.create-taxonomic', function(e) {
            if (!confirm('Apakah data sudah benar?')) {
                e.preventDefault();
                return;
            }
        });

        $(document).on('click', '.btn-edit-taxonomic', function() {
            $('.uid').val($(this).attr('uid'));
            $('.old_name').val($(this).attr('taxonomic_name'));
            $('#modal-edit-taxonomic').modal('show');
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
        <div class="modal-dialog">
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
                                <input type="text" class="form-control old_name" name="taxonomic" placeholder="Ketik disini . . ." required>
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