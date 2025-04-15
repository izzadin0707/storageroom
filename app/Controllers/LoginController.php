<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\Users;
use CodeIgniter\HTTP\Request;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * @property Users users
 * @property session session
 */

class LoginController extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->users = new Users();
    }

    public function index()
    {
        return view('login/v_login', [
            'users' => $this->users->get()
        ]);
    }

    public function login()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $res = [];
        
        $user = $this->users->getOne(['username' => $username]);
        if ($user) {
            if (decrypted($user['password']) == $password) {
                $this->session->set([
                    'user' => $user['id'],
                    'username' => $user['username'],
                    'role' => $user['rolename'],
                ]);
                $res['success'] = 1;
            } else {
                $res['success'] = 0;
            }
        } else {
            $res['success'] = 0;
        }

        return $this->response->setJSON($res);
    }

    public function logout()
    {
        $this->session->destroy();
        $this->session->start();

        $res['success'] = empty($this->session->get('user')) ? 1 : 0;

        return $this->response->setJSON($res);
    }
}
