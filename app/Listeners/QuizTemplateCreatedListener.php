<?php

namespace App\Listeners;

use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class QuizTemplateCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        //
        info('listener is called');
        $sections = \App\QuizSection::where('quiz_id', $event->quizId)->get();
        foreach($sections as $section)
        {
            $questions = \App\Question::where('topic_id', $section->topic_id)
                    ->select('id')
                    ->inRandomOrder()
                    ->limit($section->question_count)
                    ->get();
            $quizQuestions = [];
            foreach($questions as $question)
            {
                $quizQuestions[] = [
                    'quiz_id' => $event->quizId,
                    'question_id' => $question->id
                ];
            }
            
            \App\QuizQuestion::insert($quizQuestions);
        }
    }
}
