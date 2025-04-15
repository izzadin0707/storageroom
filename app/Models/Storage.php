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
        return $this->builder->select('storage.*, l.nama as location, p.nama as product, u.username as created_name')
        ->join('location as l', 'l.id = storage.id_location', 'left')
        ->join('product as p', 'p.id = storage.id_product', 'left')
        ->join('users as u', 'u.id = storage.created_by', 'left')
        ->get()->getResultArray();
    }

    public function getOne($filter = []) {
        $x = $this->builder->select('storage.*, l.nama as location, p.nama as product, u.username as created_name')
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

    public function getDashboardTable() {
        return $this->db->table('product as b')
            ->select('
                b.code,
                b.nama as product_name,
                b.description,
                c.nama as category_name,
                GROUP_CONCAT(DISTINCT d.nama ORDER BY d.nama SEPARATOR \', \') as location_names,
                SUM(COALESCE(a.qty, 0)) as total_qty
            ')
            ->join('storage as a', 'b.id = a.id_product', 'left')
            ->join('category as c', 'c.id = b.id_uom', 'left')
            ->join('location as d', 'd.id = a.id_location', 'left')
            ->groupBy(['b.code', 'b.nama', 'b.description', 'c.nama'])
            ->get()
            ->getResultArray();
    }
}
