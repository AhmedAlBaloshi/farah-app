@extends('layout')
@section('title', 'Farah')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
@endsection

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>User List</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Users</li>
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
                    <a href="{{ route('users.create') }}" class="btn btn-success" style="float: right;">Add New</a>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped" id="user_datatable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>profile</th>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$users->isEmpty())
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td> {{ $user->id }}</td>
                                        <td style="width: 10%">
                                            @if ($user->profile_image)
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('api/profile-image/' . $user->profile_image) }}"
                                                        alt=""class="img-fluid rounded-circle border border-dark border-3"style="width: 60px;height: 60px;">
                                                </div>
                                            @endif
                                        </td>
                                        <td> {{ ucwords(@$user->firstname . ' ' . @$user->lastname) }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @if ($user->role_id == 1)
                                                {{ 'Admin' }}
                                            @elseif($user->role_id == 2)
                                                {{ 'Staff' }}
                                            @elseif($user->role_id == 3)
                                                {{ 'Customer' }}
                                            @endIf
                                        </td>
                                        <td>
                                            <a href="{{ route('users.edit', $user->id) }}" data-toggle="tooltip"
                                                data-original-title="Edit" class="edit btn btn-success btn-xs">
                                                <span class="fa fa-edit"></span>
                                            </a>
                                            <button onClick="deleteRecord('{{ route('users.destroy', $user->id) }}')"
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
                    title: "Are you sure want to delete user?",
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
                                swal("Deleted!", "User has been deleted.",
                                    "success");
                                window.location.reload();
                            }
                        });
                    }
                });
        }

        $(document).ready(function() {
            $('#user_datatable').DataTable({
                "paging": true,
                "lengthChange": false,
                "searching": true,
                "scrolling": true,
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
            $("#user-module").addClass("active");
        });
    </script>

@endsection
