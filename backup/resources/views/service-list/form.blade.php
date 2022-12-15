@extends('layout')
@section('title', 'Farah')

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1> @if(!empty($serviceList)) Edit Service List @else Create Service List @endif </h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Service List</li>
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
                <a href="{{ route('service_list_index') }}" class="btn btn-success" style="float: right;">List</a>
            </div>
            <div class="card-body">
                    @if(!empty($serviceList))
                        {{ Form::model($serviceList, ['route' => ['service_list_update', $serviceList->service_list_id], 'method' => 'patch','enctype'=>'multipart/form-data','class'=>'form-horizontal']) }}
                    @else
                        {{ Form::open(['route' => 'service_list_store','class'=>'form-horizontal','enctype'=>'multipart/form-data']) }}
                    @endif

                    <div class="card-body">
                    
                        <div class="form-group row {{ $errors->has('service_id') ? 'has-error' : ''}}">
                            <label for="serviceId" class="col-sm-3 col-form-label">Service</label>
                            <div class="col-sm-6">
                                {!! Form::select('service_id', $service, null, ['class' => 'form-control','id'=>'serviceId','placeholder'=>'-Select Service-']) !!}
                                {!! $errors->first('service_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('service_name') ? 'has-error' : ''}}">
                            <label for="serviceName" class="col-sm-3 col-form-label">Service English Name</label>
                            <div class="col-sm-6">
                                {!! Form::text('service_name', null, ['class' => 'form-control','id'=>'serviceName','placeholder'=>'Service English Name']) !!}
                                {!! $errors->first('service_name', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('service_name_ar') ? 'has-error' : ''}}">
                            <label for="serviceNameAr" class="col-sm-3 col-form-label">Service Arabic Name</label>
                            <div class="col-sm-6">
                                {!! Form::text('service_name_ar', null, ['class' => 'form-control','id'=>'serviceNameAr','placeholder'=>'Service Arabic Name']) !!}
                                {!! $errors->first('service_name_ar', '<p class="help-block">:message</p>') !!}
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
                            @if (!empty($serviceList->image))
                                <img src="/service-list-image/{{ $serviceList->image }}" width="50" height="30">
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
    $("#service-list-module").addClass("active");
});

</script>

@endsection