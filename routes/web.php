<?php
Route::get('/', function () {
    return redirect('/home');
});




// Auth::routes();

// Authentication Routes...
$this->get('login', 'Auth\LoginController@showLoginForm')->name('auth.login');
$this->post('login', 'Auth\LoginController@login')->name('auth.login');
//http://127.0.0.1:8000/

$this->post('logout', 'Auth\LoginController@logout')->name('auth.logout');
$this->get('oauth2google', 'Auth\Oauth2Controller@oauth2google')->name('oauth2google');
$this->get('googlecallback', 'Auth\Oauth2Controller@googlecallback')->name('googlecallback');
$this->get('oauth2facebook', 'Auth\Oauth2Controller@oauth2facebook')->name('oauth2facebook');
$this->get('facebookcallback', 'Auth\Oauth2Controller@facebookcallback')->name('facebookcallback');
$this->get('oauth2github', 'Auth\Oauth2Controller@oauth2github')->name('oauth2github');
$this->get('githubcallback', 'Auth\Oauth2Controller@githubcallback')->name('githubcallback');

// Registration Routes...
$this->get('register', 'Auth\RegisterController@showRegistrationForm')->name('auth.register');
$this->post('register', 'Auth\RegisterController@register')->name('auth.register');

// Password Reset Routes...
$this->get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('auth.password.reset');
$this->post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('auth.password.reset');
$this->get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('auth.password.email');
$this->post('password/reset', 'Auth\ResetPasswordController@reset')->name('auth.password.reset');


Route::group(['middleware' => 'auth'], function () {
    Route::get('/home', 'HomeController@index');

    Route::any('testactions/start/{code?}', ['uses' => 'TestActionsController@start', 'as' => 'test_actions.start']);

    Route::get('expired/{code}', ['uses' => 'TestActionsController@sessionExpired', 'as' => 'test_actions.expired']);
    
    Route::get('testactions/{code?}', ['uses' => 'TestActionsController@index', 'as' => 'test_actions.index']);
    Route::post('testactions/store', ['uses' => 'TestActionsController@store', 'as' => 'test_actions.store']);
    Route::post('testctions/thanks', ['uses' => 'TestActionsController@show', 'as' => 'test_actions.thanks']);
    Route::get('testactions/next/{code?}', ['uses' => 'TestActionsController@next', 'as' => 'test_actions.next']);

    Route::post('testactions/nextAction', ['uses' => 'TestActionsController@nextAction', 'as' => 'test_actions.next_action']);   

    Route::any('tests/start/{id}/{code?}', ['uses' => 'TestsController@start', 'as' => 'tests.start']);
    Route::get('tests/{code?}', ['uses' => 'TestsController@index', 'as' => 'tests.index']);
    Route::post('tests/store', ['uses' => 'TestsController@store', 'as' => 'tests.store']);
    Route::post('tests/thanks', ['uses' => 'TestsController@show', 'as' => 'tests.thanks']);
    Route::resource('roles', 'RolesController');
    Route::post('roles_mass_destroy', ['uses' => 'RolesController@massDestroy', 'as' => 'roles.mass_destroy']);
    Route::resource('users', 'UsersController');
    Route::post('users_mass_destroy', ['uses' => 'UsersController@massDestroy', 'as' => 'users.mass_destroy']);
    Route::resource('user_actions', 'UserActionsController');
    Route::resource('topics', 'TopicsController');
    Route::post('topics_mass_destroy', ['uses' => 'TopicsController@massDestroy', 'as' => 'topics.mass_destroy']);
    Route::resource('questions', 'QuestionsController');
    Route::any('topic_show/{topic_id?}', ['uses' => 'QuestionsController@topicShow', 'as' => 'questions.topicShow']);
    Route::get('questions/create/{topic_id?}', ['uses' => 'QuestionsController@create', 'as' => 'questions.create']);
    Route::post('questions_mass_destroy', ['uses' => 'QuestionsController@massDestroy', 'as' => 'questions.mass_destroy']);
    Route::resource('questions_options', 'QuestionsOptionsController');
    Route::post('questions_options_mass_destroy', ['uses' => 'QuestionsOptionsController@massDestroy', 'as' => 'questions_options.mass_destroy']);
    Route::resource('results', 'ResultsController');

    Route::get('questions_options/create/{question_id?}', ['uses' => 'QuestionsOptionsController@create', 'as' => 'questions_options.create']);
    Route::post('results_mass_destroy', ['uses' => 'ResultsController@massDestroy', 'as' => 'results.mass_destroy']);
    Route::resource('quizzes', 'QuizzesController');
    Route::get('quizzes/section/add', 'QuizzesController@addMoreSection');
    Route::get('quizzes/clone/{code}', ['uses' => 'QuizzesController@clone', 'as' => 'quizzes.clone']);
        Route::post('quizzes_mass_destroy', ['uses' => 'QuizzesController@massDestroy', 'as' => 'quizzes.mass_destroy']);
});
