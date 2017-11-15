@extends('layouts.admin')


@section('content')

    <h1>Create Post</h1>

    <!--display errors if there is any-->
    @if(count($errors) > 0)
        <div class="alert alert-danger">
            <ul>

            <!--loop through the errors-->
            @foreach($errors->all() as $error)
                <li>{{$error}}</li>
            @endforeach

            </ul>
        </div>
    @endif

    {!! Form::open(['method'=>'POST', 'action'=>'AdminPostsController@store', 'files'=>true]) !!}

        <div class="form-group">
            {!! Form::label('title', 'Title: ' ) !!}
            {!! Form::text('title', null, ['class'=>'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('category_id', 'Category: ') !!}
            {!! Form::select('category_id', array(''=>'Select Category') + $categories, null, ['class'=>'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('photo_id', 'Upload Photo') !!}
            {!! Form::file('photo_id', null, ['class'=>'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('body', 'Content') !!}
            {!! Form::textarea('body', null, ['class'=>'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::submit('Create Post', ['class'=>'btn btn-primary']) !!}
        </div>

    {!! Form::close() !!}

@stop