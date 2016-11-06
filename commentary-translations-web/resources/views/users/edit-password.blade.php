@extends('layouts.main')

@section('content')

    {!! Form::model($user, ['route' => ['users.update-password', $user->id], 'method' => 'put']) !!}

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $user->last_name . ', ' . $user->first_name }}</h1>
            <input name="edit" id="edit" type="hidden" value="true">
        </div>
    </div>

    @include('elements.message')

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-edit"></i>
                    User - Change Password
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! Form::label('password') !!}
                                {!! Form::password('password', ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                {!! Form::label('password_confirmation') !!}
                                {!! Form::password('password_confirmation', ['class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {!! Form::close() !!}

@stop