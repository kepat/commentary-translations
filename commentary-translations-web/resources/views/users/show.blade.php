@extends('layouts.main')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $user->last_name . ', ' . $user->first_name }}</h1>
        </div>
    </div>

    @include('elements.message')

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-edit"></i>
                    User Details
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! Form::label('first_name') !!}
                                {!! Form::text('first_name', $user->first_name, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! Form::label('last_name') !!}
                                {!! Form::text('last_name', $user->last_name, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! Form::label('username') !!}
                                {!! Form::text('username', $user->username, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! Form::label('email') !!}
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                                    {!! Form::text('email', $user->email, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                {!! Form::label('role_description') !!}
                                {!! Form::text('role_description', $user->role->description, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                        @if($user->driver || $user->customer)
                            <div class="col-lg-12">
                                <div class="form-group">
                                    @if($user->driver)
                                        {!! Form::label('driver_code') !!}
                                        {!! Form::text('driver_code', $user->driver->code, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                    @elseif($user->customer)
                                        {!! Form::label('customer_code') !!}
                                        {!! Form::text('customer_code', $user->customer->code, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                                    @endif
                                   </div>
                            </div>
                        @endif
                        <div class="col-lg-12">
                            <a href="{{ route('users.index') }}">
                                <button type="button" class="btn btn-primary">Back</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop