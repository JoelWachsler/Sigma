<?php

/*
* @author Joel Wachsler
* @version 1.0
* @description Model for Schools
*/

class School extends Eloquent
{
    protected $table = 'schools';
    protected $fillable = ['name'];
}

