<div class="row">
    <div class="col-xs-6 form-group">
        {!! Form::label('topic_id', 'Topic*', ['class' => 'control-label']) !!}
        {!! Form::select('topic_id[]', $topics, !empty($section) ? $section->topic_id : null, ['class' => 'form-control']) !!}
        <p class="help-block"></p>
        @if($errors->has('topic_id'))
        <p class="help-block">
            {{ $errors->first('topic_id') }}
        </p>
        @endif
    </div>
    <div class="col-xs-4 form-group">
        {!! Form::label('no_of_questions', 'Number of Questions*', ['class' => 'control-label']) !!}
        {!! Form::select('no_of_questions[]', [2 => 2, 3 => 3, 5 => 5, 10 => 10, 15 => 15, 20 => 20], !empty($section) ? $section->question_count : 5, ['class' => 'form-control']) !!}
        <p class="help-block"></p>
        @if($errors->has('no_of_questions'))
        <p class="help-block">
            {{ $errors->first('no_of_questions') }}
        </p>
        @endif
    </div>
    @if($visible)
    <div class="col-xs-2 form-group">
        <span class="btn btn-danger remove">Remove</span>
    </div>
    @endif
</div>