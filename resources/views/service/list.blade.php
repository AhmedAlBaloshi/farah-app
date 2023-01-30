@extends('layout')
@section('title', 'Farah')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
@endsection

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Service List</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Service</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            @if (\Session::get('success'))
                <div class="alert alert-success">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    {{ \Session::get('success') }}
                </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">List</h3>
                    <a href="{{ route('service_create') }}" class="btn btn-success" style="float: right;">Add New</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="service_datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Service English Name</th>
                                <th>Service Arabic Name</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$service->isEmpty())
                                @foreach ($service as $key => $service)
                                    <tr>
                                        <td> {{ $service->service_id }}</td>
                                        <td> {{ $service->service_name }}</td>
                                        <td> {{ $service->service_name_ar }}</td>
                                        <td>
                                            @if (!empty($service->image))
                                                <img src="{{ asset('api/service-image/' . $service->image) }}"
                                                    height="70" width="70">
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('service_edit', $service->service_id) }}"
                                                data-toggle="tooltip" data-original-title="Edit"
                                                class="edit btn btn-success btn-xs">
                                                <span class="fa fa-edit"></span>
                                            </a>
                                            <button
                                                onClick="deleteRecord('{{ route('service_delete', $service->service_id) }}')"
                                                data-original-title="Delete"
                                                class="delete btn btn-danger btn-xs delete-record">
                                                <span class="ion-trash-a"></span>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript"
        src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>

    <script>
        function deleteRecord(url) {
            var dataUrl = url;
            var token = $('meta[name="csrf-token"]').attr('content');
            swal({
                    title: "Are you sure want to delete service ?",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonClass: "btn-danger",
                    confirmButtonText: "Yes, delete it!",
                    cancelButtonText: "cancel",
                    closeOnConfirm: false,
                    closeOnCancel: true
                },
                function(isConfirm) {
                    if (isConfirm) {
                        $.ajax({
                            type: "POST",
                            url: dataUrl,
                            data: {
                                _token: token
                            },
                            success: function(data) {
                                swal("Deleted!", "Your service has been deleted.",
                                    "success");
                                window.location.reload();
                            }
                        });
                    }
                });
        }
        $(document).ready(function() {
            $('#service_datatable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                columnDefs: [{
                    targets: 3,
                    orderable: false
                }, {
                    targets: 4,
                    orderable: false
                }]
            });

            $("#module").removeClass("menu-close");
            $("#module").addClass("menu-open");
            $("#service-module").addClass("active");
        });
    </script>

@endsection
