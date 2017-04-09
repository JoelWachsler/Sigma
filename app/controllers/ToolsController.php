<?php

/*
\ Settings for later
*/

class ToolsController extends BaseController {
	
	public function home()
	{
		return View::make('tools')->with('data', $this->data);
	}
}
