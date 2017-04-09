<?php

/*
* @author Joel Wachsler
* @version 1.0
* @description Model for alert-types
*/

class Type extends Eloquent {

    protected $table = 'types';
    protected $fillable = array('type_name');
}
