@extends('main')
@section('title','| Edit Blog Post')
@section('stylesheets')
    {!! Html::style('css/select2.min.css') !!}
@endsection
@section('content')
    <div class="row">
        {!! Form::model($post, ['route' => ['posts.update',  $post->id], 'method' => 'PUT', 'files' => true]) !!}
        <div class="col-md-8">
            {{ Form::label('title', 'Title:') }}
            {{ Form::text('title', null, ['class' => 'form-control input-lg']) }}

            {{ Form::label('slug', 'Slug:', ['class' => 'form-spacing-top']) }}
            {{ Form::text('slug', null, ['class' => 'form-control']) }}

            {{ Form::label('category_id', 'Category:', ['class' => 'form-spacing-top']) }}
            {{ Form::select('category_id', $categories, $post->category_id, ['class' => 'form-control']) }}

            {{ Form::label('tags', 'Tags:', ['class' => 'form-spacing-top']) }}
            {{ Form::select('tags[]', $tags, null, ['class' => 'form-control select2-multi', 'multiple' => 'multiple']) }}

            {{ Form::label('featured#', 'Update featured image:', ['class' => 'form-spacing-top']) }}
            {{ Form::file('featured_image') }}

            {{ Form::label('body', 'Body:', ['class' => 'form-spacing-top']) }}
            {{ Form::textarea('body', null, ['class' => 'form-control']) }}
        </div>

        <div class="col-md-4">
            <div class="well">
                <dl class="dl-horizontal">
                    <dt>Created On:</dt>
                    <dd>{{ date('d/m/Y g:ia',strtotime($post->created_at)) }}</dd>
                </dl>

                <dl class="dl-horizontal">
                    <dt>Last Updated:</dt>
                    <dd>{{ date('d/m/Y g:ia',strtotime($post->updated_at)) }}</dd>
                </dl>
                <hr>

                <div class="row">
                    <div class="col-sm-6">
                        {!! Html::linkRoute('posts.show', 'Cancel', array($post->id), array('class'=>'btn btn-danger btn-block')) !!}
                    </div>
                    <div class="col-sm-6">
                        {{ Form::submit('Save Changes', array('class' => 'btn btn-success btn-block')) }}
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div><!-- /.row -->
@endsection

@section('scripts')
    {!! Html::script('js/select2.min.js') !!}
    {!! Html::script('//cdn.ckeditor.com/4.7.2/standard/ckeditor.js') !!}

    <script type="text/javascript">
        $(".select2-multi").select2();
        CKEDITOR.replace('body');
    </script>
@endsection
