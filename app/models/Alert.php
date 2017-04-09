<?php

/*
* @author Joel Wachsler
* @version 1.0
* @description Model for Alerts
*/

class Alert extends Eloquent {

    protected $table = 'alerts';
    protected $fillable = ['user_id', 'type_id', 'message', 'link_to_alert'];

    public function type()
    {
    	$this->hasOne('type');
    }
}
