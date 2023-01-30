@extends('layout')
@section('title', 'Farah')

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    @if (!empty($package))
                        Edit Package
                    @else
                        Create Package
                    @endif
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Package</li>
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
                    <a href="{{ route('packages.index') }}" class="btn btn-success" style="float: right;">List</a>
                </div>
                <div class="card-body">
                    @if (!empty($package))
                        {{ Form::model($package, ['route' => ['packages.update', $package->id], 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) }}
                    @else
                        {{ Form::open(['route' => 'packages.store', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
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
                        <div class="form-group row {{ $errors->has('image') ? 'has-error' : '' }}">
                            <label for="exampleInputFile" class="col-sm-3 col-form-label">Image</label>
                            <div class="input-group col-sm-6">
                                <div class="custom-file">
                                    <input type="file" name="image" class="custom-file-input" id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                {!! $errors->first('image', '<p class="help-block">:message</p>') !!}
                            </div>
                            @if (!empty($package->image))
                                <img src="{{ asset('api/package-image/' . $package->image) }}" width="50"
                                    height="30">
                            @endif
                        </div>
                        {{--   <div class="form-group row {{ $errors->has('product_id') ? 'has-error' : '' }}">
                            <label for="product" class="col-sm-3 col-form-label">Product</label>
                            <div class="col-sm-6">
                                {!! Form::select('product_id', $products, null, [
                                    'class' => 'form-control',
                                    'id' => 'product',
                                    'placeholder' => '-Select Product-',
                                ]) !!}
                                {!! $errors->first('product_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div> --}}
                        <div class="form-group row {{ $errors->has('detail') ? 'has-error' : '' }}">
                            <label for="detail" class="col-sm-3 col-form-label">Detail</label>
                            <div class="col-sm-6">
                                {!! Form::textarea('detail', null, [
                                    'class' => 'form-control',
                                    'id' => 'detail',
                                    'rows' => 3,
                                    'placeholder' => 'Detail',
                                ]) !!}
                                {!! $errors->first('detail', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('amount') ? 'has-error' : '' }}">
                            <label for="amount" class="col-sm-3 col-form-label">Amount</label>
                            <div class="col-sm-6">
                                {!! Form::number('amount', null, [
                                    'class' => 'form-control',
                                    'id' => 'amount',
                                    'placeholder' => 'amount',
                                ]) !!}
                                {!! $errors->first('amount', '<p class="help-block">:message</p>') !!}
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
                    @include('package.addmore')
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
    <script src="{{ asset('plugins/jquery-fieldsaddmore/jquery.fieldsaddmore.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-ui/jquery-ui.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js">
    </script>

    <script>
        $('.admore-custom-fields').fieldsaddmore({
            templateEle: "#fieldsaddmore-template",
            rowEle: ".fieldsaddmore-row",
            addbtn: ".fieldsaddmore-addbtn",
            removebtn: ".fieldsaddmore-removebtn",
            min: ($('.fieldsaddmore-row').length > 0) ? 0 : 1,
            callbackBeforeInit: function(ele, options) {},
            callbackBeforeRemoveClick: function(ele, options) {
                options['min'] = 1;
            },
            callbackAfterAdd: function() {

                $("input[data-bootstrap-switch]").each(function() {
                    $(this).bootstrapSwitch('state', $(this).prop('checked'));
                });

                $('.timepicker').datetimepicker({
                    format: 'H:i',
                    datepicker: false
                });

                $('.datepicker').datetimepicker({
                    format: 'Y-m-d',
                    timepicker: false
                });


            },
            callbackAfterRemoveClick: function(ele, options) {}
        });


        $(document).ready(function() {
            $("#module").removeClass("menu-close");
            $("#module").addClass("menu-open");
            $("#package-module").addClass("active");
            $('#start_date').attr('min', "{{ date('Y-m-d') }}")

            $("#start_date").change(function() {
                $('#end_date').attr('min', $(this).val())
            });
        });
    </script>
@endsection
