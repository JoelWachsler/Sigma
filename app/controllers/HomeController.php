<?php

class HomeController extends BaseController {

	public function home()
	{
        // Pass default data to the view
        $this->data['courses']  = Book::where('active', '=', 1);
        $this->data['chapters'] = Book::first()->chapters;
        // Change this later for other courses
        $this->data['course']   = 1;

		return View::make('home')->with('data', $this->data);
	}
}
