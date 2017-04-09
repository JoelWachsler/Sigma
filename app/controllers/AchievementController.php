<?php

/*
\ Profile for later
*/

class AchievementController extends BaseController {
	
	public function home()
	{
		return View::make('achievement')->with('data', $this->data);
	}
}
