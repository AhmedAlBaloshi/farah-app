@extends('layout')
@section('title', 'Farah')

@section('style')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.css">
@endsection

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Category</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Category</li>
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
            	<a href="{{ route('category_create')}}" class="btn btn-success" style="float: right;">Add New</a>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-striped" id="category_datatable">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Parent Category</th>
                            <th>Service</th>
                            <th>Category English Name</th>
                            <th>Category Arabic Name</th>
                            <th>Image</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!$category->isEmpty())
                            @foreach($category as $key => $category)
                            <tr>
                                <td> {{ $category->category_id }}</td>
                                <td> {{ !empty($category->category->category_name) ? $category->category->category_name : "N/A" }}</td>
                                <td> {{ @$category->service->service_name }}</td>
                                <td> {{ $category->category_name }}</td>
                                <td> {{ $category->category_name_ar }}</td>
                                <td> 
                                    @if(!empty($category->image))
                                        <img src="/category-image/{{$category->image}}" height="70" width="70">
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('category_edit',$category->category_id) }}" data-toggle="tooltip" data-original-title="Edit" class="edit btn btn-success btn-xs">
                                        <span class="fa fa-edit"></span>
                                    </a>
                                    <button data-href="{{ route('category_delete',$category->category_id) }}" data-original-title="Delete" class="delete btn btn-danger btn-xs delete-record">
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
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-sweetalert/1.0.1/sweetalert.min.js"></script>

<script>
$(document).ready(function() {
    $('#category_datatable').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        columnDefs: [
            { 
                targets: 4,
                orderable: false
            },{ 
                targets: 5,
                orderable: false
            }
        ]
    });
    $('.delete-record').on('click',function() {
        var dataUrl = $(this).data('href');
        var token   = $('meta[name="csrf-token"]').attr('content');
        swal({
            title: "Are you sure want to delete category ?",
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
                    data: { _token: token },
                    success: function (data) {
                        swal("Deleted!", "Your category has been deleted.", "success");
                        window.location.reload();
                    }
                });
            }
        });
    });
    $("#module").removeClass("menu-close");
    $("#module").addClass("menu-open");
    $("#category-module").addClass("active");
});

</script>

@endsection