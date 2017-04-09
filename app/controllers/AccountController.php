<?php

/**
 * AccountController, controls the account
 **/

class AccountController extends BaseController {
	
    public function main()
    {
        return View::make('account.login_register');
    }

	public function postSignIn()
	{
        $validator = Validator::make(Input::all(),
            array(
                'username'  =>  'required',
                'password'  =>  'required'
            )
        );

        if ($validator->fails())
        {
            return $validator->messages()->toJson();
            // Redirect to sign-in page
            //return Redirect::route('home-sign-in')
                   //->withErrors($validator)
                   //->withInput();
        }
        else
        {
            // Attempt user sign in
            $auth = Auth::attempt(array(
                'username'     =>   Input::get('username'),
                'password'     =>   Input::get('password'),
                'active'       =>   1
            ));

            if ($auth)
            {
                // Redirect to the intended page
                return 'true';
                //return Redirect::intended('/');
            }
            else
            {
                $message = 'Inlogging misslyckades, fel inloggningsuppgifter eller så har du inte aktiverat ditt konto.';
                return $message;
                //return Redirect::route('home-sign-in')
                        //->with('global', 'Inlogging misslyckades, fel inloggningsuppgifter eller så har du inte aktiverat ditt konto.');
            }
        }

        $message = 'Ett problem har uppstått, har du aktiverat ditt konto?';
        return $message;
        //return Redirect::route('home-sign-in')
                //->with('glboal', 'Ett problem har uppstått, har du aktiverat ditt konto?');
	}

	public function getActivate()
	{
        return 'Not implemented yet...';
	}

    public function getSignOut()
    {
        Auth::logout();
        return Redirect::route('home-sign-in');
    }

	public function postCreate()
	{
		$validator = Validator::make(Input::all(),
			array(
				'email'				=>	'required|max:50|email|unique:users',
				'username'			=>	'required|between:3,32|unique:users',
				'first_name'	    =>	'required|between:3,32',
				'last_name'	        =>	'required|between:3,32',
				'password'			=>	'required|between:6,32',
				'password_again'	=>	'required|same:password'
			)
		);

		if ($validator->fails())
		{
            return $validator->messages()->toJson();
			//return Redirect::route('home-sign-in')
					//->withErrors($validator)
					//->withInput();
		}
		else
		{
            $first_name = Input::get('first_name');
            $last_name  = Input::get('last_name');
			$email 		= Input::get('email');
			$username 	= Input::get('username');
			$password 	= Input::get('password');

			// Activation code
			$code 		= str_random(60);

			$user = User::create(array(
                'first_name'=>  $first_name,
                'last_name' =>  $last_name,
				'email'		=>	$email,
				'username'	=>	$username,
				'password'	=>	Hash::make($password),
				'code'		=>	$code,
				'active'	=>	0
			));

			if ($user)
			{
                $message = 'Ditt konto har blivit skapad, gå till din email för att aktivera det'; 
                return $message;
				//return Redirect::route('home-sign-in')->with('global', 'Ditt konto har blivit skapad, gå till din email för att aktivera det');
			}
		}
	}
}
