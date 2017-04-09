<?php

/**
 * @author Joel Wachsler
 * @version 1.0
 * @description Model for Classroom_student
 **/

class ClassroomStudent extends Eloquent
{
    protected $table = 'classroom_students';
    protected $fillable = ['user_id', 'classroom_id'];
}

