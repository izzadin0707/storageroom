<?php

use CodeIgniter\Encryption\EncrypterInterface;

function encrypted($val) {
    $encrypter = \Config\Services::encrypter();
    return base64_encode($encrypter->encrypt($val));
}

function decrypted($val) {
    $encrypter = \Config\Services::encrypter();
    return $encrypter->decrypt(base64_decode($val));
}

function saveHistory($data = [])
{
    $history = new App\Models\History();
    $category = new App\Models\Category();

    $res = [];

    $users = isset($data['users']) ? $data['users'] : null;
    $location = isset($data['location']) ? $data['location'] : null;
    $product = isset($data['product']) ? $data['product'] : null;
    $cat = isset($data['category']) ? $data['category'] : null;
    $type = isset($data['type']) ? $data['type'] : null;
    $qty = isset($data['qty']) ? $data['qty'] : null;

    try {
        if (empty($users)) throw new Exception('users');
        if (empty($location)) throw new Exception('location');
        if (empty($product)) throw new Exception('product');
        if (empty($cat)) throw new Exception('category');
        if (empty($qty)) throw new Exception('qty');

        $cat = $category->getOne(['nama' => $cat, 'type_name' => $type]);
        if (empty($cat)) throw new Exception('category');

        $data = [
            'id_users' => $users,
            'id_location' => $location,
            'id_product' => $product,
            'id_category' => $cat['id'],
            'qty' => $qty,
            'created_at' => date('Y-m-d H:i:s')
        ];

        if (empty($id)) {
            $history->store($data);
        } else {
            $history->update($id, $data);
        }

        $res['success'] = 1;
    } catch (Exception $e) {
        $res = [
            'success' => 0,
            'error' => $e->getMessage()
        ];
    }

    return json_encode($res);
}