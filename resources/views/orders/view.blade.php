@extends('layout')
@section('title', 'Farah')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
@endsection

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Order</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Order</li>
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
                    {{-- <a href="{{ route('product_create') }}" class="btn btn-success" style="float: right;">Add New</a> --}}
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tbody>
                            <tr>
                                <th>ID</th>
                                <td>{{ $order->id }}</td>
                            </tr>
                            <tr>
                                <th>Customer Name</th>
                                <td>{{ $order->user_name ? $order->user_name : $order->guest_name }}</td>
                            </tr>
                            <tr>
                                <th>Customer Email</th>
                                <td>{{ $order->user_email ? $order->user_email : $order->guest_email }}</td>
                            </tr>
                            <tr>
                                <th>Customer Phone</th>
                                <td>{{ $order->user_mobile_no ? $order->user_mobile_no : $order->guest_mobile_no }}</td>
                            </tr>
                            <tr>
                                <th>Payment Type</th>
                                <td>{{ $order->payment_type }}</td>
                            </tr>
                            <tr>
                                <th>Delivery Status</th>
                                <td>
                                    @php
                                        $color = '';
                                        if ($order->delivery_status == 'Pending') {
                                            $color = 'badge-warning';
                                        } elseif ($order->delivery_status == 'Processing') {
                                            $color = 'badge-info';
                                        } elseif ($order->delivery_status == 'Shipped') {
                                            $color = 'badge-info';
                                        } elseif ($order->delivery_status == 'Delivered') {
                                            $color = 'badge-success';
                                        } elseif ($order->delivery_status == 'Cancelled') {
                                            $color = 'badge-danger';
                                        }
                                    @endphp
                                    <span class="badge {{ $color }} text-sm">{{ $order->delivery_status }}</span>
                                </td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td>{{ number_format($order->grand_total, 2) }} OMR</td>
                            </tr>
                            <tr>
                                <th>Order Date</th>
                                <td>{{ date('d-M-Y', strtotime($order->date)) }}</td>
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
                    <h3 class="card-title">Items</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="item_datatable">
                        <thead>
                            <tr>
                                <th>Product Id</th>
                                <th>Product name</th>
                                <th>Product Image</th>
                                <th>quantity</th>
                                <th>Unit Price</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($order['details']))
                                @foreach ($order['details'] as $item)
                                    <tr>
                                        <td>
                                            <a href="{{ route('product_index', ['product_id' => $item->product_id]) }}">
                                                #{{ $item->product_id }}
                                            </a>
                                        </td>
                                        <td>{{ $item->product_name }}</td>
                                        <td><img src="{{ asset('api/product-image/' . $item->product_image) }}"width="80"
                                                height="60" alt=""></td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>{{ number_format($item->unit_price,2) }} OMR</td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
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
