<?php

namespace App\Controllers\Main;

use App\Controllers\BaseController;
use App\Models\location;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

/**
 * @property Location location
 * @property session session
 */

class LocationController extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->location = new Location();

        $this->session->set('menu', 'location');
    }

    public function index()
    {
        if (!isset(session()->user))
	    {
	        return view('login/v_login');
	    }
        return view('Main/location/v_location', [
            'menu_title' => 'Location'
        ]);
    }

    public function datatable()
    {
        $data = $this->location->get();
        $res = [];

        for ($i=0; $i < count($data) ; $i++) {
            $row = $data[$i];
            $row['id'] = encrypted($row['id']);
            $res['data'][] = [
                'no' => "<span>" .$i + 1 . "</span>",
                'nama' => $row['nama'],
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
        $data = $this->location->get();
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
                $this->location->store($data);
            } else {
                $this->location->update(decrypted($id), $data);
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

            $this->location->remove(decrypted($id));

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
