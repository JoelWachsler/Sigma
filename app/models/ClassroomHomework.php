<?php

/*
* @author: Joel Wachsler
* @version: 1.0
* @description: Model for Homeworks
*/

class ClassroomHomework extends Eloquent {

    protected $table = 'classroom_homeworks';
    protected $fillable = ['classroom_id', 'task_group_id', 'message', 'deadline'];
}



