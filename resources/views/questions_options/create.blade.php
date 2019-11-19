@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.questions-options.title')</h3>
    {!! Form::open(['method' => 'POST', 'route' => ['questions_options.store']]) !!}

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.create')
        </div>
        <?php   $url_segment = \Request::segment(3); ?>
        <div class="panel-body">
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('question_id', 'question*', ['class' => 'control-label']) !!}

                    <select class="form-control" id="question_id" name="question_id">
                          @foreach ($questions as $k=>$v)
                          @if($k == $url_segment)
                                <option value="{{ $k }}" {{ ( $k == $url_segment) ? 'selected' : ''}}> {{ $v }} 
                          </option>
                          @endif
                            
                            @endforeach  
                    </select>
                    {{-- Form::select('question_id', $questions, old($url_segment), ['class' => 'form-control']) --}}
                    <p class="help-block"></p>
                    @if($errors->has('question_id'))
                        <p class="help-block">
                            {{ $errors->first('question_id') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('option', 'Option*', ['class' => 'control-label']) !!}
                    {!! Form::text('option', old('option'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('option'))
                        <p class="help-block">
                            {{ $errors->first('option') }}
                        </p>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 form-group">
                    {!! Form::label('correct', 'Correct', ['class' => 'control-label']) !!}
                    {!! Form::hidden('correct', 0) !!}
                    {!! Form::checkbox('correct', 1, 0, ['class' => 'form-control']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('correct'))
                        <p class="help-block">
                            {{ $errors->first('correct') }}
                        </p>
                    @endif
                </div>
            </div>
            
        </div>
    </div>

    {!! Form::submit(trans('quickadmin.save'), ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

