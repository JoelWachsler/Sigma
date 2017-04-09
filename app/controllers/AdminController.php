<?php

class AdminController extends BaseController {
	
	public function home()
	{
        $data = new stdClass();
        $url = new stdClass();
        $url->update_user   = action('AdminController@update_user');
        $url->accounts      = action('AdminController@accounts');
        $data->url = $url;
        $data->accounts     = $this->accounts();
        //$data->accounts = accounts();

        return View::make('admin')->with('data', $data);
	}

    /**
     * Get all accounts
     **/
    public function accounts()
    {
        $users = User::all(['id', 'username', 'first_name', 'last_name', 'active', 'email', 'account_type']);
        return $users;
    }

    /**
     * Update user
     **/
    public function update_user()
    {
        $user_id    = Input::get('id');
        $user       = User::find(['id' => $user_id])->first();
        $user->fill(Input::all());
        $user->save();

        return Redirect::to(action('AdminController@home'));
    }
}

