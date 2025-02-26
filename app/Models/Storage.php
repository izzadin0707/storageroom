<?php

namespace App\Models;

use CodeIgniter\Model;

class Storage extends Model
{
    protected $db;
    protected $table = "storage";
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_location', 'id_product', 'qty', 'created_by', 'created_at', 'updated_by', 'updated_at'];

    public function __construct() {
        $this->db = db_connect();
        $this->builder = $this->db->table($this->table);
    }

    public function get() {
        return $this->builder->select('storage.*, l.nama as location, p.nama as product, p.code as product_code, u.username as created_name')
        ->join('location as l', 'l.id = storage.id_location', 'left')
        ->join('product as p', 'p.id = storage.id_product', 'left')
        ->join('users as u', 'u.id = storage.created_by', 'left')
        ->get()->getResultArray();
    }

    public function getOne($filter = []) {
        $x = $this->builder->select('storage.*, l.nama as location, p.nama as product, p.code as product_code, u.username as created_name')
        ->join('location as l', 'l.id = storage.id_location', 'left')
        ->join('product as p', 'p.id = storage.id_product', 'left')
        ->join('users as u', 'u.id = storage.created_by', 'left');
        
        if (isset($filter['id'])) {
            return $x->where('storage.id', $filter['id'])->get()->getRowArray();
        }

        foreach ($filter as $key => $value) {
            $x->where($key, $value);
        }

        return $x->get();
    }

    public function store($data) {
        return $this->builder->insert($data);
    }

    public function edit($id, $data) {
        $res = $data;
        return $this->builder->where('id', $id)->update($res);
    }

    public function remove($id) {
        return $this->builder->delete(['id' => $id]);
    }
}
