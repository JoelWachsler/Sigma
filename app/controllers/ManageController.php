<?php

/**
 * Manager
 **/

class ManageController extends BaseController {
	
	public function home($book = false, $chapter = false, $id = false)
	{
        // Create a data holder which will be passed down to the view
        $data = new stdClass();
        $data->books = new stdClass();
        // Get all books
        $data->books = Book::books();

        // Pass data down to the view
        $data->this_book     = $book    ? $book     : 'default';
        $data->this_chapter  = $chapter ? $chapter  : 'default';
        $data->this_id       = $id      ? $id       : 'default';

        return View::make('manager')->with('data', $data);
	}

    public function task_create()
    {
        // Create new task if one does not exist
        if (Input::get('id') == 'new')
        {
            // Create new task
            $task = new Task;
            $task_count = Task::where('subchapter_id', '=', Input::get('subchapter_id'))
                ->count();
            // Set order
            $task->chapter_order = $task_count + 1;
        }
        else
            // Update task in database
            $task = Task::find(Input::get('id'));

        $task->fill(Input::all()); // Update data in this subchapter

        // Save task
        $task->save();
        // Return the id of the current task
        return Response::json($task->id);
    }

    public function insert_chapter()
    {
        try
        {
            // Check if name and book isset
            // if it isn't throw new exception
            if (Input::get('name') == null || Input::get('book') == null)
                throw new Exception('No name or book');

            $instance           = Chapter::firstOrNew(array('name' => Input::get('name')));
            $instance->name     = Input::get('name');
            $instance->book_id  = Input::get('book');
            $instance->save();

            // Return the instance with data
            return Response::json($instance);
        }
        catch(Exception $e)
        {
            return Response::json($e->getMessage());
        }
    }

    public function insert_subchapter()
    {
        try
        {
            // Check if name and book isset
            // if it isn't throw new exception
            if (Input::get('name') == null || Input::get('chapter') == null)
                throw new Exception('No name or chapter');

            $instance               = SubChapter::firstOrNew(array('name' => Input::get('name')));
            $instance->name         = Input::get('name');
            $instance->chapter_id   = Input::get('chapter');
            $instance->save();

            // Return the instance with data
            return Response::json($instance);
        }
        catch(Exception $e)
        {
            return Response::json($e->getMessage());
        }
    }
}
