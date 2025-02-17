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

    public function get($filter = []) {
        $x = $this->builder->select('category.*, t.nama as type_name')
        ->join('type as t', 't.id = category.id_type', 'left');

        foreach ($filter as $key => $value) {
            if ($key == 'type_name') {
                $x->where('LOWER(t.nama)', strtolower($value));
                continue;
            }
            $x->where($key, $value);
        }
        
        return $x->get()->getResultArray();
    }

    public function getOne($filter = []) {
        $x = $this->builder->select('category.*, t.nama as type_name')
        ->join('type as t', 't.id = category.id_type', 'left');
        
        if (isset($filter['id'])) {
            return $x->where('id', $filter['id'])->get()->getRowArray();
        }

        foreach ($filter as $key => $value) {
            if ($key == 'nama') {
                $x->where('LOWER(category.nama)', strtolower($value));
                continue;
            }
            if ($key == 'type_name') {
                $x->where('LOWER(t.nama)', strtolower($value));
                continue;
            }
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