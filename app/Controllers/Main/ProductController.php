<?php

namespace App\Controllers\Main;

use App\Controllers\BaseController;
use App\Models\Product;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

/**
 * @property Product product
 * @property session session
 */

class ProductController extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->product = new Product();

        $this->session->set('menu', 'product');
    }

    public function index()
    {
        if (!isset(session()->user))
	    {
	        return view('login/v_login');
	    }
        return view('main/product/v_product', [
            'menu_title' => 'Product'
        ]);
    }

    public function datatable()
    {
        $data = $this->product->get();
        $res = [];

        for ($i=0; $i < count($data) ; $i++) {
            $row = $data[$i];
            $row['id'] = encrypted($row['id']);
            $res['data'][] = [
                'no' => "<span>" .$i + 1 . "</span>",
                'code' => $row['code'],
                'nama' => $row['nama'],
                'category' => $row['category'],
                'uom' => $row['uom'],
                'expired' => $row['expired'],
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
        $search = $this->request->getPost('search');
        $data = $this->product->getOne(['search' => $search])->getResultArray();
        $res = [];
        $res['data'] = [];

        if (is_array($data)) {
            foreach ($data as $row) {
                $res['data'][] = [
                    'value' => encrypted($row['id']),
                    'text' => $row['code'] . ' - ' . $row['nama'],
                ];
            }
        }

        return $this->response->setJSON($res);
    }

    public function save()
    {
        $res = [];
        $id = $this->request->getPost('id');
        $code = $this->request->getPost('code');
        $nama = $this->request->getPost('nama');
        $description = $this->request->getPost('description');
        $expired = $this->request->getPost('expired');
        $category = $this->request->getPost('category');
        $uom = $this->request->getPost('uom');

        try {
            if (empty($code)) throw new Exception('code');
            if (empty($nama)) throw new Exception('nama');
            if (empty($description)) throw new Exception('description');
            if (empty($expired)) throw new Exception('expired');
            if (empty($category)) throw new Exception('category');
            if (empty($uom)) throw new Exception('uom');

            $data = [
                'code' => $code,
                'nama' => $nama,
                'description' => $description,
                'expired' => $expired,
                'id_category' => decrypted($category),
                'id_uom' => decrypted($uom),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            if (empty($id)) {
                $this->product->store($data);
            } else {
                $this->product->update(decrypted($id), $data);
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

            $this->product->remove(decrypted($id));

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
