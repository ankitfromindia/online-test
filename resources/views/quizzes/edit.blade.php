@extends('layouts.app')

@section('content')
<h3 class="page-title">Quiz Template</h3>

{!! Form::model($quiz, ['method' => 'PUT', 'route' => ['quizzes.update', $quiz->id]]) !!}

<div class="panel panel-default">
    <div class="panel-heading">
        @lang('quickadmin.edit')
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-xs-4 form-group">
                {!! Form::label('category', 'Category*', ['class' => 'control-label']) !!}
                {!! Form::text('category', old('category'), ['class' => 'form-control']) !!}
                <p class="help-block"></p>
                @if($errors->has('category'))
                <p class="help-block">
                    {{ $errors->first('category') }}
                </p>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-xs-4 form-group">
                {!! Form::label('test_time', 'Time*', ['class' => 'control-label']) !!}
                {!! Form::text('test_time', old('test_time'), ['class' => 'form-control']) !!}
                <p class="help-block"></p>
            </div>
        </div>        
        <fieldset>
            <legend>Sections <span class="align-reverse btn btn-warning" id="add_more">Add More</span></legend>
            @foreach($sections as $section)
            @include('quizzes.section', compact($section))
            @endforeach
            <div id="section"></div>

        </fieldset>

    </div>
</div>

{!! Form::submit(trans('quickadmin.update'), ['class' => 'btn btn-danger']) !!}
{!! Form::close() !!}
@stop

@section('javascript')
<script>
    $(document).ready(function(){
       $('#add_more').click(function(){
          $.get('/quizzes/section/add', function(html){
              $('#section').append(html);
          });
       });
    });
    $(document).on('click', '.remove', function(){
       $(this).closest('.row').remove();
    });
</script>
@stop