@extends('layout')
@section('title', 'Farah')

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    @if (!empty($banner))
                        Edit Banner
                    @else
                        Create Create
                    @endif
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item" href="{{ route('banners.index') }}">Banner</li>
                    <li class="breadcrumb-item active">Create</li>
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
                    <a href="{{ route('banners.index') }}" class="btn btn-success" style="float: right;">List</a>
                </div>
                <div class="card-body">
                    @if (!empty($banner))
                        {{ Form::model($banner, ['route' => ['banners.update', $banner->id], 'method' => 'patch', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) }}
                    @else
                        {{ Form::open(['route' => 'banners.store', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
                    @endif

                    <div class="card-body">
                        <div class="form-group row {{ $errors->has('image') ? 'has-error' : '' }}">
                            <label for="exampleInputFile" class="col-sm-3 col-form-label">Image</label>
                            <div class=" col-sm-6">
                                <div class="custom-file">
                                    <input type="file" name="image" class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
                            </div>
                            @if (!empty($banner->image))
                                <img src="{{ asset('api/banner-image/' . $banner->image) }}" width="50" height="30">
                            @endif
                        </div>
                        <div class="form-group row {{ $errors->has('sub_service_id') ? 'has-error' : '' }}">
                            <label for="subServiceId" class="col-sm-3 col-form-label">Sub Service</label>
                            <div class="col-sm-6">
                                <select name="sub_service_id" id="subService" class="form-control">
                                    <option value="">-Select Sub Service-</option>
                                    @foreach ($subService as $ser)
                                        <option {{ !empty($banner->sub_service_id) && $banner->sub_service_id == $ser->sub_service_id?'selected':'' }} value="{{ $ser->sub_service_id }}">{{ $ser->sub_service_name }}
                                        </option>
                                    @endforeach
                                </select>
                               
                                {!! $errors->first('sub_service_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('product_id') ? 'has-error' : '' }}">
                            <label for="productId" class="col-sm-3 col-form-label">Product</label>
                            <div class="col-sm-6">
                                <select name="product_id" id="product" class="form-control">
                                    <option value="">-Select Product-</option>
                                    @foreach ($product as $prod)
                                        <option  {{ !empty($banner->product_id) && $banner->product_id == $prod->product_id?'selected':'' }} value="{{ $prod->product_id }}">{{ $prod->product_name }}
                                        </option>
                                    @endforeach
                                </select>
                                {!! $errors->first('product_id', '<p class="help-block">:message</p>') !!}
                            </div>
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
            $("#banner-module").addClass("active");
        });
    </script>
@endsection
