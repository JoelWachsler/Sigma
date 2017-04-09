<?php

/*
* @author: Joel Wachsler
* @version: 1.0
* @description: Model for TaskGroups
*/

class ClassroomTaskGroup extends Eloquent {

    protected $table = 'classroom_tasks_groups';
    protected $fillable = ['task_id', 'classroom_homework_id'];
}


