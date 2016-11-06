@extends('layouts.main')

@section('content')

    {!! Form::model($user, ['route' => ['users.update', $user->id], 'method' => 'put']) !!}

    @include('users.form')

    {!! Form::close() !!}

@stop

@section('script')

    @include('users.script')

@stop