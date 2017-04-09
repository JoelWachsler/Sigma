<?php

class TaskController extends BaseController {

	public function home($task, $group = false)
	{
        // Prepare array to hold data which will be sent to the View
        $data = array();

        // Add route
        $data['data_url']   = action('TaskController@task_data');
        $data['solver_url'] = action('SolverController@home', [$task, $group]);

        $data['answer_url'] = action('TaskController@check');
        $data['ok_url']     = asset('img/ok.png');
        $next_task          = $this->get_next_and_prev_task_id($group, $task);
        $data['next_prev_url'] = new stdClass();
        if ($next_task)
        {
            // Take out the id
            if ($next_task->next)
            {
                $data['next_prev_url']->next_url = action('TaskController@home', [$next_task->next->task_id, $group]);
                $data['next_prev_url']->task_id_next = $next_task->next->task_id;
            }
            if ($next_task->prev)
            {
                $data['next_prev_url']->prev_url = action('TaskController@home', [$next_task->prev->task_id, $group]);
                $data['next_prev_url']->task_id_prev = $next_task->prev->task_id;
            }
        }
        $data['group_id'] = $group;
        
        return View::make('task')->with('data', $data);
	}

    /**
     * Get tasks in each group and check if the user has completed them
     **/
    private function get_next_and_prev_task_id($group, $task)
    {
        if ($group != false)
        {
            // Get next task-id
            $next_data = ClassroomTaskGroup::where('classroom_homework_id', '=', $group)
                ->where('task_id', '!=', $task)
                ->get(['task_id']);

            // Default $next
            $next = false;
            foreach($next_data as $item)
            {
                $next_count = TasksCleared::where('tasks_cleared.task_id', '=', $item->task_id)
                    ->where('tasks_cleared.user_id',    '=', Auth::user()->id)
                    ->where('tasks_cleared.solved',     '=', '1')
                    ->count();
                if ($next_count == 0)
                {
                    // Found one!
                    $next = $item;
                    break;
                }
            }
            
            // Get prev task-id
            $prev = ClassroomTaskGroup::where('classroom_homework_id', '=', $group)
                ->join('tasks_cleared', 'tasks_cleared.task_id', '=', 'classroom_tasks_groups.task_id')
                ->where('tasks_cleared.user_id',    '=', Auth::user()->id)
                ->where('tasks_cleared.task_id',    '!=',   $task)
                ->where('tasks_cleared.solved',     '=',    '1')
                ->where('tasks_cleared.task_id',    '<',    $task)
                ->orderBy('tasks_cleared.task_id', 'DESC')
                ->first(['tasks_cleared.task_id']);
            $data = new stdClass();
            $data->next = $next;
            $data->prev = $prev;

            return $data;
        }
        else
            return false;
    }

    /**
     * Get saved tasks and their subchapters
     **/
    private function get_chapter_data_starred()
    {
        $tasks = Starred::where('user_id', '=', Auth::user()->id)
            ->orderBy('task_id')
            ->get(['task_id']);
        // Data holder
        $data = array();
        foreach($tasks as $item)
        {
            // Get Subchapter
            $task_data = Task::where('id', '=', $item->task_id)
                ->first(['id', 'chapter_order', 'difficulty', 'subchapter_id']);
            // Every task here is starred
            $task_data->starred = 1;
            // Do not get the subchapter data if we already have it
            $found = false;
            foreach ($data as $key => $data_item)
            {
                if ($data_item['subchapter_data']->id == $task_data->subchapter_id)
                {
                    // We have already gotten the info about this subchapter
                    $found = true;
                    array_push($data[$key]['tasks'], $task_data);
                    break;
                }
            }
            // Add subchapter data if it does not exist
            if (!$found)
            {
                $subchapter_data = SubChapter::where('id', '=', $task_data->subchapter_id)
                    ->first();
                array_push($data, ['tasks' => [$task_data], 'subchapter_data' => $subchapter_data]);
            }
        }

        return $data;
    }

    /**
     * Get chapter data and set if the task is starred or not
     **/
    public function get_chapter_data()
    {
        // Check if this chapter is "sparade uppgifter"
        $chapter = Chapter::find(Input::get('chapter_id'));
        if ($chapter->name != "Sparade uppgifter")
        {
            $result = Chapter::find(Input::get('chapter_id'))->subchapters;
            $data = array();
            foreach($result as $key => $value)
            {
                $task_data = Subchapter::find($value->id)->tasksActive;
                foreach ($task_data as $item_key => $item)
                {
                    $starred = Starred::where('task_id',    '=', $item->id)
                        ->where('user_id',                  '=', Auth::user()->id)
                        ->count();
                    $task_data[$item_key]->starred = $starred;
                }
                array_push($data, array('tasks' => $task_data, 'subchapter_data' => $value));
            }
        }
        else
            $data = $this->get_chapter_data_starred();

        return Response::json($data);
    }

    /**
     * Get subchapter in chapter
     **/
    public function subchapter_by_id()
    {
        $result = Chapter::findOrFail(Input::get('chapter_id'))->subchapters;
        return Response::json($result);
    }

    /**
     * @param int
     * @return array
     **/
    public function tasks()
    {
        $response = SubChapter::find(Input::get('subchapter_id'))
            ->tasks;
        return Response::json($response);
    }

    /**
     * Get active tasks
     * @param int
     * @return array
     **/
    public function tasks_active()
    {
        $response = Task::where('subchapter_id', '=', Input::get('subchapter_id'))
            ->where('active', '=', '1')
            ->get();
        //$response = SubChapter::find(Input::get('subchapter_id'))
            //->tasks;
        return Response::json($response);
    }

    /**
     * Get chapters in current book
     * @param int $book_id
     * @return array $chapters
     **/
    public function chapters()
    {
        $response = Book::findOrFail(Input::get('book_id'))->chapters;
        return Response::json($response);
    }

    /**
     * Get task data
     * @param int $task_id
     * @return array $task_data
     **/
    public function task_data()
    {
        // Find the task
        try
        {
            // The admin is the only one who can view tasks which are not completed
            if (Auth::user()->account_type > 1)
                $response = Task::where('id', '=', Input::get('task_id'))
                    ->first();
            else
                $response = Task::where('id', '=', Input::get('task_id'))
                    ->where('active', '=', '1')
                    ->first();
            if (!isset($response->id))
                throw new Exception('Uppgiften existerar inte!');
        }
        catch(Exception $e)
        {
            return $e->getMessage();
        }

        // Start the current task for the current user
        $start_task = TasksCleared::firstOrNew(['user_id' => Auth::user()->id, 'task_id' => $response->id]);
        // Fill with relevant data
        $start_task->user_id = Auth::user()->id;
        $start_task->task_id = $response->id;
        $start_task->save();

        // Save the id of the clear_task
        Session::put('task_clear_id', $start_task->id);
        $response['started_task']   = $start_task->id;
        // Get subchapter name
        $response['subchapter'] = Task::find(Input::get('task_id'))->subchapter['name'];
        
        // Get answers from before
        $start_answer = $this->sort($response->latex_answer);
        $start_size = [];
        // Count answers
        // and prepare holder
        $holder = [];
        foreach($start_answer as $key => $value)
        {
            $start_size[$key] = sizeof($value);
            $holder[$key] = [];
        }
        // Get the correct answers if they exist
        foreach($start_size as $key => $value)
        {
            $result = TasksClearedAnswers::where('user_id', '=', Auth::user()->id)
                ->where('tasks_cleared_id', '=', $start_task->id)
                ->where('correct', '=', true)
                ->where('part',    '=', $key)
                ->get();

            foreach($result as $result_answer)
                array_push($holder[$key], [$result_answer->answer, true]);
            // Get the last answers where they were not correct
            if (($value - sizeof($holder[$key]) > 0))
            {
                $last_answers = TasksClearedAnswers::where('user_id', '=', Auth::user()->id)
                    ->where('tasks_cleared_id', '=', $start_task->id)
                    ->where('correct', '=', false)
                    ->where('part',    '=', $key)
                    ->orderBy('updated_at', 'DESC')
                    ->take($value - sizeof($holder[$key]))
                    ->get();
                foreach($last_answers as $last_answers_item)
                    array_push($holder[$key], [$last_answers_item->answer, false]);
            }

            // Add missing values if there are any
            while(sizeof($holder[$key]) < sizeof($start_answer[$key]))
                array_push($holder[$key], ["", false]);
        }
        
        // Create the final holder for the answer data
        $final_holder = [];
        // Fill the final holder
        foreach($holder as $key => $value)
            foreach ($value as $deep_value)
                array_push($final_holder, [$key, $deep_value[0], $deep_value[1]]);

        // Add answer data to the container
        $response['before_answers'] = $final_holder;

        // Check if the user has completed this task before
        $completed = TasksCleared::where('user_id', '=', Auth::user()->id)
            ->where('task_id', '=', $response->id)
            ->first();
        $response['completed'] = $completed->solved;
       
        return Response::json($response);
    }

    private function sort($data)
    {
        $data = json_decode($data);
        $current = [];              // Holder for the answer data
        foreach ($data as $item)    // Sort the solving data
            if (array_key_exists($item[0], $current))
                array_push($current[$item[0]], $item[1]);   // Array already exists add the new value to it
            else
                $current[$item[0]] = [$item[1]];            // Array does not exist, lets create a new one
        return $current;
    }

    /**
     * Check if the answer is correct and update in database
     **/
    public function check()
    {
        // Get the current task_id
        $task_id    = Input::get('task_id');
        $answer     = Input::get('answer');
        $before     = json_decode(Input::get('before'));
        $task = Task::find($task_id); // Start instance of task
        // Get answer
        $data = $task->latex_answer;

        $current = $this->sort($data);
        if ($task->answer_type == 0)
        {
            // Save the number of answers for checking later
            $number_of_answers = [];
            foreach($current as $key => $value)
                $number_of_answers[$key] = sizeof($value);
            
            // Prevent doubles when checking for the right answer ^_^
            foreach ($before as $key => $value)
                foreach ($value as $deep_value)
                    foreach ($current[$key] as $current_key => $current_value)
                        if ($current[$key][$current_key] == $deep_value)
                            unset($current[$key][$current_key]);

            // Check if the user got the correct answer
            $answer_correct = in_array($answer[1], $current[$answer[0]]);
        }
        else
        {
            $answer_correct = false;
            // This code if used for checking when using alternatives
            // Get the correct answer
            $db_answer = $task->latex_answer;
            foreach (json_decode($db_answer) as $item)
                if ($item[0] == $answer[0])
                {
                    $answer_correct = json_decode($item[1]);
                    break;
                }
            $number_of_answers = 1;
        }

        // Check if the user already has a correct answer
        $answer_table_before = TasksClearedAnswers::where('user_id',  '=', Auth::user()->id)
            ->where('tasks_cleared_id', '=', Session::get('task_clear_id'))
            ->where('correct',          '=', true)
            ->where('part',             '=', $answer[0])
            ->count();

        // Let's save what the user answered
        // if the user hasn't already answered correct before
        if ($answer_table_before < $number_of_answers[$answer[0]])
        {
            // Check so the answer is not already in the database
            $before_answer = TasksClearedAnswers::where('user_id',  '=', Auth::user()->id)
                ->where('tasks_cleared_id', '=', Session::get('task_clear_id'))
                ->where('part',             '=', $answer[0])
                ->where('answer',           '=', $answer[1])
                ->count();

            if ($before_answer < 1)
            {
                $answer_instance = new TasksClearedAnswers;
                $answer_instance->fill(array(
                            'user_id'           => Auth::user()->id,
                            'tasks_cleared_id'  => Session::get('task_clear_id'),
                            'part'              => $answer[0],
                            'answer'            => $answer[1],
                            'correct'           => $answer_correct
                            ));
                $answer_instance->save();
            }
        }

        // If the user got the correct answer to all part-questions
        // update the database
        //return Response::json($answer_correct);
        if ($answer_correct)
        {
            // The user answered correctly!
            // Check if we should update the database and
            // set the current task to cleared
            
            // First remove the item the user answered from $current
            foreach ($current[$answer[0]] as $key => $value)
                if ($value == $answer[1])
                {
                    // Found the value!
                    unset($current[$answer[0]][$key]);
                    break;
                }
            // Remove items which are empty
            $current = array_filter($current);

            // Update in database if the user got this task right
            if (sizeof($current) == 0 || $task->answer_type == 1)
            {
                // Start tasks cleared instance and return
                // an error message if something goes wrong
                $tasks_cleared = TasksCleared::find(Session::get('task_clear_id'));
                $tasks_cleared->solved = true;
                $tasks_cleared->save();

                // Remove all right answers so the user can do this task again
                TasksClearedAnswers::where('user_id',   '=', Auth::user()->id)
                    ->where('tasks_cleared_id',         '=', Session::get('task_clear_id'))
                    ->where('correct',                  '=', 1)
                    ->delete();
                
            }
            //return Response::json($current);
            return Response::json(true);
        }
        else
            // The user did not answer correctly
            return Response::json(false);
    }
}
