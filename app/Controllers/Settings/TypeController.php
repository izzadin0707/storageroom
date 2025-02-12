<?php

namespace App\Controllers\Settings;

use App\Controllers\BaseController;
use App\Models\Type;
use Exception;

/**
 * @property Type type
 * @property session session
 */

class TypeController extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->type = new Type();

        $this->session->set('menu', 'type');
    }

    public function index()
    {
        if (!isset(session()->user)) {
            return view('login/v_login');
        }
        return view('settings/type/v_type', [
            'menu_title' => 'type'
        ]);
    }

    public function datatable()
    {
        $data = $this->type->get();
        $res = [];

        for ($i = 0; $i < count($data); $i++) {
            $row = $data[$i];
            $row['id'] = encrypted($row['id']);
            $res['data'][] = [
                'no' => "<span>" . $i + 1 . "</span>",
                'nama' => $row['nama'],
                'action' => "
                <div class='d-flex flex-nowrap justify-content-center gap-1'>
                    <button class='btn-edit btn btn-warning btn-action text-white' data-row='" . json_encode($row) . "' onclick='edit(this)'><i class='bx bx-edit'></i></button>
                    <button class='btn btn-danger btn-action text-white' data-id='" . $row['id'] . " 'onclick='deleted(this)'><i class='bx bx-trash'></i></button>
                </div>"
            ];
        }

        return $this->response->setJSON($res);
    }

    public function select()
    {
        $data = $this->type->get();
        $res = [];

        foreach ($data as $row) {
            $res['data'][] = [
                'value' => encrypted($row['id']),
                'text' => $row['nama'],
            ];
        }

        return $this->response->setJSON($res);
    }

    public function save()
    {
        $res = [];
        $id = $this->request->getPost('id');
        $nama = $this->request->getPost('nama');

        try {
            if (empty($nama)) throw new Exception('nama');

            $data = [
                'nama' => $nama
            ];

            if (empty($id)) {
                $this->type->store($data);
            } else {
                $this->type->update(decrypted($id), $data);
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

            $this->type->remove(decrypted($id));

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