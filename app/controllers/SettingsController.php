<?php

/**
 * Settings for later
 * This file needs to be updated
 **/

class SettingsController extends BaseController {
	
	public function home()
	{
        if (Input::has('first_name'))
        {
            $user = Auth::user();
            if ($user->first_name != Input::get('first_name'))
            {
                $user->first_name = Input::get('first_name');
                $user->save();
                Session::flash('update_first_name', 'Ditt förnamn har ändrats!');
            }
        }

        if (Input::has('last_name'))
        {
            $user = Auth::user();
            if ($user->last_name != Input::get('last_name'))
            {
                $user->last_name = Input::get('last_name');
                $user->save();
                Session::flash('update_last_name', 'Ditt efternamn har ändrats!');
            }
        }

        if (Input::has('email'))
        {
            $user = Auth::user();
            if ($user->email != Input::get('email'))
            {
                $user->email = Input::get('email');
                $user->save();
                Session::flash('update_email', 'Email ändrat!');
            }
        }

        if (Input::has('old_password', 'new_password', 'new_password_again'))
        {
            $data = array(
                'email' => Input::get('email')
            );
            
            $rules = array(
                'old_password'          =>  'required|between:6,32',
                'new_password'          =>  'required|between:6,32',
                'new_password_again'    =>  'required|same:new_password'
            );

            $validator = Validator::make(Input::all(), $rules);
            
            $user = Auth::user();
            if ($validator->fails())
                Session::flash('update_password', $validator->errors()->getMessages());
            else if(!Hash::check(Input::get('old_password'), $user->password))
                Session::flash('update_password', 'Ditt gamla lösenord matchar inte');
            else
            {
                $user->password = Hash::make(Input::get('new_password'));
                $user->save();
                Session::flash('update_password', 'Lösenord ändrat!');
            }
        }

		return View::make('settings')->with('data', $this->data);
	}
}
