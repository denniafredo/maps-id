@extends('layouts.cms.master')

@section('title')
    Overview
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
            $('#overview').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                "responsive": true,
                "ajax": 'overview/ajax/overview',
                "columns": [
                    { data: 'name', name: 'name' },
                    { data: 'conservation', name: 'conservation' },
                    { data: 'updated_at', name: 'updated_at' },
                    { data: 'delete', name: 'delete' }
                ],
                "createdRow": function (row, data, dataIndex) {
                    $(row).addClass('row-overview').css({'cursor': 'pointer'});
                    $(row).children().first().next().css({'vertical-align': 'middle'});
                    $(row).children().first().next().next().css({'vertical-align': 'middle'});
                    $(row).children().last().css({'text-align': 'center'});
                }
            });
        });

        $(document).on('click', '.btn-delete-animal', function(e) {
            e.stopPropagation();
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
                url: 'overview/ajax/delete-animal',
                data: {
                    'uid': uid,
                },
                beforeSend: function() {
                    $('.btn-delete-animal').prop('readonly', true);
                    currentButton.empty().append('<i class="fas fa-spinner fa-spin"></i>');
                },
                success: function(response) {
                    $('.btn-delete-animal').prop('readonly', false);
                    currentButton.empty().append('Hapus');

                    if (response.status == 'OK') {
                        $('#overview').DataTable().ajax.reload();
                    } else {
                        alert("Error Delete Data! Silahkan Refresh dan coba lagi.");
                    }
                },
                error: function(response) {
                    alert("Error Delete Data! Silahkan Refresh dan coba lagi.");
                }
            });
        });

        $(document).on('click', '.row-overview', function(e) {
            var uid = $(this).children().first().children().first().attr('uid');

            var win = window.open($(location).attr('href') + '/update/' + uid + '/1', '_blank');
            if (win) {
                //Browser has allowed it to be opened
                win.focus();
            } else {
                //Browser has blocked it
                alert('Please allow popups for this website');
            }
        });
    </script>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title" style="font-weight: bold">Total Satwa: {{ $animalCount }}</h3>
                </div>
                <div class="card-body">
                    <table id="overview" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Nama Satwa</th>
                                <th>Status Konservasi</th>
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
@endsection