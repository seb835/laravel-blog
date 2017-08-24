@extends('main')
@section('title','| Posts')
@section('content')
    <div class="row">
        <div class="col-md-10">
            <h1>All Posts</h1>
        </div>

        <div class="col-md-2">
            {!! Html::linkRoute('posts.create', 'Create New Post', array(), array('class'=>'btn btn-lg btn-primary btn-block btn-h1-spacing')) !!}
        </div>

        <div class="col-md-12">
            <hr>
        </div>
    </div><!-- /.row -->

    <div class="row">
        <div class="col-ms-12">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Body</th>
                        <th>Created On</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>{{ $post->title }}</td>
                            <td>{{ substr($post->body,0,50).((strlen($post->body)>50)?'...':'') }}</td>
                            <td>{{ date('d/m/Y H:i',strtotime($post->created_at)) }}</td>
                            <td>
                                {!! Html::linkRoute('posts.show', 'View', array($post->id), array('class'=>'btn btn-lg btn-default')) !!}
                                {!! Html::linkRoute('posts.edit', 'Edit', array($post->id), array('class'=>'btn btn-lg btn-default')) !!}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
