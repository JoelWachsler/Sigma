<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*
* Authorized Group
*/
Route::group(array('before' => 'auth'), function()
{
    App::missing(function($exception)
    {
        return Redirect::action('HomeController@home');
    });

    /**
     * !IMPORTANT
     * Some of the routes must be placed inside a "teacher only" filter soon
     * !IMPORTANT
     **/

    /**
     * Get stat data (Teacher only)
     **/
    Route::get('/classroom/teacher/get_stat_data', array(
        'uses'  => 'ClassroomController@stat_data'
    ));

    /**
     * Get homework data
     **/
    Route::get('/classroom/homework_data', array(
        'uses'  => 'ClassroomController@homework_data'
    ));
    /**
     * Create new homework
     **/
    Route::get('/classroom/add_homework', array(
        'uses'  => 'ClassroomController@new_homework'
    ));
    /**
     * Activate classroom for user
     **/
    Route::get('/classroom/activate/{key}', array(
        'uses'  => 'ClassroomController@activate'
    ));
    /**
     * Send request to user to join the classroom
     **/
    Route::get('/classroom/send_request', array(
        'uses'  => 'ClassroomController@send_request'
    ));
    /**
     * Search users in classroom
     **/
    Route::get('/classroom/search_users', array(
        'uses'  => 'ClassroomController@search_user'
    ));
    /**
     * Search schools
     **/
    Route::get('/classroom/search_schools', array(
        'uses'  => 'ClassroomController@search_school'
    ));
    /**
     * Create classroom
     **/
    Route::get('/classroom/create', array(
        'uses'  => 'ClassroomController@create_classroom'
    ));
    
    /**
     * Toggle starred
     **/
    Route::get('/toggle_starred', array(
        'uses' => 'UtilController@toggle_starred'
    ));
    /**
     * Check if answer is correct
     **/
    Route::get('/check', array(
        'uses'  => 'TaskController@check'
    ));

    /**
     * Mark a message as read
     **/
    Route::post('/read_alert', array(
        'as'    => 'read-alert',
        'uses'  => 'UtilController@read_alert'
    ));

    /**
     * Get alerts
     **/
    Route::get('/get_alerts', array(
        'as'    => 'get-alerts',
        'uses'  => 'UtilController@get_alerts'
    ));

    /**
     * Get data in chapter for the first page
     **/
    Route::post('/get/chapter_data', array(
        'as'    => 'chapter-data',
        'uses'  => 'TaskController@get_chapter_data'
    ));

    /**
     * Get subchapters in chapter
     **/
    Route::get('/subchapter_by_chapter_id', array(
        'as'    => 'subchapter-by-id',
        'uses'  => 'TaskController@subchapter_by_id'
    ));

    /**
     * Get Formula by id
     **/
    Route::get('/formula_by_id', array(
        'as'    => 'formula-id',
        'uses'  => 'FormulasController@get_formula_by_id'
    ));

    /**
     * Get all formulas
     **/
    Route::get('/formulas_all', array(
        'as'    => 'all-formulas',
        'uses'  => 'FormulasController@get_formulas'
    ));

    /**
     * Get all active tasks
     **/
    Route::get('/tasks/active', array(
        'as'    => 'tasks',
        'uses'  => 'TaskController@tasks_active'
    ));

    /**
     * Get all chapters
     **/
    Route::get('/chapters', array(
        'as'    => 'chapters',
        'uses'  => 'TaskController@chapters'
    ));

    /**
     * Get formula JSON
     **/
    Route::get('/formula', array(
        'as'    => 'formula',
        'uses'  => 'FormulasController@search'
    ));

    /**
     * Get whole formula
     **/
    Route::get('/formula/{name?}', array(
        'as'    => 'formula_reference',
        'uses'  => 'FormulasController@home'
    ));

    /*
    * Get task JSON form
    */
    Route::get('/task_data', array(
        'as'	=>	'task_data',
        'uses'  =>  'TaskController@task_data'
    ));

    /*
    \ Landing page
    */
    Route::get('/home', array(
        'as'	=>	'home',
        'uses'  =>  'HomeController@home'
    ));

    Route::get('/courses', array(
        'as'    =>  'courses',
        'uses'  =>  'CoursesController@home'
    ));

    /*
    \ SIGN OUT (GET)
    */
    Route::get('/account/sign-out', array(
        'as'	=>	'account-sign-out',
        'uses'  =>  'AccountController@getSignOut'
    ));

    /*
    \ Get images (GET)
    */
    Route::get('/image/{id}/{width?}/{height?}', array(
        'as'    =>  'img',
        'uses'  =>  'UtilController@return_image'
    ));

    /*
    \ Upload images (GET)
    */
    Route::any('/upload_img/', array(
        'uses'  =>  'UtilController@upload_img'
    ));        

    /*
    \ Control the tasks (GET)
    */
    Route::get('/task/{task}/{group?}', array(
        'as'    => 'task',
        'uses'  =>  'TaskController@home'
    ));        

    Route::get('/task_check/{course?}/{task?}', array(
        'uses'  =>  'TaskController@check_exist'
    ));        

    /*
    \ Basic routes
    */
    Route::get('/solver/{task}/{group?}', array(
        'as'	=>	'solver',
        'uses'	=>	'SolverController@home'
    ));

    Route::get('/profile', array(
        'as'	=>	'profile',
        'uses'	=>	'ProfileController@home'
    ));

    Route::get('/achievement', array(
        'as'    =>  'achievement',
        'uses'  =>  'AchievementController@home'
    ));

    Route::get('/classroom', array(
        'as'    =>  'classroom',
        'uses'  =>  'ClassroomController@home'
    ));

    Route::get('/settings', array(
        'as'    =>  'settings',
        'uses'  =>  'SettingsController@home'
    ));

    Route::get('/tools', array(
        'as'    =>  'tools',
        'uses'  =>  'ToolsController@home'
    ));

    // Admin only routes
    Route::group(array('before' => 'admin'), function()
    {
        Route::get('/manager/{book?}/{chapter?}/{id?}', array(
            'as'    =>  'manager',
            'uses'  =>  'ManageController@home'
        ));

        /**
         * Get all tasks from chapter
         **/
        Route::get('/tasks', array(
            'as'    => 'tasks',
            'uses'  => 'TaskController@tasks'
        ));

        /**
         * Insert new chapter
         **/
        Route::post('/insert/chapter', array(
            'as'    => 'insert-chapter',
            'uses'  => 'ManageController@insert_chapter'
        ));

        /**
         * Insert new chapter
         **/
        Route::post('/insert/subchapter', array(
            'as'    => 'insert-subchapter',
            'uses'  => 'ManageController@insert_subchapter'
        ));
    });

    /**
     * Admin routes
     **/

    Route::group(array('before' => 'admin'), function()
    {
        Route::get('/admin/accounts', array(
            'uses' => 'AdminController@accounts'
        ));

        Route::get('/admin/update', array(
            'uses' => 'AdminController@update_user'
        ));

        Route::get('/admin', array(
            'uses'  => 'AdminController@home'
        ));
    });

    

    /*
    \ CSRF protection
    */
	Route::group(array('before' => 'csrf'), function()
	{
        
        Route::group(array('before' => 'admin'), function()
        {
            /**
             * Insert Formula (ADMIN)
             **/
            Route::post('/insert/formula', array(
               'as'    => 'formula_insert',
               'uses'  => 'FormulasController@insert'
            ));
            /*
            * Create Task (POST)
            */
            Route::post('/task_create', array(
                'as'    =>  'manager-create-task',
                'uses'  =>  'ManageController@task_create'
            ));

            Route::post('/admin/update', array(
                'uses'  =>  'AdminController@update_user'
            ));
        });

        /*
        * Update user information
        */
        Route::post('settings', array(
            'uses'  => 'SettingsController@home'
        ));

	});

});

/*
\ Unauthorized user group
*/
Route::group(array('before' => 'guest'), function()
{
    /*
    \ CSRF protection
    */
	Route::group(array('before' => 'csrf'), function()
	{

        /*
        \ Create account (POST)
        */
    	Route::post('/account/create', array(
    		'as'	=>	'account-create-post',
    		'uses'	=>	'AccountController@postCreate'
    	));
	
		/*
		\ Sign in (POST)
		*/
		Route::post('/account/sign-in', array(
            'as'	=>	'account-sign-in',
            'uses'	=>	'AccountController@postSignIn'
		));
	});

    /*
    \ Activate account (GET)
    */

	Route::get('/account/activate/{code}', array(
		'as'	=>	'account-activate',
		'uses'	=>	'AccountController@getActivate'
	));

    Route::get('/', array(
        'as'	=>	'home-sign-in',
        'uses'  =>  'AccountController@main'
    ));
});

