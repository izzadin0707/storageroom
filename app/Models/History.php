<?php

namespace App\Models;

use CodeIgniter\Model;

class History extends Model
{
    protected $db;
    protected $table = "history";
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_location', 'id_product', 'id_users', 'id_category', 'qty', 'created_at'];

    public function __construct() {
        $this->db = db_connect();
        $this->builder = $this->db->table($this->table);
    }

    public function get() {
        return $this->builder->select('history.*, l.nama as location, p.nama as product, u.username as created_name, c.nama as type')
        ->join('location as l', 'l.id = history.id_location', 'left')
        ->join('product as p', 'p.id = history.id_product', 'left')
        ->join('users as u', 'u.id = history.id_users', 'left')
        ->join('category as c', 'c.id = history.id_category', 'left')
        ->get()->getResultArray();
    }

    public function getOne($filter = []) {
        $x = $this->builder->select('history.*, l.nama as location, p.nama as product, u.username as created_name, c.nama as type')
        ->join('location as l', 'l.id = history.id_location', 'left')
        ->join('product as p', 'p.id = history.id_product', 'left')
        ->join('users as u', 'u.id = history.id_users', 'left')
        ->join('category as c', 'c.id = history.id_category', 'left');
        
        if (isset($filter['id'])) {
            return $x->where('id', $filter['id'])->get()->getRowArray();
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
