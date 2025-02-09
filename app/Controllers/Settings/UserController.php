<?php

namespace App\Controllers\Settings;

use App\Controllers\BaseController;
use App\Models\Users;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

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

    public function datatable()
    {
        $data = $this->users->get();
        $res = [];

        for ($i=0; $i < count($data) ; $i++) {
            $row = $data[$i];
            $row['id'] = encrypted($row['id']);
            $row['password'] = decrypted($row['password']);
            $res['data'][] = [
                'no' => "<span>" .$i + 1 . "</span>",
                'username' => $row['username'],
                'password' => $row['password'],
                'rolename' => $row['rolename'],
                'action' => "
                <div class='d-flex flex-nowrap justify-content-center gap-1'>
                    <button class='btn-edit btn btn-warning btn-action text-white' data-row='". json_encode($row) ."' onclick='edit(this)'><i class='bx bx-edit'></i></button>
                    <button class='btn btn-danger btn-action text-white' data-id='". $row['id'] ." 'onclick='deleted(this)'><i class='bx bx-trash'></i></button>
                </div>"
            ];
        }

        return $this->response->setJSON($res);
    }

    public function save()
    {
        $res = [];
        $id = $this->request->getPost('id');
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $role = $this->request->getPost('role');

        try {
            if (empty($username)) throw new Exception('username');
            if (empty($password)) throw new Exception('password');
            if (empty($role)) throw new Exception('role');

            $data = [
                'username' => $username,
                'password' => encrypted($password),
                'roleid' => decrypted($role)
            ];

            if (empty($id)) {
                $this->users->store($data);
            } else {
                $this->users->update(decrypted($id), $data);
            }

            $res['success'] = 1;
        } catch (Exception $e) {
            $res = [
                'success' => 0,
                'error' => $e->getMessage()
            ];
        }

        return $this->response->setJSON($res);
    }

    public function delete()
    {
        $res = [];
        $id = $this->request->getPost('id');

        try {
            if (empty($id)) throw new Exception('Id Required!');

            $this->users->remove(decrypted($id));

            $res['success'] = 1;
        } catch (Exception $e) {
            $res = [
                'success' => 0,
                'error' => $e->getMessage()
            ];
        }

        return $this->response->setJSON($res);
    }
}
