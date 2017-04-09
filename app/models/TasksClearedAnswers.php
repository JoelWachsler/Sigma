<?php

/*
* @author Joel Wachsler
* @version 1.0
* @description Model for TasksClearedAnswers
*/

class TasksClearedAnswers extends Eloquent {

    protected $table = 'tasks_cleared_answers';
    protected $fillable = array('user_id', 'tasks_cleared_id', 'part', 'answer', 'correct');

    public function tasks_cleared()
    {
        return $this->belongsTo('TasksCleared');
    }
}
