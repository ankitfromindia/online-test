<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = ['user_id', 'category', 'code'];
    protected $casts = [
      'total_questions' => 'integer'  
    ];
    
    public function sections()
    {
        return $this->hasMany(QuizSection::class);
    }
    
    public static function getListOfQuizzes()
    {
        return self::join('quiz_sections', 'quizzes.id', '=', 'quiz_sections.quiz_id')
                ->select('quizzes.*' , \DB::raw('SUM(question_count) as total_questions'))
                ->groupBy('quiz_id')->get();
    }
}
