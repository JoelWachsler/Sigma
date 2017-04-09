<?php

/*
* @author Joel Wachsler
* @version 1.0
* @description Model for book
*/

class Book extends Eloquent {

    protected $table = 'books';
    protected $fillable = array('name');

    public function chapters()
    {
        // Each book has many chapters
        return $this->hasMany('Chapter')
            ->select(array('id', 'name'));
    }

    /**
     * Get all books
     * @param void
     * @return array
     **/
    public static function books()
    {
        return Book::all();
    }
}

