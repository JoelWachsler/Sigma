<?php

/**
 * Model for tasks
 * @author Joel Wachsler
 * @version 1.0
 **/

class Task extends Eloquent {

    protected $table = 'tasks';
    protected $fillable = array(
        'chapter_id',
        'subchapter_id',
        'content',
        'latex_solve',
        'latex_answer',
        'answer',
        'difficulty',
        'height',
        'active',
        'answer_type'
    );

    public function chapter()
    {
        return $this->belongsTo('Chapter')->select(array('id', 'name'));
    }

    public function subchapter()
    {
        return $this->belongsTo('SubChapter')->select(array('id', 'name'));
    }

    public function tasks_cleared()
    {
        return $this->hasMany('TasksCleared');
    }

    /**
     * Check if the next and previous task exists
     * @param int, array
     * @return array
     **/
     public static function check_next_and_prev_task($id, $array)
     {
         // Default value set to "Not found" if the task is not found
         $array['next_task'] = $id;
         $array['prev_task'] = $id;
         if (Task::find($id + 1))
            $array['next_task'] = $id + 1;
         if (Task::find($id - 1))
            $array['prev_task'] = $id - 1;

        return $array;
     }

     /**
      * Insert data into database
      * @param array
      * @return int
      **/
      public static function insert($data)
      {
          try
          {
              $instance = Task::firstOrNew(array('task_id' => $data['task_id']));
              $instance->fill($data);
              $instance->save();

              return true;
          }
          catch(Exception $e)
          {
              return $e->getMessage();
          }
      }
}
