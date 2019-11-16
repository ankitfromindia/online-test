<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Question;
use App\Quiz;
use App\QuizSection;

class QuizzesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quiz = Quiz::getListOfQuizzes();
        
        return view('quizzes.index', compact('quiz'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $topics = \App\Topic::get()->pluck('title', 'id')->prepend('Please select', '');

        return view('quizzes.create', compact('topics') + ['visible' => false]);
    }

    
    public function addMoreSection()
    {
        $topics = \App\Topic::get()->pluck('title', 'id')->prepend('Please select', '');
        return view('quizzes.section', compact('topics') + ['visible' => true]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = Quiz::create([
            'user_id' => auth()->id(),
            'category' => $request->input('category'),
            'test_time' => $request->input('test_time'),
            'code' => md5($request->input('category'))
        ]);
        $sections = array_combine($request->input('topic_id'), $request->input('no_of_questions'));
        foreach($sections as $topicId => $noOfQuestions)
        {
            QuizSection::create([
               'quiz_id' => $category->id,
                'topic_id' => $topicId,
                'question_count' => $noOfQuestions
            ]);
        }
        
        event(new \App\Events\QuizTemplateCreatedOrUpdated($category->id));
        return redirect()->route('quizzes.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $topics = \App\Topic::get()->pluck('title', 'id')->prepend('Please select', '');

        return view('quizzes.create', compact('topics') + ['visible' => false]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $topics = \App\Topic::get()->pluck('title', 'id')->prepend('Please select', '');
        $quiz = Quiz::findOrFail($id);
        $sections = QuizSection::where('quiz_id', $id)->get();
        
        return view('quizzes.edit', compact('quiz', 'topics', 'sections') + ['visible' => true]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //dd($request->all());
        $quizzestime = Quiz::findOrFail($id);
        $quizzestime->update($request->all());
        $sections = array_combine($request->input('topic_id'), $request->input('no_of_questions'));
        
        QuizSection::where('quiz_id', $id)->delete();
        foreach($sections as $topicId => $noOfQuestions)
        {
            QuizSection::create([
               'quiz_id' => $id,
                'topic_id' => $topicId,
                'question_count' => $noOfQuestions
            ]);
        }
       \App\QuizQuestion::where('quiz_id', $id)->delete();
       event(new \App\Events\QuizTemplateCreatedOrUpdated($id));
       return redirect()->route('quizzes.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        dd($id);
    }

    /**
     * Delete all selected Question at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if ($request->input('ids')) {
            $entries = Quiz::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }    
}
