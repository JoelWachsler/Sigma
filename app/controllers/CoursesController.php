<?php

class CoursesController extends BaseController {

	public function home()
	{
        // Create a data holder which will be passed down to the view
        $data = new stdClass();
        $data->books = new stdClass();
        // Get all books
        $data->books = Book::books();

        $data_holder = new stdClass();
        foreach($this->get_book_names() as $item)
        {
            // Get all data for books
            $new_holder         = new stdClass();
            $difficulty_holder  = new stdClass();
            $total_holder       = new stdClass();

            $difficulty_holder->easy    = $this->count_completed_tasks_in_book($item->name, 1);
            $difficulty_holder->medium  = $this->count_completed_tasks_in_book($item->name, 2);
            $difficulty_holder->hard    = $this->count_completed_tasks_in_book($item->name, 3);
            $difficulty_holder->total   = $difficulty_holder->easy + $difficulty_holder->medium + $difficulty_holder->hard;
            $new_holder->completed      = $difficulty_holder;
            $total_holder->easy         = $this->count_tasks_in_book($item->name, 1);
            $total_holder->medium       = $this->count_tasks_in_book($item->name, 2);
            $total_holder->hard         = $this->count_tasks_in_book($item->name, 3);
            $total_holder->total        = $total_holder->easy + $total_holder->medium + $total_holder->hard;
            $new_holder->total_tasks    = $total_holder;
            $name                       = $item->name; // Cannot declare this after $data_holder
            $data_holder->$name         = $new_holder;
        }

        $data->course_data = $data_holder;
		return View::make('courses')->with('data', $data);
	}

    /**
     * This function takes the books name and difficulty as argument
     * and returns the number of completed tasks in it
     **/
    private function count_completed_tasks_in_book($book_name, $difficulty)
    {
        $course_data = TasksCleared::where('user_id',   '=', Auth::user()->id)
            ->join('tasks', 'tasks.id',                 '=', 'tasks_cleared.task_id')
            ->where('tasks_cleared.solved',             '=', true)
            ->where('tasks.active',                     '=', true)
            ->where('tasks.difficulty',                 '=', $difficulty)
            ->join('chapters', 'chapters.id',           '=', 'tasks.chapter_id')
            ->join('books', 'books.id',                 '=', 'chapters.book_id')
            ->where('books.name',                       '=', $book_name)
            ->count();
        return $course_data;
    }


    /**
     * Get the number of tasks in book
     **/
    private function count_tasks_in_book($book_name, $difficulty)
    {
        $book_data = Task::join('chapters', 'chapters.id',      '=', 'tasks.chapter_id')
            ->where('tasks.active',                             '=', true)
            ->where('difficulty',                               '=', $difficulty)
            ->join('books', 'books.id',                         '=', 'chapters.book_id')
            ->where('books.name',                               '=', $book_name)
            ->count();
        return $book_data;
    }

    /**
     * Returns all book names
     **/
    private function get_book_names()
    {
        return Book::all();
    }
}
