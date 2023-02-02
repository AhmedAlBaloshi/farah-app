@extends('layout')
@section('title', 'Farah')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
@endsection

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Sub Service List</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Sub Service</li>
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
                    <a href="{{ route('sub_service_create') }}" class="btn btn-success" style="float: right;">Add New</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped w-100" id="service_datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Service</th>
                                <th>Sub Service English Name</th>
                                <th>Sub Service Arabic Name</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Difference</th>
                                <th>Amount</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$subService->isEmpty())
                                @if (isset($_GET['sub_service_id']))
                                    @php
                                        $subService = $subService->where('sub_service_id', $_GET['sub_service_id']);
                                    @endphp
                                @endif
                                @foreach ($subService as $index => $service)
                                    <tr>
                                        <td> {{ $service->sub_service_id }}</td>
                                        <td> {{ @$service->serviceList->service_name }}</td>
                                        <td> {{ $service->sub_service_name }}</td>
                                        <td> {{ $service->sub_service_name_ar }}</td>
                                        <td>{{ $service->start_time }}</td>
                                        <td>{{ $service->end_time }}</td>
                                        <td>{{ $service->minutes }} minutes</td>
                                        <td>{{ number_format($service->amount, 2) }} OMR</td>
                                        <td> {!! !empty($service->is_active)
                                            ? '<span class="badge bg-success">Active</span>'
                                            : '<span class="badge bg-danger">In-active</span>' !!}
                                        </td>
                                        <td>
                                            <a href="{{ route('sub_service_edit', $service->sub_service_id) }}"
                                                data-toggle="tooltip" data-original-title="Edit"
                                                class="edit btn btn-success btn-xs">
                                                <span class="fa fa-edit"></span>
                                            </a>
                                            <button
                                                onClick="deleteRecord('{{ route('sub_service_delete', $service->sub_service_id) }}')"
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
                    title: "Are you sure want to delete service list ?",
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
                                swal("Deleted!", "Your service list has been deleted.", "success");
                                window.location.reload();
                            }
                        });
                    }
                });
        }
        $(document).ready(function() {
            let table = $('#service_datatable').DataTable({
                "scrollX": true,
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": true,
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
            $("#sub-service-module").addClass("active");
        });
    </script>

@endsection
