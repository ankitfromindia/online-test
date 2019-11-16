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
        
        StartQuiz::create([
            'user_id'     => Auth::id(),
            'code'     => $code,
            'no_of_question' => 2,
        ]);        
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

        foreach ($questions as &$question) {
            $question->options = QuestionsOption::where('question_id', $question->question_id)->inRandomOrder()->get();
            //return view('test_actions.create', compact('question','quiz','question_count'));
            return redirect()->route('test_actions.next', ['id' => 1, 'code' => $code]);
        }

        //return view('tests.create', compact('questions','quiz'));
    }    

    public function next(Request $request, $code = null){
         $result = 0;
        $code = $request->code;
        $test = Test::create([
            'user_id' => Auth::id(),
            'result'  => $result,
        ]);
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
