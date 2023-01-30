@extends('layout')
@section('title', 'Farah')

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    @if (!empty($offer))
                        Edit Offer
                    @else
                        Create Offer
                    @endif
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Offer</li>
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
                    <a href="{{ route('offers.index') }}" class="btn btn-success" style="float: right;">List</a>
                </div>
                <div class="card-body">
                    @if (!empty($offer))
                        {{ Form::model($offer, ['route' => ['offers.update', $offer->id], 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) }}
                    @else
                        {{ Form::open(['route' => 'offers.store', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
                    @endif

                    <div class="card-body">
                        <div class="form-group row {{ $errors->has('title') ? 'has-error' : '' }}">
                            <label for="title" class="col-sm-3 col-form-label">Title</label>
                            <div class="col-sm-6">
                                {!! Form::text('title', null, [
                                    'class' => 'form-control',
                                    'id' => 'title',
                                    'placeholder' => 'Title',
                                ]) !!}
                                {!! $errors->first('title', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('service_id') ? 'has-error' : '' }}">
                            <label for="subService" class="col-sm-3 col-form-label">Sub Service</label>
                            <div class="col-sm-6">
                                {!! Form::select('service_id', $services, null, [
                                    'class' => 'form-control',
                                    'id' => 'subService',
                                    'placeholder' => '-Select Sub Service-',
                                ]) !!}
                                {!! $errors->first('service_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('product_id') ? 'has-error' : '' }}">
                            <label for="product" class="col-sm-3 col-form-label">Product</label>
                            <div class="col-sm-6">
                                {!! Form::select('product_id', $products, null, [
                                    'class' => 'form-control',
                                    'id' => 'product',
                                    'placeholder' => '-Select Product-',
                                ]) !!}
                                {!! $errors->first('product_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('percentage') ? 'has-error' : '' }}">
                            <label for="percentage" class="col-sm-3 col-form-label">Percentage</label>
                            <div class="col-sm-6">
                                {!! Form::number('percentage', null, [
                                    'class' => 'form-control',
                                    'id' => 'percentage',
                                    'placeholder' => 'Percentage',
                                ]) !!}
                                {!! $errors->first('percentage', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('start_date') ? 'has-error' : '' }}">
                            <label for="start_date" class="col-sm-3 col-form-label">Start Date</label>
                            <div class="col-sm-6">
                                {!! Form::date('start_date', null, [
                                    'class' => 'form-control',
                                    'id' => 'start_date',
                                    'min' => '',
                                ]) !!}
                                {!! $errors->first('start_date', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('start_time') ? 'has-error' : '' }}">
                            <label for="start_time" class="col-sm-3 col-form-label"> Start Time</label>
                            <div class="col-sm-6">
                                {!! Form::time('start_time', null, [
                                    'class' => 'form-control',
                                    'id' => 'start_time',
                                ]) !!}
                                {!! $errors->first('start_time', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('end_date') ? 'has-error' : '' }}">
                            <label for="end_date" class="col-sm-3 col-form-label">End Date</label>
                            <div class="col-sm-6">
                                {!! Form::date('end_date', null, [
                                    'class' => 'form-control',
                                    'id' => 'end_date',
                                ]) !!}
                                {!! $errors->first('end_date', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('end_time') ? 'has-error' : '' }}">
                            <label for="end_time" class="col-sm-3 col-form-label">End Time</label>
                            <div class="col-sm-6">
                                {!! Form::time('end_time', null, [
                                    'class' => 'form-control',
                                    'id' => 'end_time',
                                ]) !!}
                                {!! $errors->first('end_time', '<p class="help-block">:message</p>') !!}
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
            $("#offer-module").addClass("active");
            $('#start_date').attr('min', "{{ date('Y-m-d') }}")

            $("#start_date").change(function() {
                $('#end_date').attr('min', $(this).val())
            });
        });
    </script>
@endsection
