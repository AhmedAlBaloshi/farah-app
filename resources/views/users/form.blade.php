@extends('layout')
@section('title', 'Farah')

@section('header')
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>
                    @if (!empty($user))
                        Edit User
                    @else
                        Create User
                    @endif
                </h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active">User</li>
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
                    <a href="{{ route('users.index') }}" class="btn btn-success" style="float: right;">List</a>
                </div>
                <div class="card-body">
                    @if (!empty($user))
                        {{ Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'put', 'enctype' => 'multipart/form-data', 'class' => 'form-horizontal']) }}
                    @else
                        {{ Form::open(['route' => 'users.store', 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
                    @endif

                    <div class="card-body">
                        <div class="form-group row {{ $errors->has('firstname') ? 'has-error' : '' }}">
                            <label for="firstname" class="col-sm-3 col-form-label">First Name</label>
                            <div class="col-sm-6">
                                {!! Form::text('firstname', null, [
                                    'class' => 'form-control',
                                    'id' => 'firstname',
                                    'placeholder' => 'First Name',
                                ]) !!}
                                {!! $errors->first('firstname', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('lastname') ? 'has-error' : '' }}">
                            <label for="lastname" class="col-sm-3 col-form-label">Last Name</label>
                            <div class="col-sm-6">
                                {!! Form::text('lastname', null, [
                                    'class' => 'form-control',
                                    'id' => 'lastname',
                                    'placeholder' => 'Last Name',
                                ]) !!}
                                {!! $errors->first('lastname', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('email') ? 'has-error' : '' }}">
                            <label for="email" class="col-sm-3 col-form-label">Email</label>
                            <div class="col-sm-6">
                                {!! Form::text('email', null, [
                                    'class' => 'form-control',
                                    'id' => 'email',
                                    'readOnly' => empty($user) ? false : true,
                                    'placeholder' => 'Email',
                                ]) !!}
                                {!! $errors->first('email', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        @if (empty($user))
                            <div class="form-group row {{ $errors->has('password') ? 'has-error' : '' }}">
                                <label for="password" class="col-sm-3 col-form-label">Password</label>
                                <div class="col-sm-6">
                                    {!! Form::text('password', null, [
                                        'class' => 'form-control',
                                        'id' => 'password',
                                        'placeholder' => 'Password',
                                    ]) !!}
                                    {!! $errors->first('password', '<p class="help-block">:message</p>') !!}
                                </div>
                            </div>
                        @endIf
                        <div class="form-group row {{ $errors->has('mobile_no') ? 'has-error' : '' }}">
                            <label for="mobile_no" class="col-sm-3 col-form-label">Mobile Number</label>
                            <div class="col-sm-6">
                                {!! Form::number('mobile_no', null, [
                                    'class' => 'form-control',
                                    'id' => 'mobile_no',
                                    'placeholder' => 'Mobile Number',
                                ]) !!}
                                {!! $errors->first('mobile_no', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('profile_image') ? 'has-error' : '' }}">
                            <label for="exampleInputFile" class="col-sm-3 col-form-label">Image</label>

                            <div class="input-group col-sm-6">
                                @if (!empty($user->profile_image))
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('api/profile-image/' . $user->profile_image) }}"
                                            alt="Generic placeholder image"
                                            class="img-fluid rounded-circle border border-dark border-3"
                                            style="width: 60px;height: 60px;">
                                    </div>
                                @endif
                                <div class="custom-file {{ !empty($user->profile_image) ? 'mt-3 mb-3 ml-3' : '' }}">
                                    <input type="file" name="profile_image" class="custom-file-input"
                                        id="exampleInputFile">
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                {!! $errors->first('profile_image', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('address') ? 'has-error' : '' }}">
                            <label for="address" class="col-sm-3 col-form-label">address</label>
                            <div class="col-sm-6">
                                {!! Form::text('address', null, [
                                    'class' => 'form-control',
                                    'id' => 'address',
                                    'placeholder' => 'address',
                                ]) !!}
                                {!! $errors->first('address', '<p class="help-block">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('role_id') ? 'has-error' : '' }}">
                            <label for="roleId" class="col-sm-3 col-form-label">Role</label>
                            <div class="col-sm-6">
                                {!! Form::select('role_id', $roles, null, [
                                    'class' => 'form-control',
                                    'id' => 'roleId',
                                    'placeholder' => '-Select Role-',
                                ]) !!}
                                {!! $errors->first('role_id', '<p class="help-block">:message</p>') !!}
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
            $("#user-module").addClass("active");
        });
    </script>
@endsection
