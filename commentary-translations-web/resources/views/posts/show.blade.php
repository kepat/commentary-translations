@extends('layouts.main')

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">{{ $post->title }}</h1>
        </div>
    </div>

    @include('elements.message')

    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-edit"></i>
                    Post Details
                </div>
                <div class="panel-body">
                    <div class="row col-lg-6">
                        <div class="col-lg-12">
                            <div class="form-group">
                                {!! Form::label('title') !!}
                                {!! Form::text('title', $post->title, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="form-group">
                                {!! Form::label('Content') !!}
                                {!! Form::textarea('content', $post->content, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <a href="{{ route('posts.index') }}">
                                <button type="button" class="btn btn-primary">Back</button>
                            </a>
                        </div>
                    </div>

                    <div class="row col-lg-6">
                        <div class="col-lg-12">
                            <div class="form-group">
                                {!! Form::label('Language') !!}
                                {!! Form::select('languages', $languages, null, ['class' => 'form-control', 'id' => 'languages']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('Content') !!}
                                {!! Form::textarea('translated_language', null, ['class' => 'form-control', 'disabled' => 'disabled', 'id' => 'translated_language']) !!}
                            </div>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop


@section('script')

    @include('posts.script-show')

@stop