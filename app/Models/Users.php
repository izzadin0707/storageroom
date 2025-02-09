<?php

namespace App\Models;

use CodeIgniter\Model;

class Users extends Model
{
    protected $db;
    protected $table = "users";
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'roleid'];

    public function __construct() {
        $this->db = db_connect();
        $this->builder = $this->db->table($this->table);
    }

    public function get() {
        return $this->builder->select('users.*, r.rolename')
        ->join('role as r', 'r.id = users.roleid', 'left')
        ->get()->getResultArray();
    }

    public function getOne($filter = []) {
        $x = $this->builder->select('users.*, r.rolename')
        ->join('role as r', 'r.id = users.roleid', 'left');
        
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
