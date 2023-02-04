@extends('layout')
@section('title', 'Farah')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
@endsection

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Payment List</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Payment</li>
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
                </div>
                <div class="card-body">
                    <table class="table table-borderless w-25">
                        <tbody>
                            <tr>
                                <th>This Month's Payment:</th>
                                <td>@php
                                    $startDate = Carbon\Carbon::now()
                                        ->startOfMonth()
                                        ->toDateString();
                                    $endDate = Carbon\Carbon::now()
                                        ->endOfMonth()
                                        ->toDateString();
                                        $currentYear = Carbon\Carbon::now()->year;
                                    $thisYearData = $payments->filter(function ($item) use($currentYear) {
                                        return Carbon\Carbon::parse($item->payment_date)->year === $currentYear;
                                    });
                                @endphp
                                    {{ number_format($payments->whereBetween('payment_date', [$startDate, $endDate])->sum('net_amount'), 2) }}
                                    OMR
                                </td>
                            </tr>
                            <tr>
                                <th>This Year's Payment:</th>
                                <td>{{ number_format($thisYearData->sum('net_amount'), 2) }}
                                    OMR</td>
                            </tr>
                            <tr>
                                <th>Total Payment:</th>
                                <td>{{ number_format($payments->sum('net_amount'), 2) }} OMR</td>
                            </tr>
                        </tbody>
                    </table>

                    <table class="table table-bordered table-striped" id="payment_datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Order Id</th>
                                <th>Booking Id</th>
                                <th>Payment Method</th>
                                <th>Amount</th>
                                <th>Discount</th>
                                <th>Date</th>
                                <th>Net Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $payment)
                                <tr>
                                    <td>{{ $payment->id }}</td>
                                    <td>
                                        @if (count($payment->order->timeSlot) < 1)
                                            <a href="{{ route('orders.index', ['order_id' => $payment->order_id]) }}">
                                                #{{ $payment->order_id }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>
                                        @if (count($payment->order->timeSlot) > 0)
                                            <a href="{{ route('bookings.index', ['booking_id' => $payment->order_id]) }}">
                                                #{{ $payment->order_id }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ $payment->payment_method }}</td>
                                    <td>{{ number_format($payment->amount, 2) }} OMR</td>
                                    <td>{{ number_format($payment->discount, 2) }} OMR</td>
                                    <td>{{ $payment->payment_date }}</td>
                                    <td>{{ number_format($payment->net_amount, 2) }} OMR</td>
                                </tr>
                            @endforeach
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
        $(document).ready(function() {
            $('#payment_datatable').DataTable({
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
            $("#payment-module").addClass("active");
        });
    </script>

@endsection
