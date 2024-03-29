<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Test;
use App\TestAnswer;
use App\StartQuiz;
use App\Topic;
use App\Question;
use App\QuestionsOption;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTestRequest;
use Carbon\Carbon;

class TestActionsController extends Controller
{
    /**
     * Display a new test.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $code = null)
    {
        return view('test_actions.start', compact('code'));
    }

    /**
     * Display a new test.
     *
     * @return \Illuminate\Http\Response
     */
    public function start(Request $request, $code = null)
    {
   
        // Get quiz details
        // Get quiz sections
//        $code => 'asdasdsadsada'
       // echo "<pre>";
        session()->forget('test_id');
        session()->forget('current_question');
        session()->forget('exam_start_time');
        $quiz = \App\Quiz::where('code', $code)->firstOrFail();
        $questionSections = \App\QuizSection::where('quiz_id', $quiz->id)->get();
        $totalQuestions =0;
        $qIds = [];
        foreach($questionSections as $questionSection) {
            $topicId = $questionSection->topic_id;
            $topicQuestions = \App\Question::where(['topic_id'=>$topicId])->select('id')->get()->toArray();
            if(count($topicQuestions)) {
                shuffle($topicQuestions);
                //$qIds[$topicId] = array_slice($topicQuestions, 0, $questionSection->question_count);
                array_push($qIds, array_slice($topicQuestions, 0, $questionSection->question_count));
                $totalQuestions = $totalQuestions+$questionSection->question_count;
            }
        }
        $totalQuestionsCount = [];

        //print_r($totalQuestions);
        $qRows = [];
        foreach ($qIds as $qId) {
            foreach($qId as $a) {
                $b = [];
                $b['user_id']= Auth::id();
                $b['quiz_id']= $quiz->id;
                $b['question_id']= $a['id'];
                array_push($qRows, $b);
            }
        }

        \App\QuizQuestion::insert($qRows);
        return redirect()->route('test_actions.next', ['code' => $code]);
        //    print_r($qRows);
        // exit();

        // StartQuiz::create([
        //     'user_id'     => Auth::id(),
        //     'code'     => $code,
        //     'no_of_question' => 2,
        // ]);        
        // $quiz = \App\Quiz::where('code', $code)->firstOrFail();
        // $question_count = \App\QuizSection::where('quiz_id', $quiz->id)->firstOrFail();
        
        // $timer=array();
        // $timer = $request->session()->put('timer_out', $quiz->test_time);
        // $session_id = session()->getId();
        // //Session::set('timer_out',$quiz->test_time );
        // $questions = Question::join('quiz_questions', 'questions.id', '=', 'quiz_questions.question_id')
        //         ->where('quiz_id', $quiz->id)
        //         //->get();
        //         ->simplePaginate(1);

        // foreach ($questions as &$question) {
        //     $question->options = QuestionsOption::where('question_id', $question->question_id)->inRandomOrder()->get();
        //     //return view('test_actions.create', compact('question','quiz','question_count'));
        //     return redirect()->route('test_actions.next', ['id' => 1, 'code' => $code]);
        // }

        //return view('tests.create', compact('questions','quiz'));
    }    

    public function next($code){

        $quiz = \App\Quiz::where('code', $code)->firstOrFail();
        $questionSections = \App\QuizSection::where('quiz_id', $quiz->id)->get()->toArray();
        $totalQuestionsToPick = 0;
        foreach($questionSections as $questionSection) {
            $totalQuestionsToPick = $totalQuestionsToPick+$questionSection['question_count'];
        }

        $questionQuestions = \App\QuizQuestion::where(['quiz_id'=> $quiz->id])->orderby('id', 'desc')->limit($totalQuestionsToPick)->get()->toArray();


        $question_count =$totalQuestionsToPick;
        if(session('current_question') > $totalQuestionsToPick) {
            \App\User::where(['id'=>Auth::id()])->update(['status' => 0]);
            session()->forget('test_id');
            session()->forget('current_question');
            session()->forget('exam_start_time');
            \Auth::logout();
            return view('test_actions.thanks');
        }

        if(is_null(session('current_question'))) {
            session(['current_question'=> '1']);
        }


        if(is_null(session('exam_start_time'))) {
            session(['exam_start_time'=> date('Y-m-d H:i:s')]);
        }

$currentTime =  date('Y-m-d H:i:s');
//print_r($currentTime);
//echo "<br>";
//display the converted time
$startTime = session('exam_start_time');
//print_r($startTime);
//echo "</br>";




//strtotime(date('Y-m-d H:i:s'))
$endTime =  date('Y-m-d H:i:s', strtotime('+' .$quiz->test_time. 'minutes', strtotime($startTime)));
//print_r($endTime);
//echo "</br>";

$timeRemaining = (strtotime($endTime) - strtotime($currentTime));



$counterNumber = ceil($timeRemaining/60) -1;
$counterNumberMinutes = explode(".",number_format($timeRemaining/60,2))[0];
$counterNumberSeconds = explode(".",number_format($timeRemaining/60,2))[1];


// print_r(number_format($timeRemaining/60,2));
// echo "</br>";
// print_r($counterNumberMinutes);
// echo "</br>";
// print_r($counterNumberSeconds);
// echo "</br>";




// //  $date = "2016-09-16 11:00:00";
//   $cdatework = new Carbon($currentTime);
//   $edatework = new Carbon($endTime);
// //  $edatework = Carbon::createFromDate($endTime);
//   //$now = Carbon::now();
// //  $testdate = $edatework->diffInDays($sdatework);
// //print_r($sdatework);
// //print_r($cdatework);
// $diff = $edatework->diffInMinutes($cdatework);
// //print_r($diff);
// //echo "<br>";
// $diffs = $edatework->diffInSeconds($cdatework);

// print_r($diffs->format('%h.%i'));
//echo round(abs($timeRemaining) / 60,2). " minute";

$start_date = new \DateTime($currentTime);
$end_date = new \DateTime($endTime);
$interval = $start_date->diff($end_date);
$hours   = $interval->format('%h'); 
$counterNumberMinutes = $interval->format('%i');
$counterNumberSeconds = $interval->format('%s');
// echo  'Diff. in minutes is: '.($hours * 60 + $minutes);
// echo  'Diff. in seconds is: '.($seconds);
// $counterNumberMinutes = explode(".",number_format($timeRemaining/60,2))[0];
// $counterNumberSeconds = explode(".",number_format($timeRemaining/60,2))[1];

//$timeRemaining = 180;
//print_r();

//echo "adasdsasa";print_r(session('exam_start_time'));
//$timeRemaining = (strtotime($endTime) - $startTime);
//print_r($timeRemaining);
        // Set start time in sesssion if not exists
        // Subtraacxt test_time from start time and set this time in session
//print_r($quiz->test_time);
//exit();
        //$request->session()->put('timer_out', $quiz->test_time)

        $currentQuestionNumber = session('current_question');
        $questionId = $questionQuestions[$currentQuestionNumber-1]['question_id'];
        //print_r($questionId);exit();
        $question = \App\Question::where('id', $questionId)->get()->first();        
        return view('test_actions.create', compact('question','quiz','question_count','code', 'currentQuestionNumber', 'counterNumber', 'counterNumberMinutes', 'counterNumberSeconds'));
    }

    public function nextAction(Request $request){
        // print_r($request->all());
        //Array ( 
        // [_token] => otxaaD1ac2R5W4ZZfbvekPaxsbEvYsrbaakiOCTR 
        // [code] => d1a62bebadf43fce4f8ef07b1d13249d 
        // [currentQuestionNumber] => 1 
        // [question] => 1 
        // [answers] => Array ( [1] => 1 ) 
        // [submit] => Submit answers 
        // )
        $result = 0;
        $code = $request->code;

        if(is_null(session('test_id'))) {
            $test = Test::create([
                'user_id' => Auth::id(),
                'result'  => $result,
            ]);
            session(['test_id'=> $test->id]);
        }
        
        $status = 0;
        $qOptions = \App\QuestionsOption::where(['question_id' => $request->input('question_id')])->get()->toArray();
        $correctAnswerId = 1;
        foreach($qOptions as $qOption) {
            if(($qOption['id'] == $request->input('answer')) && ($qOption['correct'] == 1)) {
                $status = 1;
                \App\Test::where(['id'=>session('test_id'), 'user_id' => Auth::id()])->increment('result');
            }
        }

        TestAnswer::create([
            'user_id'     => Auth::id(),
            'test_id'     => session('test_id'),
            'question_id' => $request->input('question_id'),
            'option_id'   => $request->input('answer'),
            'correct'     => $status,
        ]);
        session(['current_question'=> $request->input('currentQuestionNumber')+1]);
        return redirect()->route('test_actions.next', ['code' => $request->input('code')]);
    }

    public function sessionExpired()
    {
        session()->forget('test_id');
        session()->forget('current_question');
        session()->forget('exam_start_time');
        \App\User::where(['id'=>Auth::id()])->update(['status' => 0]);
        \Auth::logout();
        //return redirect()->route('test_actions/session_expired');
        return view('test_actions.session_expired', ['message' => 'Your session is expired.']);
    }

    public function nextOld(Request $request, $code = null){
         $result = 0;
        $code = $request->code;
        $test = Test::create([
            'user_id' => Auth::id(),
            'result'  => $result,
        ]);
        
        foreach ($request->input('questions', []) as $key => $question) {
            $status = 0;

            if ($request->input('answers.'.$question) != null
                && QuestionsOption::find($request->input('answers.'.$question))->correct
            ) {
                $status = 1;
                $result++;
            }
            TestAnswer::create([
                'user_id'     => Auth::id(),
                'test_id'     => $test->id,
                'question_id' => $question,
                'option_id'   => $request->input('answers.'.$question),
                'correct'     => $status,
            ]);
        }
        $quiz = \App\Quiz::where('code', $code)->firstOrFail();
        $question_count = \App\QuizSection::where('quiz_id', $quiz->id)->firstOrFail();
        
        $timer=array();
        $timer = $request->session()->put('timer_out', $quiz->test_time);
        $session_id = session()->getId();
        //Session::set('timer_out',$quiz->test_time );
        $questions = Question::join('quiz_questions', 'questions.id', '=', 'quiz_questions.question_id')
                ->where('quiz_id', $quiz->id)
                //->get();
                ->simplePaginate(1);
//return view('test_actions.create', compact('question','quiz','question_count','code'));
        foreach ($questions as &$question) {
            $question->options = QuestionsOption::where('question_id', $question->question_id)->inRandomOrder()->get();
            return view('test_actions.create', compact('question','quiz','question_count','code'));
            //return redirect()->route('test_actions.start', $question->question_id);
        }
}

    /**
     * Store a newly solved Test in storage with results.
     *
     * @param  \App\Http\Requests\StoreResultsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //print_r($request->all());exit;
        $result = 0;
        $code = $request->code;
        $test = Test::create([
            'user_id' => Auth::id(),
            'result'  => $result,
        ]);
        
        foreach ($request->input('questions', []) as $key => $question) {
            $status = 0;

            if ($request->input('answers.'.$question) != null
                && QuestionsOption::find($request->input('answers.'.$question))->correct
            ) {
                $status = 1;
                $result++;
            }
            TestAnswer::create([
                'user_id'     => Auth::id(),
                'test_id'     => $test->id,
                'question_id' => $question,
                'option_id'   => $request->input('answers.'.$question),
                'correct'     => $status,
            ]);
        }
//echo $test->id;exit;
        return redirect()->route('test_actions.next', compact('code'));
        //return redirect()->route('tests.thanks', [$test->id]);
    }

    /**
     * Display a new test.
     *
     * @return \Illuminate\Http\Response
     */
    public function show($code = null)
    {

        return view('tests.thanks', compact('code'));
    }

}
