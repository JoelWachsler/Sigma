<?php

/*
* @author Joel Wachsler
* @version 1.0
* @description Model for subchapters
*/

class SubChapter extends Eloquent {

    protected $table = 'subchapters';
    protected $fillable = array('name', 'desc', 'review', 'chapter_id');

    public function tasks()
    {
        // Each chapter has many tasks
        return $this->hasMany('Task', 'subchapter_id')
            ->orderBy('chapter_order', 'ASC')
            ->select(array('id', 'difficulty', 'chapter_order'));
    }

    public function tasksActive()
    {
        return $this->hasMany('Task', 'subchapter_id')->where('active', 1)->select(array('id', 'difficulty', 'chapter_order'));
    }

    public function chapter()
    {
        return $this->belongsTo('Chapter');
    }

    public function book()
    {
        return $this->belongsTo('book');
    }
}

