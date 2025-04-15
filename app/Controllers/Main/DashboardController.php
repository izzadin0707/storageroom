<?php

namespace App\Controllers\Main;

use App\Controllers\BaseController;
use App\Models\History;
use App\Models\Location;
use App\Models\Product;
use App\Models\Storage;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * @property History history
 * @property Product product
 * @property Storage storage
 * @property Location location
 * @property session session
 */

class DashboardController extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->history = new History();
        $this->product = new Product();
        $this->storage = new Storage();
        $this->location = new Location();

        $this->session->set('menu', 'dashboard');
    }

    public function index()
    {
        if (!isset(session()->user))
	    {
	        return view('login/v_login');
	    }
        return view('main/dashboard/v_dashboard', [
            'menu_title' => 'Dashboard',
            'product' => count($this->product->get()),
            'location' => count($this->location->get()),
            'storage' => count($this->storage->get()),
            'history' => count($this->history->get()),
        ]);
    }

    public function datatable()
    {
        $data = $this->storage->getDashboardTable();
        $res = [];

        for ($i=0; $i < count($data) ; $i++) {
            $row = $data[$i];
            $res['data'][] = [
                'no' => "<span>" .$i + 1 . "</span>",
                'product' => $row['code'] . ' - ' . $row['product_name'],
                'description' => $row['description'],
                'uom' => $row['category_name'],
                'qty' => "<span style='text-align: end;'>" . $row['total_qty'] . "</span>",
                'location' => $row['location_names'] ?? '-'
            ];
        }

        return $this->response->setJSON($res);
    }
}