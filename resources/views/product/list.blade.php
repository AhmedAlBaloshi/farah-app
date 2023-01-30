@extends('layout')
@section('title', 'Farah')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
@endsection

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Product</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Product</li>
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
                    <a href="{{ route('product_create') }}" class="btn btn-success" style="float: right;">Add New</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="product_datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Service</th>
                                <th>Service List</th>
                                <th>Sub Service </th>
                                <th>Category </th>
                                <th>Product Name</th>
                                <th>Product Image</th>
                                <th>Banner Image</th>
                                <th>Rate</th>
                                <th>Discount</th>
                                <th>Active</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$product->isEmpty())
                                @if (isset($_GET['product_id']))
                                    @php
                                        $product = $product->where('product_id', $_GET['product_id']);
                                    @endphp
                                @endif

                                @foreach ($product as $key => $product)
                                    <tr>
                                        <td> {{ $product->product_id }}</td>
                                        <td> {{ @$product->service->service_name }}</td>
                                        <td> {{ @$product->serviceList->service_name }}</td>
                                        <td> {{ @$product->subServiceList->sub_service_name }}</td>
                                        <td> {{ @$product->category->category_name }}</td>
                                        <td> {{ $product->product_name }}</td>
                                        <td>
                                            @if ($product->product_image)
                                                <img src="{{ asset('api/product-image/' . $product->product_image) }}"
                                                    width="80" height="60">
                                            @endIf
                                        </td>
                                        <td>
                                            @if (isset($product->banner->image))
                                                <img src="{{ asset('api/banner-image/' . $product->banner->image) }}"
                                                    width="50" height="30">
                                            @endIf
                                        </td>
                                        <td> {{ $product->rate }}</td>
                                        <td>
                                            @if ($product->offers)
                                                @if (@$product->offers->first()->end_date >= date('Y-m-d'))
                                                    {{ @$product->offers->first()->end_time <= date('H:r:s') ? $product->discount : 0 }}
                                                @else
                                                    {{ 0 }}
                                                @endIf
                                            @else
                                                {{ 0 }}
                                            @endIf
                                        </td>
                                        <td> {!! !empty($product->is_active)
                                            ? '<span class="badge bg-success">Active</span>'
                                            : '<span class="badge bg-danger">In-active</span>' !!} </td>
                                        <td>
                                            <a href="{{ route('product_edit', $product->product_id) }}"
                                                data-toggle="tooltip" data-original-title="Edit"
                                                class="edit btn btn-success btn-xs">
                                                <span class="fa fa-edit"></span>
                                            </a>
                                            <button
                                                onClick="deleteRecord('{{ route('product_delete', $product->product_id) }}')"
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
                    title: "Are you sure want to delete product ?",
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
                                swal("Deleted!", "Your product has been deleted.",
                                    "success");
                                window.location.reload();
                            }
                        });
                    }
                });
        }
        $(document).ready(function() {
            $('#product_datatable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                columnDefs: [{
                    targets: 6,
                    orderable: false
                }]
            });

            $("#module").removeClass("menu-close");
            $("#module").addClass("menu-open");
            $("#product-module").addClass("active");
        });
    </script>

@endsection
