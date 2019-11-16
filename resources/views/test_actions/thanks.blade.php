@extends('layouts.app')

@section('content')

    <h3 class="page-title">@lang('quickadmin.laravel-quiz')</h3>
   

    <div class="panel panel-default">
        
 <h2>Thanks, You test submit successfully</h2>
    </div>

 
@stop

@section('javascript')
    @parent
    <script src="{{ url('quickadmin/js') }}/timepicker.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
    <script>
        $('.datetime').datetimepicker({
            autoclose: true,
            dateFormat: "{{ config('app.date_format_js') }}",
            timeFormat: "hh:mm:ss"
        });
    </script>
    <script type="text/javascript">

        $(document).ready(function() {

        window.history.pushState(null, "", window.location.href);        

        window.onpopstate = function() {

        window.history.pushState(null, "", window.location.href);

        };

        });

    </script>    

@stop
