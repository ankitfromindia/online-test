<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class QuizTemplateCreatedOrUpdated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $quizId;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($quizId)
    {
        $this->quizId = $quizId;
    }

}
