<?php

/*
* @author: Joel Wachsler
* @version: 1.0
* @description: Model for Images
*/

class Image extends Eloquent {

    protected $table = 'img';
    protected $primaryKey = 'unique_id';
}

