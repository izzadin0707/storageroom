<?php

namespace App\Controllers\Settings;

use App\Controllers\BaseController;
use App\Models\category;
use Exception;

/**
 * @property Category category
 * @property session session
 */

class CategoryController extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->category = new Category();

        $this->session->set('menu', 'category');
    }

    public function index()
    {
        if (!isset(session()->user)) {
            return view('login/v_login');
        }
        return view('settings/category/v_category', [
            'menu_title' => 'category'
        ]);
    }

    public function datatable()
    {
        $data = $this->category->get();
        $res = [];

        for ($i = 0; $i < count($data); $i++) {
            $row = $data[$i];
            $row['id'] = encrypted($row['id']);
            $res['data'][] = [
                'no' => "<span>" . $i + 1 . "</span>",
                'nama' => $row['nama'],
<<<<<<< HEAD
                'id_type' => $row['nama_type'],
=======
                'type' => $row['type_name'],
>>>>>>> 890a278d2f6fa5c29e8ae706bcbebb289925b1d2
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
        $type = $this->request->getPost('type');
        $data = $this->category->get(['type_name' => $type]);
        $res = [];
        $res['data'] = [];

<<<<<<< HEAD
        foreach ($data as $row) {
            $res['data'][] = [
                'value' => encrypted($row['id']),
                'text' => $row['nama'],
                'text' => $row['id_type'],
            ];
=======
        if (is_array($data)) {
            foreach ($data as $row) {
                $res['data'][] = [
                    'value' => encrypted($row['id']),
                    'text' => $row['nama'],
                ];
            }
>>>>>>> 890a278d2f6fa5c29e8ae706bcbebb289925b1d2
        }

        return $this->response->setJSON($res);
    }

    public function save()
    {
        $res = [];
        $id = $this->request->getPost('id');
        $nama = $this->request->getPost('nama');
<<<<<<< HEAD
        $nama = $this->request->getPost('id_type');
=======
        $type = $this->request->getPost('type');
>>>>>>> 890a278d2f6fa5c29e8ae706bcbebb289925b1d2

        try {
            if (empty($nama)) throw new Exception('nama');
            if (empty($type)) throw new Exception('type');

            $data = [
                'nama' => $nama,
                'id_type' => decrypted($type)
            ];

            if (empty($id)) {
                $this->category->store($data);
            } else {
                $this->category->update(decrypted($id), $data);
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

            $this->category->remove(decrypted($id));

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