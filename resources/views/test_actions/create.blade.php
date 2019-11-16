@extends('layouts.app')

@section('content')
    <h3 class="page-title">@lang('quickadmin.laravel-quiz')</h3>

{{ Form::open(array('url' => route('test_actions.next_action'), 'action' => 'POST')) }}
    
    

<?php
//$message = session('timer_out');
//$ldate = date('H:i:s');
//dd($timer);?>
    <div class="panel panel-default">
        <table style="border:0px; margin-left: 80%">
                                <tr>
                                    <td style="width:60px;text-align:center;">Minutes</td>
                                    <td style="width:70px;text-align:center;">Seconds</td>
                                </tr>
                                <tr>
                                    <td colspan="4"><span id="h_timer"></span></td>
                                </tr>
                            </table>
        <?php //dd($code) ?>
    <input type="hidden" name="code" id="code" value='{{$code}}' >
    <input type="hidden" name="currentQuestionNumber" value='{{$currentQuestionNumber}}' >
        <div class="panel-body">
        <div class="row">
            <div class="col-xs-12 form-group">
                <div class="form-group">
                    <strong>Question {{ $currentQuestionNumber }}.<br />{!! nl2br($question->question_text) !!}</strong>

                    @if ($question->code_snippet != '')
                        <div class="code_snippet">{!! $question->code_snippet !!}</div>
                    @endif

                    <input
                        type="hidden"
                        name="question_id"
                        value="{{ $question->id }}" id='qid'>
                @foreach($question->options as $option)
                    <br>
                    <label class="radio-inline">
                        <input
                            type="radio"
                            name="answer"
                            value="{{ $option->id }}">
                        {{ $option->option }}
                    </label>
                @endforeach
                </div>
            </div>
        </div>
        <td></td>
        </div>
    
    </div>

    {!! Form::submit(trans('quickadmin.submit_quiz'),['name'=> 'submit'], ['class' => 'btn btn-danger']) !!}
    {!! Form::close() !!}
@stop

@section('javascript')
    @parent
    <script src="{{ url('quickadmin/js') }}/timepicker.js"></script>
    <script type="text/javascript" src="{{ url('quickadmin/js') }}/jquery-2.0.3.js"></script>
<script type="text/javascript" src="{{ url('quickadmin/js') }}/jquery.countdownTimer.js"></script>
<link rel="stylesheet" type="text/css" href="{{ url('quickadmin/css') }}/jquery.countdownTimer.css" />
    <script src="https://cdnjs.cloudflare.com//ajax/libs/jquery-ui-timepicker-addon/1.4.5/jquery-ui-timepicker-addon.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.2.0/js/dataTables.select.min.js"></script>
    <script type="text/javascript">
         //window.location.reload();    
         //sessionStorage.clear()
function abc(id) {
  //event.preventDefault();
  var href = document.getElementById("qid");
  alert(href);
  //var href = event.currentTarget.getAttribute('fmid')
  window.location='http://www.shopeeon.com?ver=' + href;
}
         var form = document.getElementById("testsub");

         document.getElementsByClassName("page-link").addEventListener("click", function () {
         form.submit();
});
    </script>
    <script>
        $('.datetime').datetimepicker({
            autoclose: true,
            dateFormat: "{{ config('app.date_format_js') }}",
            timeFormat: "hh:mm:ss"
        });
    </script>

    <script>
        $(function(){
            $('#h_timer').countdowntimer({
                minutes :{{$counterNumber}},
                size : "lg",
                expiryUrl : "{{ config('app.url') }}/expired/"+$("#code").val()
            });
        });
        // TIMEUP
        // //timeUp
        // opts.expiryUrl
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
