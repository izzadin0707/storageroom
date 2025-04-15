<?php

namespace App\Controllers\Main;

use App\Controllers\BaseController;
use App\Models\Category;
use App\Models\Location;
use App\Models\Storage;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

/**
 * @property Storage storage
 * @property Location location
 * @property Category category
 * @property session session
 */

class StorageController extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->storage = new Storage();
        $this->location = new Location();
        $this->category = new Category();

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
            $row['id_product'] = encrypted($row['id_product']);
            $res['data'][] = [
                'no' => "<span>" .$i + 1 . "</span>",
                'nama' => $row['product'],
                'qty' => $row['qty'],
                'createdby' => "
                <span class='fw-semibold' style='color: #676df0;'>" . $row['created_name'] ."</span><br>
                    <span>" . date('d F Y', strtotime($row['created_at'])) . "</span>
                ",
                'action' => "
                <div class='d-flex flex-nowrap justify-content-center gap-1'>
                    <button class='btn btn-success btn-action text-white' data-id='". $row['id'] ."' data-product='". $row['product'] ."' data-qty='". $row['qty'] ."' onclick='transaction(this)'><i class='bx bx-transfer'></i></button>
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

            $category = null;

            if (empty($id)) {
                $data['id_location'] = decrypted($location);
                $data['created_by'] = $this->session->user;
                $data['created_at'] = date('Y-m-d H:i:s');
                $this->storage->store($data);
                $category = 'INITIAL';
            } else {
                $data['updated_by'] = $this->session->user;
                $data['updated_at'] = date('Y-m-d H:i:s');
                $this->storage->update(decrypted($id), $data);
                $category = 'UPDATE';
                
            }

            saveHistory([
                'users' => $this->session->user,
                'location' => decrypted($location),
                'product' => decrypted($product),
                'category' => $category,
                'type' => 'Storage',
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

    public function transaction() {
        $res = [];
        $id = $this->request->getPost('id');
        $type = $this->request->getPost('type');
        $qty = $this->request->getPost('qty');

        try {
            if (empty($type)) throw new Exception('type');
            if (empty($qty)) throw new Exception('qty');

            $storage = $this->storage->getOne(['id' => decrypted($id)]) ?? [];

            if (!empty($storage)) {

                $data = [
                    'qty' => $qty,
                ];
    
                $category = $this->category->getOne(['id' => decrypted($type)])['nama'] ?? 'IN';
    
                if ($category == 'IN') {
                    $data['qty'] = $storage['qty'] + $qty;
                } else if ($category == 'OUT' && $storage['qty'] >= $qty) {
                    $data['qty'] = $storage['qty'] - $qty;
                } else {
                    throw new Exception('less');
                }

                $data['updated_by'] = $this->session->user;
                $data['updated_at'] = date('Y-m-d H:i:s');
                $this->storage->update(decrypted($id), $data);
    
                saveHistory([
                    'users' => $this->session->user,
                    'location' => $storage['id_location'],
                    'product' => $storage['id_product'],
                    'category' => $category,
                    'type' => 'Transaction',
                    'qty' =>  $qty
                ]);
    
    
                $res['success'] = 1;
            } else {
                $res['success'] = 0;
                $res['error'] = 'Storage not found!';
            }
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
