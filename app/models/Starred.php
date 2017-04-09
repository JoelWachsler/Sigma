<?php

/*
* @author: Joel Wachsler
* @version: 1.0
* @description: Model for Starred
*/

class Starred extends Eloquent {

    protected $table = 'starred';
    protected $fillable = ['user_id', 'task_id'];
}

