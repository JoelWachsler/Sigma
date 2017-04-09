<?php

class SolverController extends BaseController {

	public function home($task, $group = false)
	{
        $data = new stdClass();
        // This task must be active for a normal user to see it
        if (Auth::user()->account_type > 1)
        {
            $data->task_data = Task::where('id', '=', $task)
                ->first(['latex_solve', 'latex_answer', 'answer', 'id']);
        }
        else
        {
            $data->task_data = Task::where('id', '=', $task)
                ->where('active', '=', '1')
                ->first(['latex_solve', 'latex_answer', 'answer', 'id']);
        }
        if (sizeof($data) != 0)
        {
            $data->task_url = action('TaskController@home', [$task, $group]);

            // Do not show this answer if the user hasn't tried to answer it yet
            $data->allowed = $this->tried($task);
        }
        $data->size = sizeof($data);


        return View::make('solver')->with('data', $data);
	}

    private function tried($task_id)
    {
        $tasks_cleared = TasksCleared::where('user_id', '=', Auth::user()->id)
            ->where('task_id', '=', $task_id)
            ->where('solved', '=', '1')
            ->count();
        if ($tasks_cleared > 0)
            return true;

        // The user has not solved this task yet
        $tasks_answers = TasksClearedAnswers::where('tasks_cleared_answers.user_id', '=', Auth::user()->id)
            ->join('tasks_cleared', 'tasks_cleared.id', '=', 'tasks_cleared_answers.tasks_cleared_id')
            ->where('tasks_cleared.task_id', '=', $task_id)
            ->count();
        if ($tasks_answers > 0)
            return true;

        // None of the above are true so the user haven't tried this task and therefore
        // we should not give them the answer
        return false;
    }

}
