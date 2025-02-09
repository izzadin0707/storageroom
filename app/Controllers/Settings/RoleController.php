<?php

namespace App\Controllers\Settings;

use App\Controllers\BaseController;
use App\Models\Role;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

/**
 * @property Role role
 * @property session session
 */

class RoleController extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->role = new Role();

        $this->session->set('menu', 'role');
    }

    public function index()
    {
        if (!isset(session()->user))
	    {
	        return view('login/v_login');
	    }
        return view('settings/role/v_role', [
            'menu_title' => 'Role'
        ]);
    }

    public function datatable()
    {
        $data = $this->role->get();
        $res = [];

        for ($i=0; $i < count($data) ; $i++) {
            $row = $data[$i];
            $row['id'] = encrypted($row['id']);
            $res['data'][] = [
                'no' => "<span>" .$i + 1 . "</span>",
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

    public function select()
    {
        $data = $this->role->get();
        $res = [];

        foreach ($data as $row) {
            $res['data'][] = [
                'value' => encrypted($row['id']),
                'text' => $row['rolename'],
            ];
        }

        return $this->response->setJSON($res);
    }

    public function save()
    {
        $res = [];
        $id = $this->request->getPost('id');
        $rolename = $this->request->getPost('rolename');

        try {
            if (empty($rolename)) throw new Exception('rolename');

            $data = [
                'rolename' => $rolename
            ];

            if (empty($id)) {
                $this->role->store($data);
            } else {
                $this->role->update(decrypted($id), $data);
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

            $this->role->remove(decrypted($id));

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
