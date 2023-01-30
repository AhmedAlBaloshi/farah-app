@extends('layout')
@section('title', 'Farah')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
@endsection

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Feedback List</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Feedbacks</li>
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
                    {{-- <a href="{{ route('service_create') }}" class="btn btn-success" style="float: right;">Add New</a> --}}
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="feedback_datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User Name</th>
                                <th>description</th>
                                {{-- <th>Action</th> --}}
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$feedbacks->isEmpty())
                                @foreach ($feedbacks as $key => $feedback)
                                    <tr>
                                        <td> {{ $feedback->id }}</td>
                                        <td> {{ ucwords(@$feedback->user->firstname . ' ' . @$feedback->user->lastname) }}
                                        </td>
                                        <td style="width: 75%"> {{ $feedback->description }}</td>
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
       $(document).ready(function() {
            $('#feedback_datatable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "ordering": true,
                "info": true,
                "autoWidth": false,
                columnDefs: [{
                    targets: 2,
                    orderable: false
                }]
            });

            $("#module").removeClass("menu-close");
            $("#module").addClass("menu-open");
            $("#feedback-module").addClass("active");
        });
    </script>

@endsection
