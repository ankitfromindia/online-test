<?php
namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StartQuiz
 *
 * @package App
 * @property string $question
 * @property string $option
 * @property tinyInteger $correct
*/
class StartQuiz extends Model
{
    use SoftDeletes;

    protected $fillable = ['user_id', 'code','no_of_question'];

    public static function boot()
    {
        parent::boot();

        Test::observe(new \App\Observers\UserActionsObserver);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
