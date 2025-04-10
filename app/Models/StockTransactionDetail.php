<?php

namespace App\Models;

use CodeIgniter\Model;

class StockTransactionDetail extends Model
{
    protected $db;
    protected $table = "stock_transaction_detail";
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_transaction', 'id_product', 'qty', 'note'];

    public function __construct() {
        $this->db = db_connect();
        $this->builder = $this->db->table($this->table);
    }

    public function get() {
        return $this->builder->select('stock_transaction_detail.*, p.nama as product')
        ->join('product as p', 'p.id = stock_transaction_detail.id_product', 'left')
        ->get()->getResultArray();
    }

    public function getOne($filter = []) {
        $x = $this->builder->select('stock_transaction_detail.*, p.nama as product')
        ->join('product as p', 'p.id = stock_transaction_detail.id_product', 'left');
        
        if (isset($filter['id'])) {
            return $x->where('stock_transaction_detail.id', $filter['id'])->get()->getRowArray();
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
