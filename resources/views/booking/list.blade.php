@extends('layout')
@section('title', 'Farah')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
@endsection

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Booking</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Booking</li>
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
                    @if (isset($_GET['list']) && $_GET['list'] == 'all')
                        <a href="{{ route('bookings.index') }}" class="btn btn-success" style="float: right;">Pending
                            Bookings</a>
                    @else
                        <a href="{{ route('bookings.index', ['list' => 'all']) }}" class="btn btn-success"
                            style="float: right;">All Bookings</a>
                    @endif
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="booking_datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Booking Date</th>
                                <th>Start Time</th>
                                <th>End Time</th>
                                <th>Payment Type</th>
                                <th>Payment Status</th>
                                <th>Total</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$bookings->isEmpty())
                                @if (isset($_GET['booking_id']))
                                    @php
                                        $bookings = $bookings->where('id', $_GET['booking_id']);
                                    @endphp
                                @endif
                                @foreach ($bookings as $key => $booking)
                                    <tr>
                                        <td> {{ $booking->id }}</td>
                                        <td> {{ @$booking->user->firstname ? @$booking->user->firstname : @$booking->guest->firstname }}
                                        </td>
                                        <td>{{ $booking->booking_date }}</td>
                                        <td>{{ $booking->booking_start_time }}</td>
                                        <td>{{ $booking->booking_end_time }}</td>
                                        <td> {{ $booking->payment_type }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $booking->payment_status == 'unpaid' ? 'badge-warning' : 'badge-success' }} text-sm">{{ $booking->payment_status }}</span>
                                        </td>

                                        <td> {{ number_format($booking->grand_total, 2) }} OMR</td>
                                        <td>
                                            <a href="{{ route('bookings.show', $booking->id) }}" title="show"
                                                class="edit btn text-light btn-success btn-sm">
                                                <span class="fa fa-eye"></span>
                                                Show
                                            </a>
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
        // function updateStatus(url, delivery_status) {
        //     let status = delivery_status;
        //     if (delivery_status == 'Pending') {
        //         status = "Processing";
        //     } else if (delivery_status == 'Processing') {
        //         status = "Shipped";
        //     } else if (delivery_status == 'Shipped') {
        //         status = "Delivered";
        //     }
        //     var dataUrl = url;
        //     var token = $('meta[name="csrf-token"]').attr('content');
        //     let title = status == 'Cancelled' ? "Are you sure you want to cancel the order?" : "Update status " +
        //         delivery_status + " to " + status;
        //     swal({
        //             title: title,
        //             type: status == 'Cancelled' ? "warning" : "success",
        //             showCancelButton: true,
        //             size: 'large',
        //             confirmButtonClass: status == 'Cancelled' ? 'btn-danger' : "btn-success",
        //             confirmButtonText: "Update!",
        //             cancelButtonText: "Cancel",
        //             closeOnConfirm: false,
        //             closeOnCancel: true
        //         },
        //         function(isConfirm) {
        //             if (isConfirm) {
        //                 $.ajax({
        //                     type: "POST",
        //                     url: dataUrl,
        //                     data: {
        //                         _token: token,
        //                         status: status
        //                     },
        //                     success: function(data) {
        //                         swal("Success", status == 'Cancelled' ? "Order Cancelled Successfully." :
        //                             "Order Successfully Updated",
        //                             "success");
        //                         window.location.reload();
        //                     }
        //                 });
        //             }
        //         });
        // }

        $(document).ready(function() {
            $('#booking_datatable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                columnDefs: [{
                    targets: 5,
                    orderable: false
                }]
            });

            $("#module").removeClass("menu-close");
            $("#module").addClass("menu-open");
            $("#booking-module").addClass("active");
        });
    </script>

@endsection
