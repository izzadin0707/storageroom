<?php

namespace App\Controllers\Main;

use App\Controllers\BaseController;
use App\Models\Location;
use App\Models\Storage;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

/**
 * @property Storage storage
 * @property Location location
 * @property session session
 */

class StorageController extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->storage = new Storage();
        $this->location = new Location();

        $this->session->set('menu', 'storage');
    }

    public function index()
    {
        if (!isset(session()->user))
	    {
	        return view('login/v_login');
	    }
        return view('main/storage/v_storage', [
            'menu_title' => 'Storage'
        ]);
    }

    public function datatable()
    {
        $data = $this->location->get();
        $res = [];

        for ($i=0; $i < count($data) ; $i++) {
            $row = $data[$i];
            $storage = $this->storage->getOne(['id_location' => $row['id']])->getResultArray();
            $product_count = is_array($storage) ? count($storage) : 0;
            $row['id'] = encrypted($row['id']);
            $res['data'][] = [
                'no' => "<span>" .$i + 1 . "</span>",
                'nama' => $row['nama'],
                'qty' => $product_count,
                'action' => "
                <div class='d-flex flex-nowrap justify-content-center gap-1'>
                    <button class='btn btn-primary btn-action text-white' data-location='" . $row['nama'] . "' data-id='". $row['id'] ."' onclick='detail(this)'><i class='bx bx-menu'></i></button>
                </div>"
            ];
        }

        return $this->response->setJSON($res);
    }

    public function detailtable()
    {
        $location = $this->request->getPost('location') ?? 0;
        $data = $this->storage->getOne(['id_location' => decrypted($location)])->getResultArray() ?? [];
        $res = [];

        for ($i=0; $i < count($data) ; $i++) {
            $row = $data[$i];
            $row['id'] = encrypted($row['id']);
            $res['data'][] = [
                'no' => "<span>" .$i + 1 . "</span>",
                'code' => $row['product_code'],
                'nama' => $row['product'],
                'qty' => $row['qty'],
                'createdby' => "
                <span class='fw-semibold' style='color: #676df0;'>" . $row['created_name'] ."</span><br>
                    <span>" . date('d F Y', strtotime($row['created_at'])) . "</span>
                ",
                'action' => "
                <div class='d-flex flex-nowrap justify-content-center gap-1'>
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
        $location = $this->request->getPost('location');
        $product = $this->request->getPost('product');
        $qty = $this->request->getPost('qty');

        try {
            if (empty($product)) throw new Exception('product');
            if (empty($qty)) throw new Exception('qty');

            $data = [
                'id_product' => decrypted($product),
                'qty' => $qty,
            ];

            if (empty($id)) {
                $data['id_location'] = decrypted($location);
                $data['created_by'] = $this->session->user;
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->storage->store($data);
            } else {
                $data['updated_by'] = $this->session->user;
                $data['updated_at'] = date('Y-m-d H:i:s');
                $this->storage->update(decrypted($id), $data);

            }

            saveHistory([
                'users' => $this->session->user,
                'location' => decrypted($location),
                'product' => decrypted($product),
                'type' => 'IN',
                'qty' =>  $qty
            ]);

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

            $stg = $this->storage->getOne(['id' => decrypted($id)]);

            saveHistory([
                'users' => $this->session->user,
                'location' => $stg['id_location'],
                'product' => $stg['id_product'],
                'type' => 'OUT',
                'qty' =>  $stg['qty']
            ]);

            $this->storage->remove(decrypted($id));

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
