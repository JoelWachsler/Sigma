<?php

/*
\ Profile for later
*/

class ProfileController extends BaseController {
	
	public function home()
	{
        $data = new stdClass();
        $data->week_data = $this->day_data(7);
        $data->month_data = $this->day_data(30);
		return View::make('profile')->with('data', json_encode($data));
	}

    /**
     * Get week data
     **/
    private function day_data($days_back)
    {
        $data = []; // Holder for week dates
        $data_tasks = [[],[],[],[]]; // Holder for week data
        for ($i = $days_back-1; $i+1 > 0; $i--) {
            $timestamp = strtotime('midnight today -'.$i.' days');
            $date = new Datetime();
            $date->setTimestamp($timestamp);
            array_push($data, $date->format('m/d'));
            $week_data = $this->tasks_done_week($timestamp);

            array_push($data_tasks[0], $week_data[0]);
            array_push($data_tasks[1], $week_data[1]);
            array_push($data_tasks[2], $week_data[2]);
            array_push($data_tasks[3], $week_data[3]);
        }

        return [$data, $data_tasks];
    }

    /**
     * Get data for days
     **/
    private function get_data($start_day, $difficulty = false)
    {
        $start_day = clone $start_day;
        if ($difficulty)
        {
            $result = TasksCleared::where('user_id',  '=', Auth::user()->id)
                ->where('solved',                     '=', true)
                ->where('tasks_cleared.updated_at',   '>', $start_day->format('Y-m-d H:i:s'))
                ->where('tasks_cleared.updated_at',   '<', $start_day->modify('+1 day')->format('Y-m-d H:i:s'))
                ->join('tasks', 'tasks.id',           '=', 'tasks_cleared.task_id')
                ->where('tasks.difficulty',           '=', $difficulty)
                ->count();
        }
        else
        {
            $result = TasksCleared::where('user_id',  '=', Auth::user()->id)
                ->where('solved',                     '=', true)
                ->where('updated_at',                 '>', $start_day->format('Y-m-d H:i:s'))
                ->where('updated_at',                 '<', $start_day->modify('+1 day')->format('Y-m-d H:i:s'))
                ->count();
        }

        return $result;
    }

    /**
     * Counts the tasks done at specific day
     **/
    private function tasks_done_week($timestamp)
    {
        $start_day = new Datetime();
        $start_day->setTimestamp($timestamp);

        $result_easy    = $this->get_data($start_day, 1); // Easy
        $result_medium  = $this->get_data($start_day, 2); // Medium
        $result_hard    = $this->get_data($start_day, 3); // Hard
        $result_total   = $this->get_data($start_day);    // All

        return [$result_easy, $result_medium, $result_hard, $result_total];
    }
}
