<?php

/*
* @author Joel Wachsler
* @version 1.0
* @description Model for Classroom Activation
*/

class ClassroomActivation extends Eloquent
{
    protected $table = 'classroom_activations';
    protected $fillable = ['user_id', 'classroom_id', 'activation_key'];
}
