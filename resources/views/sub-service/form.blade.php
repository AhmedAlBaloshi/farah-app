@extends('layout')
@section('title', 'Farah')

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    @if (!empty($subService))
                        Edit Sub Service
                    @else
                        Create Sub Service
                    @endif
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Sub Service List</li>
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
                    @if (!empty($subService))
                        {{ Form::model($subService, ['route' => ['sub_service_update', $subService->sub_service_id], 'method' => 'patch', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) }}
                    @else
                        {{ Form::open(['route' => 'sub_service_store', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
                    @endif

                    <div class="card-body">

                        <div class="form-group row {{ $errors->has('service_list_id') ? 'has-error' : '' }}">
                            <label for="serviceId" class="col-sm-3 col-form-label">Service</label>
                            <div class="col-sm-6">
                                {!! Form::select('service_list_id', $service, null, [
                                    'class' => 'form-control',
                                    'id' => 'serviceId',
                                    'placeholder' => '-Select Service-',
                                ]) !!}
                                {!! $errors->first('service_list_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('sub_service_name') ? 'has-error' : '' }}">
                            <label for="serviceName" class="col-sm-3 col-form-label">Service English Name</label>
                            <div class="col-sm-6">
                                {!! Form::text('sub_service_name', null, [
                                    'class' => 'form-control',
                                    'id' => 'serviceName',
                                    'placeholder' => 'Service English Name',
                                ]) !!}
                                {!! $errors->first('sub_service_name', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('sub_service_name_ar') ? 'has-error' : '' }}">
                            <label for="serviceNameAr" class="col-sm-3 col-form-label">Service Arabic Name</label>
                            <div class="col-sm-6">
                                {!! Form::text('sub_service_name_ar', null, [
                                    'class' => 'form-control',
                                    'id' => 'serviceNameAr',
                                    'placeholder' => 'Service Arabic Name',
                                ]) !!}
                                {!! $errors->first('sub_service_name_ar', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('detail') ? 'has-error' : '' }}">
                            <label for="detail" class="col-sm-3 col-form-label">Detail</label>
                            <div class="col-sm-6">
                                {!! Form::textarea('detail', null, [
                                    'class' => 'form-control',
                                    'id' => 'detail',
                                    'placeholder' => 'Detail',
                                    'rows' => 5,
                                ]) !!}
                                {!! $errors->first('detail', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('start_time') ? 'has-error' : '' }}">
                            <label for="start-time" class="col-sm-3 col-form-label">Start Time</label>
                            <div class="col-sm-6">
                                    {!! Form::time('start_time', null, [
                                    'class' => 'form-control',
                                    'id' => 'start-time',
                                    'value' => '00:00',
                                    'required' => true,
                                ]) !!}
                                {!! $errors->first('start_time', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('end_time') ? 'has-error' : '' }}">
                            <label for="end-time" class="col-sm-3 col-form-label">End Time</label>
                            <div class="col-sm-6">
                                    {!! Form::time('end_time', null, [
                                    'class' => 'form-control',
                                    'id' => 'end-time',
                                    'value' => '00:00',
                                    'required' => true,
                                ]) !!}
                                {!! $errors->first('end_time', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('minutes') ? 'has-error' : '' }}">
                            <label for="minutes" class="col-sm-3 col-form-label">Minutes</label>
                            <div class="col-sm-6">
                                {!! Form::number('minutes', null, [
                                    'class' => 'form-control',
                                    'id' => 'minutes',
                                    'placeholder' => 'Minutes',
                                    'min' => 15,
                                    'required' => true,
                                ]) !!}
                                {!! $errors->first('minutes', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>

                        <div class="form-group row {{ $errors->has('is_active') ? 'has-error' : '' }}">
                            <label for="is_active" class="col-sm-3 col-form-label">Active</label>
                            <div class="col-sm-6">
                                <input type="checkbox" name="is_active"
                                    {{ isset($subService->is_active) && $subService->is_active == 1 ? 'checked' : '' }}
                                    data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                {!! $errors->first('is_active', '<p class="help-block">:message</p>') !!}
                            </div>
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
    <script src="{{ asset('plugins/bootstrap-switch/js/bootstrap-switch.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $("#module").removeClass("menu-close");
            $("#module").addClass("menu-open");
            $("#sub-service-module").addClass("active");
            $("input[data-bootstrap-switch]").each(function() {
                $(this).bootstrapSwitch('state', $(this).prop('checked'));
            });
            $("#start-time").change(function() {
                $('#end-time').attr('min', $(this).val())
            });
        });
    </script>

@endsection
