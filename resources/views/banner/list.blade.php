@extends('layout')
@section('title', 'Farah')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
@endsection

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Banner</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Banner</li>
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
                    <a href="{{ route('banners.create') }}" class="btn btn-success" style="float: right;">Add New</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="banner_datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Sub Service</th>
                                <th>Product</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                          
                            @if (!$banners->isEmpty())
                                @foreach ($banners as $key => $banner)
                                    <tr>
                                        <td> {{ $banner->id }}</td>
                                        <td>
                                            <img src="{{ asset('api/banner-image/' . $banner->image) }}" width="50"
                                                height="30">
                                        </td>
                                        <td> {{ $banner->subService?$banner->subService->sub_service_name :''}}</td>
                                        <td> {{ $banner->product?$banner->product->product_name :''}}</td>
                                        <td>
                                            <a href="{{ route('banners.edit',$banner->id) }}" data-toggle="tooltip" data-original-title="Edit"
                                                class="edit btn btn-success btn-xs">
                                                <span class="fa fa-edit"></span>
                                            </a>
                                            <button onClick="deleteRecord('{{ route('banners.destroy',$banner->id) }}')" data-original-title="Delete"
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
                    title: "Are you sure want to delete banner ?",
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
                            type: "DELETE",
                            url: dataUrl,
                            data: {
                                _token: token
                            },
                            success: function(data) {
                                swal("Deleted!", "Your banner has been deleted.",
                                    "success");
                                window.location.reload();
                            }
                        });
                    }
                });
        }
        function dataTable() {
            $('#banner_datatable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                columnDefs: [{
                    targets: 4,
                    orderable: false
                }]
            });
        }

        $(document).ready(function() {
            dataTable()
            $("#module").removeClass("menu-close");
            $("#module").addClass("menu-open");
            $("#banner-module").addClass("active");
        });
    </script>

@endsection
