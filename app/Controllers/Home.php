<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        if (!isset(session()->user))
	    {
	        return view('login/v_login');
	    }
        return view('welcome_message');
    }
}
