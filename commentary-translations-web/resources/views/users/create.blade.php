@extends('layouts.main')

@section('content')

    {!! Form::open(['route' => 'users.store']) !!}

    @include('users.form')

    {!! Form::close() !!}

@stop

@section('script')

    @include('users.script')

@stop