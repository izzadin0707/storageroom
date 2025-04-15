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
            $color = strtolower($row['type']) == 'out' ? 'red' : 'black';
            $qty = strtolower($row['type']) == 'out' ? "(" . $row['qty'] . ")" : $row['qty'];
            $res['data'][] = [
                'no' => "<span>" .$i + 1 . "</span>",
                'product' => $row['product'],
                'location' => $row['location'],
                'type' => $row['type'],
                'qty' => "<span style='color: $color;'>" . $qty . "</span>",
                'createdby' => "
                    <span class='fw-semibold' style='color: #676df0;'>" . $row['created_name'] ."</span><br>
                    <span>" . date('d F Y', strtotime($row['created_at'])) . "</span>
                ",
                'action' => "
                <div class='d-flex flex-nowrap justify-content-center gap-1'>
                    <button class='btn btn-danger btn-action text-white' data-id='". $row['id'] ." 'onclick='deleted(this)'><i class='bx bx-trash'></i></button>
                </div>"
            ];
        }

        return $this->response->setJSON($res);
    }

    public function delete()
    {
        $res = [];
        $id = $this->request->getPost('id');

        try {
            if (empty($id)) throw new Exception('Id Required!');

            $stg = $this->history->getOne(['history.id' => decrypted($id)]);

            $this->history->remove(decrypted($id));

            $res['success'] = 1;
        } catch (Exception $e) {
            $res = [
                'success' => 0,
                'error' => $e->getMessage()
            ];
        }

        return $this->response->setJSON($res);
    }
}
