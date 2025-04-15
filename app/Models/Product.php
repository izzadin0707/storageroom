<?php

namespace App\Models;

use CodeIgniter\Model;

class Product extends Model
{
    protected $db;
    protected $table = "product";
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_category', 'id_uom', 'code', 'nama', 'description', 'expired', 'created_at', 'updated_at'];

    public function __construct() {
        $this->db = db_connect();
        $this->builder = $this->db->table($this->table);
    }

    public function get() {
        return $this->builder->select('product.*, c.nama as category, u.nama as uom')
        ->join('category as c', 'c.id = product.id_category', 'left')
        ->join('category as u', 'u.id = product.id_uom', 'left')
        ->get()->getResultArray();
    }

    public function getOne($filter = []) {
        $x = $this->builder->select('product.*, c.nama as category, u.nama as uom')
        ->join('category as c', 'c.id = product.id_category', 'left')
        ->join('category as u', 'u.id = product.id_uom', 'left');
        
        if (isset($filter['id'])) {
            return $x->where('id', $filter['id'])->get()->getRowArray();
        }

        foreach ($filter as $key => $value) {
            if ($key == 'search') {
                $x->where('LOWER(product.code) LIKE', '%'.strtolower($value).'%')->orWhere('LOWER(product.nama) LIKE', '%'.strtolower($value).'%');
                continue;
            }
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
