@extends('layout')
@section('title', 'Farah')

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    Edit Term Of Services
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">Term Of Services</li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="height: 61px">
                </div>
                <div class="card-body">
                    {{ Form::model($term, ['route' => ['term.update', $term->id], 'method' => 'put', 'class' => 'form-horizontal']) }}
                    <div class="card-body">
                        <div class="form-group row {{ $errors->has('description') ? 'has-error' : '' }}">
                            <label for="description" class="col-sm-3 col-form-label">Description</label>
                            <div class="col-sm-6">
                                {!! Form::textarea('description', null, [
                                    'class' => 'form-control',
                                    'id' => 'description',
                                    'placeholder' => 'Description',
                                    'rows' => 5,
                                ]) !!}
                                {!! $errors->first('description', '<p class="help-block">:message</p>') !!}
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

    <script>
        $(document).ready(function() {
            $("#module").removeClass("menu-close");
            $("#module").addClass("menu-open");
            $("#term-module").addClass("active");

        });
    </script>

@endsection
