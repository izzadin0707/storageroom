<?php

namespace App\Controllers;

/**
 * @property session session
 */

class Home extends BaseController
{
    public function __construct()
    {
        $this->session = session();

        $this->session->set('menu', '');
    }

    public function index(): string
    {
        if (!isset(session()->user))
	    {
	        return view('login/v_login');
	    }
        return view('welcome_message');
    }
}
