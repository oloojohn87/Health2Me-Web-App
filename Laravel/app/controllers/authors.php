<?php

class AuthorsController extends BaseController {
	//public $restful = true;
	
	public function getIndex() {
		$view = View::make('authors.index', array('name' => 'Kyle Austin'))
            ->with('age', 26);
        $view->location = 'Dallas';
        $view['specialty'] = 'PHP';
        return $view;
            
	}
}