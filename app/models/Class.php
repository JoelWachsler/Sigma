<?php

/*
* @author Joel Wachsler
* @version 1.0
* @description Model for Classes
*/

class _Class extends Eloquent
{
    protected $table = 'classes';
    protected $fillable = ['name', 'school_id'];
}

