@extends('layout')
@section('title', 'Farah')

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/jquery-ui/jquery-ui.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/jquery-datetimepicker/jquery.datetimepicker.css') }}">
@endsection

@section('header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1> @if(!empty($product)) Edit Product @else Create Product @endif </h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Product</li>
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
                <a href="{{ route('product_index') }}" class="btn btn-success" style="float: right;">List</a>
            </div>
            <div class="card-body">
                    @if(!empty($product))
                        {{ Form::model($product, ['route' => ['product_update', $product->product_id], 'method' => 'patch','enctype'=>'multipart/form-data','class'=>'form-horizontal']) }}
                    @else
                        {{ Form::open(['route' => 'product_store','class'=>'form-horizontal','enctype'=>'multipart/form-data']) }}
                    @endif

                    <div class="card-body">
                        <div class="form-group row {{ $errors->has('service_id') ? 'has-error' : ''}}">
                            <label for="serviceId" class="col-sm-3 col-form-label">Service</label>
                            <div class="col-sm-6">
                                {!! Form::select('service_id', [''=>'-Select Service-']+$service, null, ['class' => 'form-control','id'=>'serviceId']) !!}
                                {!! $errors->first('service_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('service_list_id') ? 'has-error' : ''}}">
                            <label for="serviceListId" class="col-sm-3 col-form-label">Service List</label>
                            <div class="col-sm-6">
                                {!! Form::select('service_list_id', [''=>'-Select Service List-']+(!empty($serviceList) ? $serviceList :[]), null, ['class' => 'form-control','id'=>'serviceListId']) !!}
                                {!! $errors->first('service_list_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('sub_service_id') ? 'has-error' : ''}}">
                            <label for="subServiceId" class="col-sm-3 col-form-label">Service Category List</label>
                            <div class="col-sm-6">
                                {!! Form::select('sub_service_id', [''=>'-Select Service Category List-']+(!empty($subServiceList) ? $subServiceList :[]), null, ['class' => 'form-control','id'=>'subServiceId']) !!}
                                {!! $errors->first('sub_service_id', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('product_name') ? 'has-error' : ''}}">
                            <label for="productName" class="col-sm-3 col-form-label">Product English Name</label>
                            <div class="col-sm-6">
                                {!! Form::text('product_name', null, ['class' => 'form-control','id'=>'productName','placeholder'=>'Product English Name']) !!}
                                {!! $errors->first('product_name', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('product_name_ar') ? 'has-error' : ''}}">
                            <label for="productNameAr" class="col-sm-3 col-form-label">Product Arabic Name</label>
                            <div class="col-sm-6">
                                {!! Form::text('product_name_ar', null, ['class' => 'form-control','id'=>'productNameAr','placeholder'=>'Product Arabic Name']) !!}
                                {!! $errors->first('product_name_ar', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('address') ? 'has-error' : ''}}">
                            <label for="productAddress" class="col-sm-3 col-form-label">English Address</label>
                            <div class="col-sm-6">
                                {!! Form::text('address', null, ['class' => 'form-control','id'=>'productAddress','placeholder'=>'English Address']) !!}
                                {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('address_ar') ? 'has-error' : ''}}">
                            <label for="productAddressAr" class="col-sm-3 col-form-label">Arabic Address</label>
                            <div class="col-sm-6">
                                {!! Form::text('address_ar', null, ['class' => 'form-control','id'=>'productAddressAr','placeholder'=>'Arabic Address']) !!}
                                {!! $errors->first('address_ar', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('latitude') ? 'has-error' : ''}}">
                            <label for="latitude" class="col-sm-3 col-form-label">Latitude</label>
                            <div class="col-sm-6">
                                {!! Form::text('latitude', null, ['class' => 'form-control','id'=>'latitude','placeholder'=>'Latitude']) !!}
                                {!! $errors->first('latitude', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('longitude') ? 'has-error' : ''}}">
                            <label for="longitude" class="col-sm-3 col-form-label">Longitude</label>
                            <div class="col-sm-6">
                                {!! Form::text('longitude', null, ['class' => 'form-control','id'=>'longitude','placeholder'=>'Longitude']) !!}
                                {!! $errors->first('longitude', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('description') ? 'has-error' : ''}}">
                            <label for="description" class="col-sm-3 col-form-label">English Description</label>
                            <div class="col-sm-6">
                                {!! Form::textarea('description', null, ['id' => 'description','class' => 'form-control', 'rows' => 2, 'cols' => 54, 'style' => 'resize:none','placeholder'=>'English Description']) !!}
                                {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('description_ar') ? 'has-error' : ''}}">
                            <label for="description_ar" class="col-sm-3 col-form-label">Arabic Description</label>
                            <div class="col-sm-6">
                                {!! Form::textarea('description_ar', null, ['id' => 'description_ar','class' => 'form-control', 'rows' => 2, 'cols' => 54, 'style' => 'resize:none','placeholder'=>'Arabic Description']) !!}
                                {!! $errors->first('description_ar', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('rate') ? 'has-error' : ''}}">
                            <label for="rate" class="col-sm-3 col-form-label">Rate</label>
                            <div class="col-sm-6">
                                {!! Form::text('rate', null, ['class' => 'form-control','id'=>'rate','placeholder'=>'Rate']) !!}
                                {!! $errors->first('rate', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('is_active') ? 'has-error' : ''}}">
                            <label for="is_active" class="col-sm-3 col-form-label">Active</label>
                            <div class="col-sm-6">
                                <input type="checkbox" name="is_active" {{ (isset($product->is_active) && $product->is_active == 1) ? 'checked' : ""}} data-bootstrap-switch data-off-color="danger" data-on-color="success">
                                {!! $errors->first('is_active', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                    </div>

                    @include('product.addmore')

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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js"></script>

<script>
    
var SITEURL = '{{ URL::to('') }}';

$(document).ready(function() {
    
    $("#module").removeClass("menu-close");
    $("#module").addClass("menu-open");
    $("#product-module").addClass("active");

    $("input[data-bootstrap-switch]").each(function(){
        $(this).bootstrapSwitch('state', $(this).prop('checked'));
    });
});

$('.admore-custom-fields').fieldsaddmore({
        templateEle: "#fieldsaddmore-template",
        rowEle: ".fieldsaddmore-row",
        addbtn: ".fieldsaddmore-addbtn",
        removebtn: ".fieldsaddmore-removebtn",
        min:($('.fieldsaddmore-row').length>0)?0:1,
        callbackBeforeInit: function(ele, options) {
        },
        callbackBeforeRemoveClick: function(ele, options) {
            options['min'] = 1;
        },
        callbackAfterAdd:function() {

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
        callbackAfterRemoveClick: function(ele, options) {
        }
    }
);

jQuery(document).on('change','#serviceId',function() {
    var url = SITEURL+"/get_service_list";
    var id  = jQuery(this).val();
    jQuery("select[id='serviceListId']").find("option").not(':first').remove();
    jQuery("select[id='subServiceId']").find("option").not(':first').remove();
    if(id != '') {
        jQuery.ajax({
            type: "GET",
            url: url,
            data: {service_id:id},
            success: function(data) {
                jQuery("select[id='serviceListId']").find("option:not(:first)").remove();
                jQuery.each(data, function(key,value) {
                    jQuery("select[id='serviceListId']").append('<option value="'+key+'">'+value+'</option>');
                });
            },
        });
    }
});

jQuery(document).on('change','#serviceListId',function() {
    var url = SITEURL+"/get_sub_service_list";
    var id  = jQuery(this).val();
    jQuery("select[id='subServiceId']").find("option").not(':first').remove();
    if(id != '') {
        jQuery.ajax({
            type: "GET",
            url: url,
            data: {service_list_id:id},
            success: function(data) {
                jQuery("select[id='subServiceId']").find("option:not(:first)").remove();
                jQuery.each(data, function(key,value) {
                    jQuery("select[id='subServiceId']").append('<option value="'+key+'">'+value+'</option>');
                });
            },
        });
    }
});

</script>

@endsection