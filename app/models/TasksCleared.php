<?php

/*
* @author Joel Wachsler
* @version 1.0
* @description Model for TasksCleared
*/

class TasksCleared extends Eloquent {

    protected $table = 'tasks_cleared';
    protected $fillable = array('user_id', 'task_id');

    public function tasks_cleared_answers()
    {
        return $this->hasMany('TasksClearedAnswers')->select(array('user_id', 'part', 'answer', 'correct'));
    }

    public function task()
    {
        return $this->belongsTo('Task', 'task_id')->select(['difficulty']);
    }
}
