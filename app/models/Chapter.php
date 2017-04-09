<?php

/*
* @author Joel Wachsler
* @version 1.0
* @description Model for chapters
*/

class Chapter extends Eloquent {

    protected $table = 'chapters';
    protected $fillable = array('name', 'book');

    public function tasks()
    {
        // Each chapter has many tasks
        return $this->hasMany('Task')->select(array('id', 'starred', 'difficulty', 'chapter_order'));
    }

    public function book()
    {
        return $this->belongsTo('Book')->select(array('id', 'name'));
    }

    public function subchapters()
    {
        return $this->hasMany('SubChapter');
    }
}
