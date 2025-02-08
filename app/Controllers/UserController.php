<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Users;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * @property Users users
 * @property session session
 */

class UserController extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->users = new Users();

        $this->session->set('menu', 'users');
    }

    public function index()
    {
        if (!isset(session()->user))
	    {
	        return view('login/v_login');
	    }
        return view('settings/users/v_users', [
            'menu_title' => 'Users'
        ]);
    }
}
