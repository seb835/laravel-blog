@extends('main')
@section('title', '| Edit Comment')

@section('content')

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <h1>Edit Comment</h1>

            {{ Form::model($comment, ['route' => ['comments.update', $comment->id], 'method' => 'PUT']) }}

            {{ Form::label('name', 'Name:', ['class' => 'form-spacing-top']) }}
            {{ Form::text('name', null, ['class' => 'form-control', 'disabled' => 'disabled']) }}

            {{ Form::label('email', 'E-Mail:', ['class' => 'form-spacing-top']) }}
            {{ Form::text('email', null, ['class' => 'form-control', 'disabled' => 'disabled']) }}

            {{ Form::label('comment', 'Comment:', ['class' => 'form-spacing-top']) }}
            {{ Form::textarea('comment', null, ['class' => 'form-control']) }}

            {{ Form::submit('Update Comment', ['class' => 'btn btn-block btn-success btn-h1-spacing']) }}

            {{ Form::close() }}
        </div>
    </div>

@endsection
