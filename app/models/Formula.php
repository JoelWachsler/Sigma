<?php

/*
* @author: Joel Wachsler
* @version: 1.0
* @description: Model for formulas
*/

class Formula extends Eloquent {

    protected $table = 'formulas';
    protected $fillable = array('name', 'content', 'active', 'height');

    /**
     * This function seraches the database
     * for entries like the one provided
     * @param string
     * @return array
     **/
    public static function search($name)
    {
     // Columns to get
     $columns = array(
        'id',
        'name'
     );
     if ($name)
         $result = Formula::where('name', 'LIKE', "%$name%")->where('active', '=', 1)->orderBy('searched_times', 'DESC')->get($columns);
     else
         $result = array();
     return $result;
    }

    /**
     * Update by one when searched for
     * @param string
     * @return bool
     **/
    public static function get_formula($id, $active = true)
    {
        try
        {
            if ($active)
                $instance = Formula::where('id', '=', $id)->where('active', '=', 1)->firstOrFail();
            else
                $instance = Formula::where('id', '=', $id)->firstOrFail();
            $instance->searched_times += 1;
            $instance->save();

            return $instance;
        }
        catch(Exception $e)
        {
            return "";
        }
    }
}

