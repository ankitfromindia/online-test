@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.questions.title')</h3>

    <p>
        <a href="{{ route('questions.create') }}" class="btn btn-success">@lang('quickadmin.add_new')</a>
    </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.list')
        </div>

        <div class="panel-body">
            <div class="row" align="left">
                {!! Form::label('topic_id', 'Topic*', ['class' => 'control-label']) !!}
            </div>
                <div class="col-xs-12 form-group" align="left">
                    
                    {!! Form::select('topic_id', $topics, old('topic_id'), ['class' => 'form-control','style'=>'width:128px;margin-left: 36px;margin-top: -29px;']) !!}
                    <p class="help-block"></p>
                    @if($errors->has('topic_id'))
                        <p class="help-block">
                            {{ $errors->first('topic_id') }}
                        </p>
                    @endif
                </div>
            <table style="margin-top: -51px;" class="table table-bordered table-striped {{ count($questions) > 0 ? 'datatable' : '' }} dt-select">
                <thead>
                    <tr>
                        <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        <th>@lang('quickadmin.questions.fields.topic')</th>
                        <th>@lang('quickadmin.questions.fields.question-text')</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($questions) > 0)
                        @foreach ($questions as $question)
                            <tr data-entry-id="{{ $question->id }}">
                                <td></td>
                                <td>{{ $question->topic->title or '' }}</td>
                                <td>{!! $question->question_text !!}</td>
                                <td>
                                    <a href="{{ route('questions.show',[$question->id]) }}" class="btn btn-xs btn-primary">@lang('quickadmin.view')</a>
                                    <a href="{{ route('questions.edit',[$question->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.edit')</a>
                                    {!! Form::open(array(
                                        'style' => 'display: inline-block;',
                                        'method' => 'DELETE',
                                        'onsubmit' => "return confirm('".trans("quickadmin.are_you_sure")."');",
                                        'route' => ['questions.destroy', $question->id])) !!}
                                    {!! Form::submit(trans('quickadmin.delete'), array('class' => 'btn btn-xs btn-danger')) !!}
                                    {!! Form::close() !!}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7">@lang('quickadmin.no_entries_in_table')</td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('javascript')
<style>
.dataTables_filter {
  margin-top: -46px;

} 
</style>
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('questions.mass_destroy') }}';
    </script>
    <script>

        $("#topic_id").on('change', function(e) {

            window.location="/topic_show?topic_id="+this.value
        });

    </script>
@endsection