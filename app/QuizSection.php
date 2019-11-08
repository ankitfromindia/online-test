<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizSection extends Model
{
    protected $fillable = ['quiz_id', 'topic_id', 'question_count'];
    
}
