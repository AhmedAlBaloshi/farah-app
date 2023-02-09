@extends('layout')
@section('title', 'Farah')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
@endsection

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Offer List</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Offer</li>
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
                    <a href="{{ route('offers.create') }}" class="btn btn-success" style="float: right;">Add New</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="offer_datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Service</th>
                                <th>Product</th>
                                <th>Title</th>
                                <th>Discount</th>
                                <th>Start Date</th>
                                <th>Start Time</th>
                                <th>End Date</th>
                                <th>End Time</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$offers->isEmpty())
                                @foreach ($offers as $key => $offer)
                                    <tr>
                                        <td> {{ $offer->id }}</td>
                                        <td> <a
                                                href="{{ route('sub_service_index', ['sub_service_id' => $offer->service_id]) }}">
                                                {{ $offer->service_id > 0 ? '#' . $offer->service_id : '' }}
                                            </a></td>
                                        <td><a href="{{ route('product_index', ['product_id' => $offer->product_id]) }}">
                                                {{ $offer->product_id > 0 ? '#' . $offer->product_id : '' }}
                                            </a></td>
                                        <td> {{ $offer->title }}</td>
                                        <td> {{ $offer->percentage }}%</td>
                                        <td> {{ $offer->start_date }}</td>
                                        <td> {{ date('g:i A', strtotime($offer->start_time)) }}</td>
                                        <td> {{ $offer->end_date }}</td>
                                        <td> {{ date('g:i A', strtotime($offer->end_time)) }}</td>
                                        <td>
                                            <a href="{{ route('offers.edit', $offer->id) }}" data-toggle="tooltip"
                                                data-original-title="Edit" class="edit btn btn-success btn-xs">
                                                <span class="fa fa-edit"></span>
                                            </a>
                                            <button onClick="deleteRecord('{{ route('offers.destroy', $offer->id) }}')"
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
                    title: "Are you sure want to delete Offer ?",
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
                                swal("Deleted!", "Your Offer has been deleted.",
                                    "success");
                                window.location.reload();
                            }
                        });
                    }
                });
        }
        $(document).ready(function() {
            $('#offer_datatable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                columnDefs: [{
                    targets: 9,
                    orderable: false
                }]
            });

            $("#module").removeClass("menu-close");
            $("#module").addClass("menu-open");
            $("#offer-module").addClass("active");
        });
    </script>

@endsection
