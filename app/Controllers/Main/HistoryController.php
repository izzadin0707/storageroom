<?php

namespace App\Controllers\Main;

use App\Controllers\BaseController;
use App\Models\Location;
use App\Models\History;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

/**
 * @property History history
 * @property Location location
 * @property session session
 */

class HistoryController extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->history = new History();
        $this->location = new Location();

        $this->session->set('menu', 'history');
    }

    public function index()
    {
        if (!isset(session()->user))
	    {
	        return view('login/v_login');
	    }
        return view('main/history/v_history', [
            'menu_title' => 'History'
        ]);
    }

    public function datatable()
    {
        $data = $this->history->get();
        $res = [];

        for ($i=0; $i < count($data) ; $i++) {
            $row = $data[$i];
            $row['id'] = encrypted($row['id']);
            $color = strtolower($row['type']) == 'in' ? 'black' : 'red';
            $min = strtolower($row['type']) == 'out' ? '-' : '';
            $res['data'][] = [
                'no' => "<span>" .$i + 1 . "</span>",
                'product' => $row['product'],
                'location' => $row['location'],
                'type' => $row['type'],
                'qty' => "<span style='color: $color;'>$min" . $row['qty'] . "</span>",
                'createdby' => "
                    <span class='fw-semibold' style='color: #676df0;'>" . $row['created_name'] ."</span><br>
                    <span>" . date('d F Y', strtotime($row['created_at'])) . "</span>
                "
            ];
        }

        return $this->response->setJSON($res);
    }
}
