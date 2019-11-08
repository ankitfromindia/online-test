@extends('layouts.app')

@section('content')
    <h3 class="page-title">Quiz Management</h3>

    <p>
        <a href="{{ route('quizzes.create') }}" class="btn btn-success">@lang('quickadmin.add_new')</a>
    </p>

    <div class="panel panel-default">
        <div class="panel-heading">
            @lang('quickadmin.list')
        </div>

        <div class="panel-body">
            <table class="table table-bordered table-striped {{ count($quiz) > 0 ? 'datatable' : '' }} dt-select">
                <thead>
                    <tr>
                        <th style="text-align:center;"><input type="checkbox" id="select-all" /></th>
                        <th>Category</th>
                        <th>No. of Questions</th>
                        <th>Share</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                
                <tbody>
                    @if (count($quiz) > 0)
                        @foreach ($quiz as $q)
                            <tr data-entry-id="{{ $q->id }}">
                                <td></td>
                                <td>{{ $q->category}}</td>
                                <td>{{ $q->total_questions}}</td>
                                <td><a href="{{url('/tests/' . $q->code)}}">{{url('/tests/' . $q->code)}}</a></td>
                                <td>
                                    <a href="{{ route('quizzes.edit',[$q->id]) }}" class="btn btn-xs btn-info">@lang('quickadmin.edit')</a>
                                    <a href="{{ route('quizzes.clone',[$q->id]) }}" class="btn btn-xs btn-info">Clone</a>
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
    <script>
        window.route_mass_crud_entries_destroy = '{{ route('questions.mass_destroy') }}';
    </script>
@endsection