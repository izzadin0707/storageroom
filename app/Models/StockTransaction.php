<?php

namespace App\Models;

use CodeIgniter\Model;

class StockTransaction extends Model
{
    protected $db;
    protected $table = "stock_transaction";
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_location', 'id_category', 'transcode', 'date', 'note', 'isrelease', 'created_by', 'created_at', 'updated_by', 'updated_at'];

    public function __construct() {
        $this->db = db_connect();
        $this->builder = $this->db->table($this->table);
    }

    public function get() {
        return $this->builder->select('stock_transaction.*, l.nama as location, c.nama as category, u.username as created_name')
        ->join('location as l', 'l.id = stock_transaction.id_location', 'left')
        ->join('category as c', 'c.id = stock_transaction.id_category', 'left')
        ->join('users as u', 'u.id = stock_transaction.created_by', 'left')
        ->get()->getResultArray();
    }

    public function getOne($filter = []) {
        $x = $this->builder->select('stock_transaction.*, l.nama as location, c.nama as category, u.username as created_name')
        ->join('location as l', 'l.id = stock_transaction.id_location', 'left')
        ->join('category as c', 'c.id = stock_transaction.id_category', 'left')
        ->join('users as u', 'u.id = stock_transaction.created_by', 'left');
        
        if (isset($filter['id'])) {
            return $x->where('stock_transaction.id', $filter['id'])->get()->getRowArray();
        }

        foreach ($filter as $key => $value) {
            $x->where($key, $value);
        }

        return $x->get();
    }

    public function store($data) {
        $this->builder->insert($data);
        return $this->db->insertID();
    }

    public function edit($id, $data) {
        $res = $data;
        return $this->builder->where('id', $id)->update($res);
    }

    public function remove($id) {
        return $this->builder->delete(['id' => $id]);
    }
}
