<?php

/**
 * Classroom Controller
 **/

class ClassroomController extends BaseController {
	
	public function home()
	{
        $data = new stdClass(); // Data holder
        $url = new stdClass();  // Holder for the urls
        $url->create_classroom  = action('ClassroomController@create_classroom');
        $url->search_user       = action('ClassroomController@search_user');
        $url->search_school     = action('ClassroomController@search_school');
        $url->send_request      = action('ClassroomController@send_request');
        $url->chapter_data      = action('TaskController@chapters');
        $url->subchapter_data   = action('TaskController@subchapter_by_id');
        $url->task_data         = action('TaskController@tasks_active');
        $url->task_url          = action('TaskController@home');
        $url->new_homework      = action('ClassroomController@new_homework');
        $url->homework_data     = action('ClassroomController@homework_data');
        $url->stat_data         = action('ClassroomController@stat_data');
        
        $data->url = $url;      // Assign url values

        // Get all classrooms this user is in
        $classrooms = ClassroomStudent::where('classroom_students.user_id', '=', Auth::user()->id)
            ->join('classrooms', 'classrooms.id',                           '=', 'classroom_students.classroom_id')
            ->join('classes', 'classes.id',                                 '=', 'classrooms.class_id')
            ->get();

        // Go through each classroom and add the students
        foreach($classrooms as $key => $value)
        {
            $item = new stdClass();
            $item = ClassroomStudent::where('classroom_id',    '=', $value->class_id)
                // The teacher is not a student
                ->where('user_id',                             '!=', $value->user_id)
                ->join('users', 'users.id',                    '=', 'classroom_students.user_id')
                ->get(['users.first_name', 'users.last_name']);
            $value->user_data = $item;

            // Add the info of the classroom
            $classroom_info = new stdClass();
            // Get Teacher name,
            // school name
            // and current class name
            $classroom_info = Classroom::where('classrooms.id', '=', $value->classroom_id)
                ->join('users', 'users.id',                     '=', 'classrooms.user_id')
                ->join('schools', 'schools.id',                 '=', 'classrooms.school_id')
                ->join('classes', 'classes.id',                 '=', 'classrooms.class_id')
                ->first(['users.first_name', 'users.last_name', 'schools.name as schoolname', 'classes.name as classname']);

            $value->classroom_info = $classroom_info;

            // Is this user the owner of this classroom?
            $value->is_owner = $this->is_owner_and_teacher($value->classroom_id);
        }
        $data->classrooms = $classrooms;

        // Add task-data
        $books = Book::all(['id', 'name']);
        $data->books = $books;

		return View::make('classroom')->with('data', $data);
	}

    /**
     * Check if the user is a teacher or above
     **/
    private function is_teacher()
    {
        // Teacher previlege = 1
        return Auth::user()->account_type > 0 ? true : false;
    }

    /**
     * Check if the teacher is the owner of the classroom
     **/
    private function is_owner($classroom_id)
    {
        // Count classrooms where the the user_id and id of the task is the same
        $classroom = Classroom::where('id', '=', $classroom_id)
            ->where('user_id', '=', Auth::user()->id)
            ->count();
        // If the count is above 0 the user is the owner of the classroom
        return $classroom > 0 ? true : false;
    }

    /**
     * Check both if the user is teacher and owner of classroom
     **/
    private function is_owner_and_teacher($classroom_id)
    {
        if ($this->is_teacher() && $this->is_owner($classroom_id))
            return true;
        else
            return false;
    }

    /**
     * Create new classroom
     **/
    public function create_classroom()
    {
        if ($this->is_teacher() && Input::get('school_name') != null && Input::get('class_name') != null)
        {
            $school_name    = Input::get('school_name');
            $class_name     = Input::get('class_name');
            // Get id of school if it exists
            $school = School::firstOrNew(['name' => $school_name]);
            // Assign name to the school just incase
            // it does not exist
            $school->name = $school_name;
            // Save the school so we can use it later
            $school->save();

            // Create class
            $class = _Class::firstOrNew([
                        'name'      => $class_name,
                        'school_id' => $school->id
                    ]);
            $class->fill([
                        'name'      => $class_name,
                        'school_id' => $school->id
                    ]);
            $class->save();

            // Create the new classroom
            $classroom = Classroom::firstOrNew([
                        'class_id'  => $class->id,
                        'user_id'   => Auth::user()->id,
                        'school_id' => $school->id
                    ]);
            $classroom->fill([
                        'class_id'  => $class->id,
                        'user_id'   => Auth::user()->id,
                        'school_id' => $school->id
                    ]);
            $classroom->save();
            
            // Add teacher to the classroom
            $classroom_students = ClassroomStudent::firstOrNew([
                        'user_id'       => Auth::user()->id,
                        'classroom_id'  => $classroom->id
                    ]);
            $classroom_students->fill([
                        'user_id'       => Auth::user()->id,
                        'classroom_id'  => $classroom->id
                    ]);
            $classroom_students->save();

            // Return id of the new classroom
            return $classroom->id;
        }
        else
            return Response::json(false);
    }

    /**
     * Send request to join the classroom to the user
     **/
    public function send_request()
    {
        $classroom_id    = Input::get('classroom_id');
        if (Input::get('student_id') != null && Input::get('classroom_id') != null && $this->is_owner_and_teacher($classroom_id))
        {
            // Student id
            $student_id     = Input::get('student_id');

            // Check so the user is not already in the classroom
            $classroom_check = Classroom::where('user_id', '=', $student_id)
                ->where('id', '=', $classroom_id)
                ->count();
            if ($classroom_check > 0)
                return Response::json('Användaren finns redan i klassrummet!');

            // Create a new classroom request
            $classroom_activation = ClassroomActivation::firstOrNew([
                        'user_id'       => $student_id,
                        'classroom_id'  => $classroom_id
                    ]);
            $classroom_activation->fill([
                        'user_id'       => $student_id,
                        'classroom_id'  => $classroom_id,
                        'activation_key'=> bin2hex(openssl_random_pseudo_bytes(16))
                    ]);
            $classroom_activation->save();

            // -- Create a new alert --

            // Message in alert
            $classroom_name = Classroom::where('classrooms.id', '=', $classroom_id)
                ->join('classes', 'classes.id', '=','classrooms.class_id')
                ->first(['classes.name'])->name;
            $message = Auth::user()->first_name.' '.Auth::user()->last_name.' vill att du går med i klassrummet '.$classroom_name;
            // Link to activate classroom for this user
            $join_classroom_link = action('ClassroomController@activate', [$classroom_activation->activation_key]);


            // Get the id of the type `classroom-new`
            $type_id = Type::where('type_name', '=', 'classroom-new')
                ->first(['id'])->id;
            // Create the actual alert
            $alert = Alert::firstOrNew([
                    'user_id'   => $student_id,
                    'type_id'   => $type_id,
                    'message'   => $message
            ]);
            $alert->fill([
                    'user_id'       => $student_id,
                    'type_id'       => $type_id,
                    'message'       => $message,
                    'link_to_alert' => $join_classroom_link
            ]);
            $alert->save();

            return Response::json(true);
        }
        return Response::json(false);
    }

    /**
     * Search for user and get the id for add_student function
     **/
    public function search_user()
    {
        if (Input::get('input') != null) // No point in searching if there's nothing to search for
        {
            $name_split = explode(" ", Input::get('input'));
            $users = User::select('id', 'first_name', 'last_name', 'email')
                ->take(5);
            foreach($name_split as $item)
            {
                $users->orWhere('first_name',   'LIKE', '%'.$item.'%');
                $users->orWhere('last_name',    'LIKE', '%'.$item.'%');
                $users->orWhere('email',        'LIKE', '%'.$item.'%');
            }

            return $users->get();
        }
        return Response::json(false);
    }

    /**
     * Search for schools and give back the names
     **/
    public function search_school()
    {
        if (Input::get('input') != null)
        {
            $schools = School::where('name', 'LIKE', Input::get('input').'%')
                ->take(5)
                ->get(['name as value']);
            return $schools;
        }
        else
            return Response::json('false');
    }

    /**
     * Activate the classroom if the user visits this page with a valid key
     **/
    public function activate($key)
    {
        if ($key)
        {
            $classroom_activation = ClassroomActivation::where('user_id', '=', Auth::user()->id)
                ->where('activation_key', '=', $key);
            // Check if the user has a pending activation
            if ($classroom_activation->count() > 0)
            {
                // The user does have one
                // Add the user to the classroom if they are not already in there
                $classroom_id = $classroom_activation->first(['classroom_id'])->classroom_id;
                $classroom_students = ClassroomStudent::firstOrNew([
                            'user_id'       => Auth::user()->id,
                            'classroom_id'  => $classroom_id
                        ]);
                $classroom_students->fill([
                            'user_id'       => Auth::user()->id,
                            'classroom_id'  => $classroom_id
                        ]);
                $classroom_students->save();

                // Remove the activation
                $classroom_activation->delete();

            }
        }
        // Redirect home
        return Redirect::to(action('ClassroomController@home'));
    }

    /**
     * Create new homework
     **/
    public function new_homework()
    {
        if (Input::get('classroom_id') != null && Input::get('deadline') != null && Input::get('tasks_group') != null)
        {
            // Declare variables for easier access
            $classroom_id   = Input::get('classroom_id');
            $deadline       = strtotime(Input::get('deadline'));
            if (!$deadline)
                return Response::json('Tidformat fel!');
            // Convert to date object
            $deadline       = date('y-m-d', $deadline);
            $task_group     = Input::get('tasks_group');
            $message        = Input::get('message') == null ? "" : Input::get('message');
            
            // Check if the user is a teacher and owns this classroom
            if ($this->is_owner_and_teacher($classroom_id))
            {
                // Create homework
                $homework = ClassroomHomework::firstOrNew([
                            'classroom_id'  => $classroom_id,
                            'message'       => $message,
                            'deadline'      => $deadline
                        ]);
                $homework->fill([
                            'classroom_id'  => $classroom_id,
                            'message'       => $message,
                            'deadline'      => $deadline
                        ]);
                $homework->save();

                // Create task-group
                foreach($task_group as $item)
                {
                    $task_group = ClassroomTaskGroup::firstOrNew([
                                'task_id'               => $item,
                                'classroom_homework_id' => $homework->id
                            ]);
                    $task_group->fill([
                                'task_id'               => $item,
                                'classroom_homework_id' => $homework->id
                            ]);
                    $task_group->save();
                }

                // Send a message to all in the classroom that there's a new homework
                $users_in_classroom = ClassroomStudent::where('classroom_id', '=', $classroom_id)->get(['user_id']);
                // Get the id of the type `classroom-new`
                $type_id = Type::where('type_name', '=', 'classroom-new')
                    ->first(['id'])->id;

                $classroom_name = Classroom::where('classrooms.id', '=', $classroom_id)
                    ->join('classes', 'classes.id', '=', 'classrooms.class_id')
                    ->first(['name'])->name;
                $message = 'Det finns en ny läxa i klassrummet '.$classroom_name;
                $link_to_classroom = action('ClassroomController@home');
                
                foreach ($users_in_classroom as $item)
                {
                    $alert = Alert::create([
                            'user_id'       => $item->user_id,
                            'type_id'       => $type_id,
                            'message'       => $message,
                            'link_to_alert' => $link_to_classroom
                    ]);
                    $alert->save();
                }

                // Everything is fine ^^
                return Response::json('true');
            }
            else
                return Response::json('Du är inte en lärare eller så äger du inte det här klassrummet!');
        }
        else
            return Response::json('Någonting saknas!');
    }

    /**
     * Get homework-data for the current classroom
     **/
    public function homework_data()
    {
        if (Input::get('classroom_id') != null)
        {
            $classroom_id = Input::get('classroom_id');
            $homeworks = ClassroomHomework::where('classroom_id', '=', $classroom_id)
                ->orderBy('deadline', 'asc')
                ->get(['id', 'message', 'deadline']);
            foreach($homeworks as $item)
            {
                // Add task-grups to each homework
                $task_group = ClassroomTaskGroup::where('classroom_homework_id', '=', $item->id)
                    ->get(['id', 'task_id']);
                $item->tasks = $task_group;
                // Check if the user has completed the task
                foreach($item->tasks as $task)
                {
                    $tasks_cleared = TasksCleared::where('user_id', '=', Auth::user()->id)
                        ->where('task_id', '=', $task->task_id);
                    // Check if there are even any entries
                    $task->solved = $tasks_cleared->count() > 0 ? $tasks_cleared->first(['solved'])->solved : 0;
                }

                // Calculate days in numbers
                $days = round((strtotime($item->deadline) - time()) / 86400) + 1;
                // Replace the deadline for days instead of timestamp
                // Cannot have a negative deadline
                $item->days = $days > 0 ? $days : 0;
                // Format date for a friendlier look
                $item->deadline = date_format(date_create($item->deadline), 'Y-m-d');
            }

            return $homeworks;
        }
        else
            return Response::json('Klassrummets id saknas...');
    }

    /**
     * Get statistics for the current homework if
     * the user requesting it is the owner of this
     * current classroom
     **/
    public function stat_data()
    {
        if (Input::get('classroom_id') == null || Input::get('homework_id') == null)
            return Response::json('Fick inte tillräckligt med data');

        // Assigning variabled for easier access
        $classroom_id = Input::get('classroom_id');
        $homework_id = Input::get('homework_id');

        // Check if this user is the owner of this classroom
        if (!$this->is_owner($classroom_id))
            return Response::json('Du äger inte det här klassrummet...');

        // Get the task ids of the items we want in the current homework
        $task_id = ClassroomTaskGroup::where('classroom_homework_id', '=', $homework_id)
            ->get(['task_id']);

        // Format the ids to a normal array
        $new_task_id = [];
        foreach ($task_id as $item)
            $new_task_id[] = $item->task_id;

        // Get the users in the current classroom
        // Do not get the current user because this person is
        // the owner of this classroom
        $user_ids = ClassroomStudent::where('classroom_id', '=', $classroom_id)
            ->where('user_id', '!=', Auth::user()->id)
            ->get(['user_id']);

        // Format user ids to a normal array
        $new_user_ids = [];
        foreach($user_ids as $user_id)
            $new_user_ids[] = $user_id->user_id;

        // Get the tasks where the current homework matches and
        // the users match
        $final_task_data = new stdClass();
        // Students in classroom calculation
        $student_calc = count($new_user_ids);
        if ($student_calc == 0)
            return Response::json("Inga studenter :(");

        foreach ($new_task_id as $current_task_id)
        {
            // Prepare the final task holder
            $final_task_data->$current_task_id = new stdClass();
            // Get completed tasks
            $final_task_data->$current_task_id->completed = 0;
            if (count($new_user_ids) > 0)
                $final_task_data->$current_task_id->completed = TasksCleared::whereIn('user_id', $new_user_ids)
                    ->where('task_id', '=', $current_task_id)
                    ->where('solved', '=', 1)
                    ->count();

            // Get started tasks
            $final_task_data->$current_task_id->started = 0;
            if (count($new_user_ids) > 0)
                $final_task_data->$current_task_id->started = TasksCleared::whereIn('user_id', $new_user_ids)
                    ->where('task_id', '=', $current_task_id)
                    ->where('solved', '=', 0)
                    ->count();


            // Calculate how many hasn't started yet
            $final_task_data->$current_task_id->not_started = $student_calc - $final_task_data->$current_task_id->started - $final_task_data->$current_task_id->completed;

            // Calculate percentage of class has completed this task
            $final_task_data->$current_task_id->percentage = $final_task_data->$current_task_id->completed / ($final_task_data->$current_task_id->started + $final_task_data->$current_task_id->completed + $final_task_data->$current_task_id->not_started) * 100;
            
            // Wrong answers

            // Get the tasks_cleared_id
            $clear_id = TasksCleared::whereIn('user_id', $new_user_ids)
                ->where('task_id', '=', $current_task_id)
                ->get(['id']);

            // Fix for use in querys
            $clear_id_fix = [];
            foreach($clear_id as $clear_curr_id)
                $clear_id_fix[] = $clear_curr_id->id;

            $answers = new stdClass();
            if (count($clear_id_fix) > 0 && count($new_user_ids) > 0)
                $answers = TasksClearedAnswers::whereIn('user_id', $new_user_ids)
                    ->whereIn('tasks_cleared_id', $clear_id_fix)
                    ->where('correct', '=', 0)
                    ->get(['answer']);

            $answers_fixed = [];
            foreach($answers as $answer)
                $answers_fixed[] = $answer->answer;

            $final_task_data->$current_task_id->wrong_answers = [count($answers_fixed), array_count_values($answers_fixed)];
        }

        // Calculate how many students have completed all tasks
        $completed_all = 0;
        $started = 0;
        $not_started = 0;
        foreach($new_user_ids as $student)
        {
            if (count($new_task_id) > 0)
                $completed_tasks = TasksCleared::where('user_id', '=', $student)
                    ->whereIn('task_id', $new_task_id)
                    ->where('solved', '=', 1)
                    ->count();

            if (count($new_task_id) == $completed_tasks)
                $completed_all++;
            else if ($completed_tasks > 0)
                $started++;
            else
                $not_started++;
        }

        // Holder for all data
        $holder = new stdClass();
        $holder->stat_data = $final_task_data;
        $holder->completed_all = $completed_all;
        $holder->not_started = $not_started;
        $holder->started = $started;

        return Response::json($holder);
   }
}
