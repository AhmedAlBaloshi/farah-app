@extends('layout')
@section('title', 'Farah')

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1> @if(!empty($category)) Edit Category @else Create Category @endif </h1>
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
        <div class="card">
            <div class="card-header">
                <a href="{{ route('category_index') }}" class="btn btn-success" style="float: right;">List</a>
            </div>
            <div class="card-body">
                    @if(!empty($category))
                        {{ Form::model($category, ['route' => ['category_update', $category->category_id], 'method' => 'patch','enctype'=>'multipart/form-data','class'=>'form-horizontal']) }}
                    @else
                        {{ Form::open(['route' => 'category_store','class'=>'form-horizontal','enctype'=>'multipart/form-data']) }}
                    @endif

                    <div class="card-body">
                        
                        <div class="form-group row {{ $errors->has('parent_category') ? 'has-error' : ''}}">
                            <label for="parentCategoryId" class="col-sm-3 col-form-label">Category</label>
                            <div class="col-sm-6">
                                {!! Form::select('parent_category', $categoryList, null, ['class' => 'form-control','id'=>'parentCategoryId','placeholder'=>'-Select Category-']) !!}
                                {!! $errors->first('parent_category', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('service_id') ? 'has-error' : ''}}">
                            <label for="serviceId" class="col-sm-3 col-form-label">Service</label>
                            <div class="col-sm-6">
                                {!! Form::select('service_id', $service, null, ['class' => 'form-control','id'=>'serviceId','placeholder'=>'-Select Service-']) !!}
                                {!! $errors->first('service_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('category_name') ? 'has-error' : ''}}">
                            <label for="categoryName" class="col-sm-3 col-form-label">Category English Name</label>
                            <div class="col-sm-6">
                                {!! Form::text('category_name', null, ['class' => 'form-control','id'=>'categoryName','placeholder'=>'Category English Name']) !!}
                                {!! $errors->first('category_name', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('category_name_ar') ? 'has-error' : ''}}">
                            <label for="categoryNameAr" class="col-sm-3 col-form-label">Category Arabic Name</label>
                            <div class="col-sm-6">
                                {!! Form::text('category_name_ar', null, ['class' => 'form-control','id'=>'categoryNameAr','placeholder'=>'Category Arabic Name']) !!}
                                {!! $errors->first('category_name_ar', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('image') ? 'has-error' : ''}}">
                            <label for="exampleInputFile" class="col-sm-3 col-form-label">Image</label>
                            <div class="input-group col-sm-6">
                                <div class="custom-file">
                                    <input type="file" name="image" class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <div class="input-group-append">
                                    <span class="input-group-text" id="">Upload</span>
                                </div>
                                {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
                            </div>
                            @if (!empty($category->image))
                                <img src="/category-image/{{ $category->image }}" width="50" height="30">
                            @endif
                        </div>
                    </div>
                    <!-- /.card-body -->
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
          </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    
$(document).ready(function() {
    $("#module").removeClass("menu-close");
    $("#module").addClass("menu-open");
    $("#category-module").addClass("active");
});
</script>

@endsection