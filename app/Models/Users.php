<?php

namespace App\Models;

use CodeIgniter\Model;

class Users extends Model
{
    protected $db;
    protected $table = "users as a";

    public function __construct() {
        $this->db = db_connect();
        $this->builder = $this->db->table($this->table);
    }

    public function get() {
        return $this->builder->get()->getResultArray();
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
}
