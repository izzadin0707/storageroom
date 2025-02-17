<?php

namespace App\Models;

use CodeIgniter\Model;

class Category extends Model
{
    protected $db;
    protected $table = "category as a";
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama', 'nama_type'];

    public function __construct() {
        $this->db = db_connect();
        $this->builder = $this->db->table($this->table);
    }

    public function get() {
        return $this->builder->select('a.*, b.nama as nama_type')
            ->join('type as b', 'b.id = a.id_type')
            ->get()->getResultArray();
    }

    public function getOne($filter = []) {
        $x = $this->builder;
        
        if (isset($filter['id'])) {
            return $x->where('id', $filter['id'])->get()->getRowArray();
        }

        foreach ($filter as $key => $value) {
            $x->where($key, $value);
        }

        return $x->get()->getRowArray();
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