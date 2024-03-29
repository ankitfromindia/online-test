<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Test;
use App\TestAnswer;
use App\Topic;
use App\Question;
use App\QuestionsOption;
use Illuminate\Http\Request;
use App\Http\Requests\StoreTestRequest;

class TestsController extends Controller
{
    /**
     * Display a new test.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $code = null)
    {
        return view('tests.start', compact('code'));
    }

    /**
     * Display a new test.
     *
     * @return \Illuminate\Http\Response
     */
    public function start(Request $request, $code = null)
    {
        $quiz = \App\Quiz::where('code', $code)->firstOrFail();
        //print_r($quiz);exit;
        $timer=array();
        $timer = $request->session()->put('timer_out', $quiz->test_time);
        echo $session_id = session()->getId();
        //Session::set('timer_out',$quiz->test_time );
        $questions = Question::join('quiz_questions', 'questions.id', '=', 'quiz_questions.question_id')
                ->where('quiz_id', $quiz->id)
                //->get();
                ->simplePaginate(1);
        foreach ($questions as &$question) {
            $question->options = QuestionsOption::where('question_id', $question->id)->inRandomOrder()->get();
        }

        return view('tests.create', compact('questions','quiz'));
    }    

    /**
     * Store a newly solved Test in storage with results.
     *
     * @param  \App\Http\Requests\StoreResultsRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = 0;

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

        $test->update(['result' => $result]);
        return view('tests.thanks');
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
