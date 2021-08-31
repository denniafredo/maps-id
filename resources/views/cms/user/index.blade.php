@extends('layouts.cms.master')

@section('title')
    Kelola User
@endsection

@section('css')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ url('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ url('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ url('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
@endsection

@section('scripts')
    <!-- DataTables -->
    <script src="{{ url('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ url('plugins/select2/js/select2.full.min.js') }}"></script>

    <script>
        $(function () {        
            $('#users').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "ajax": 'user/ajax/get-user',
                "columns": [
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    { data: 'role', name: 'role' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'edit', name: 'edit' }
                ],
                "createdRow": function (row, data, dataIndex) {
                    $(row).children().last().css({'text-align': 'center', 'cursor': 'pointer'});
                },
                "order": [[ 0, "asc" ]]
            });

            $(".select2-role").select2({
                tags: true,
                placeholder: "Pilih Role"
            });

            $(".select2-role-update").select2({
                tags: true,
                placeholder: "Pilih Role"
            });
        });

        $(document).on('click', '.btn-edit-user', function() {
            var name = $(this).parent().parent().children().first().text();
            var email = $(this).parent().parent().children().first().next().text();
            var rid = $(this).attr('rid');

            $('.uid').val($(this).attr('uid'));
            $('.current-user').text(name);
            $('.name-update').val(name);
            $('.email-update').val(email);
            $('.select2-role-update').select2().select2('val', rid);
            $('#modal-edit-user').modal('show');
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
    @if(Session::has('error-update'))
    <div class="alert alert-danger alert-dismissible alert-session">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-ban"></i> Gagal!</h5>
        Perubahan data gagal dilakukan. <br>
        Detail: <span>{{ Session::get('error-update') }}</span>
    </div>
    @endif
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible alert-session">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-ban"></i> Gagal!</h5>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    
    <div class="row">
        <div class="col-10 offset-1">
            <div id="accordion">
                <!-- we are adding the .class so bootstrap.js collapse plugin detects it -->
                <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                            Klik disini untuk tambah user baru
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
                            <form role="form" class="create-user" method="POST">
                                {{ csrf_field() }}
                                <div class="card-body">
                                    <div class="form-group">
                                        <label> <br> Nama </label>
                                        <input type="text" class="form-control" name="name" placeholder="Ketik disini . . ." required>
                                    </div>
                                    <div class="form-group">
                                        <label> <br> Email </label>
                                        <input type="email" class="form-control" name="email" placeholder="Ketik disini . . ." required>
                                    </div>
                                    <div class="form-group">
                                        <label> <br> Password </label>
                                        <input type="password" class="form-control" name="password" placeholder="Ketik disini . . ." required>
                                    </div>
                                    <div class="form-group">
                                        <label> <br> Konfirmasi Password </label>
                                        <input type="password" class="form-control" name="password_confirmation" placeholder="Ketik disini . . ." required>
                                    </div>

                                    <div class="form-group">
                                        <label> <br> Role </label>
                                        <select class="form-control select2-role" name="role_id">
                                            <option></option>
                                            @foreach($roles as $role)
                                                <option value={{ $role->id }}>{{ $role->role }}</option>
                                            @endforeach
                                        </select>
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
        <div class="col-10 offset-1">
            <div class="card">
                <div class="card-body">
                    <table id="users" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Tanggal Update</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modal-edit-user">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah Data <span class="current-user" style="font-weight: bold"></span></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form role="form" class="edit-user" method="POST" action={{ url('cms/user/update') }}>
                        {{ csrf_field() }}
                        <input type="hidden" class="uid" name="uid">
                        <div class="card-body">
                            <div class="form-group">
                                <label> <br> Nama </label>
                                <input type="text" class="form-control name-update" name="name" placeholder="Ketik disini . . ." required>
                            </div>
                            <div class="form-group">
                                <label> <br> Email </label>
                                <input type="email" class="form-control email-update" name="email" placeholder="Ketik disini . . ." required>
                            </div>
                            <div class="form-group update-pass">
                                <label> <br> Password </label> <span class="text-info small">*Bisa dikosongkan jika tidak ingin merubah password</span>
                                <input type="password" class="form-control" name="password" placeholder="Ketik disini . . .">
                            </div>
                            <div class="form-group update-conf-pass">
                                <label> <br> Konfirmasi Password </label>
                                <input type="password" class="form-control" name="password_confirmation" placeholder="Ketik disini . . .">
                            </div>

                            <div class="form-group">
                                <label> <br> Role </label>
                                <select class="form-control select2-role-update" name="role_id">
                                    <option></option>
                                    @foreach($roles as $role)
                                        <option value={{ $role->id }}>{{ $role->role }}</option>
                                    @endforeach
                                </select>
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