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
                    <h3 class="card-title">View</h3>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>ID</th>
                                <td>{{ $booking->id }}</td>
                            </tr>
                            <tr>
                                <th>Customer Name</th>
                                <td>{{ $booking->user_name ? $booking->user_name : $booking->guest_name }}</td>
                            </tr>
                            <tr>
                                <th>Customer Email</th>
                                <td>{{ $booking->user_email ? $booking->user_email : $booking->guest_email }}</td>
                            </tr>
                            <tr>
                                <th>Customer Phone</th>
                                <td>{{ $booking->user_mobile_no ? $booking->user_mobile_no : $booking->guest_mobile_no }}
                                </td>
                            </tr>
                            <tr>
                                <th>Booking Date</th>
                                <td>{{ $booking['details']->booking_date }}
                                </td>
                            </tr>
                            <tr>
                                <th>Booking Start Time</th>
                                <td>{{ $booking['details']->booking_start_time }}
                                </td>
                            </tr>
                            <tr>
                                <th>Booking End Time</th>
                                <td>{{ $booking['details']->booking_end_time }}
                                </td>
                            </tr>
                            <tr>
                                <th>Payment Type</th>
                                <td>{{ $booking->payment_type }}</td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td>{{ number_format($booking->grand_total, 2) }} OMR</td>
                            </tr>
                            <tr>
                                <th>Order Date</th>
                                <td>{{ date('d-M-Y', strtotime($booking->date)) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Service</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="item_datatable">
                        <thead>
                            <tr>
                                <th>Service Id</th>
                                <th> Name</th>
                                <th> Image</th>
                                <th>Total Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($booking['details']))
                                <tr>
                                    <td>
                                        <a
                                            href="{{ route('sub_service_index', ['sub_service_id' => $booking['details']->service_id]) }}">
                                            #{{ $booking['details']->service_id }}
                                        </a>
                                    </td>
                                    <td>{{ $booking['details']->service_name }}</td>
                                    <td><img src="{{ asset('api/product-image/' . $booking['details']->product_image) }}"width="80"
                                            height="60" alt=""></td>
                                    <td>{{ $booking['details']->price }}</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection
@section('script')
    <script>
        $(document).ready(function() {
            $('#item_datatable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                columnDefs: [{
                    targets: 0,
                    orderable: false
                }]
            });

            $("#module").removeClass("menu-close");
            $("#module").addClass("menu-open");
            $("#order-module").addClass("active");
        });
    </script>
@endSection
