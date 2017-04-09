<?php

/*
* @author Joel Wachsler
* @version 1.0
* @description Model for Classroom
*/

class Classroom extends Eloquent
{
    protected $table = 'classrooms';
    protected $fillable = ['class_id', 'user_id', 'school_id'];
}
