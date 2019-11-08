<?php

namespace App\Providers;

use App\Question;
use App\QuestionsOption;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \DB::listen(function ($query) {
             info('Query :: ' . $query->sql);
             info('Bindings :: ' . json_encode($query->bindings));
             info('Execution Time :: ' . $query->time);
         });
        Question::deleting(function ($question) {
            $question->options()->delete();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->environment() !== 'production') {
            $this->app->register(\Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider::class);
        }
    }
}
