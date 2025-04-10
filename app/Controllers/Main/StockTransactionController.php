<?php

namespace App\Controllers\Main;

use App\Controllers\BaseController;
use App\Models\StockTransaction;
use App\Models\StockTransactionDetail;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;

/**
 * @property StockTransaction stockTransaction
 * @property StockTransactionDetail stockTransactionDetail
 * @property session session
 */

class StockTransactionController extends BaseController
{
    public function __construct()
    {
        $this->session = session();
        $this->stockTransaction = new StockTransaction();
        $this->stockTransactionDetail = new StockTransactionDetail();

        $this->session->set('menu', 'transaction');
    }

    public function index()
    {
        if (!isset(session()->user))
	    {
	        return view('login/v_login');
	    }
        return view('main/transaction/v_transaction', [
            'menu_title' => 'Transaction'
        ]);
    }

    public function form()
    {
        $id = $this->request->getGet('id') ?? '';

        // dd(decrypted((string)$id));
        $data = [];
        if (!empty($id)) {
            try {
                $decrypted = decrypted($id);
                $data = $this->stockTransaction->getOne(['id' => $decrypted]);
                if (empty($data)) {
                    return redirect()->to('/transaction');
                }
            } catch (\Exception $e) {
                return redirect()->to('/transaction');
            }
        }

        return view('main/transaction/v_form', [
            'menu_title' => 'Transaction',
            'data' => $data
        ]);
    }

    public function datatable()
    {
        $data = $this->stockTransaction->get();
        $res = [];

        for ($i=0; $i < count($data); $i++) {
            $row = $data[$i];
            $detail = $this->stockTransactionDetail->getOne(['id_transaction' => $row['id']])->getResultArray();
            $items_count = is_array($detail) ? count($detail) : 0;
            $row['id'] = encrypted($row['id']);
            $res['data'][] = [
                'no' => "<span>" . ($i + 1) . "</span>",
                'transcode' => $row['transcode'],
                'location' => $row['location'],
                'category' => $row['category'],
                'date' => date('d F Y', strtotime($row['date'])),
                'items' => $items_count,
                'status' => $row['isrelease'] ? '<span class="badge bg-success">Released</span>' : '<span class="badge bg-warning">Draft</span>',
                'createdby' => "
                    <span class='fw-semibold' style='color: #676df0;'>" . $row['created_name'] . "</span><br>
                    <span>" . date('d F Y', strtotime($row['created_at'])) . "</span>
                ",
                'action' => "
                <div class='d-flex flex-nowrap justify-content-center gap-1'>" 
                    . (!$row['isrelease'] ? "
                    <button class='btn btn-warning btn-action text-white'><i class='bx bx-edit'></i></button>
                    <button class='btn btn-danger btn-action text-white' data-id='" . $row['id'] . "' onclick='deleted(this)'><i class='bx bx-trash'></i></button>
                    " : "
                    <button class='btn btn-primary btn-action text-white'><i class='bx bx-menu'></i></button>
                    ") . "
                </div>"
            ];
        }

        return $this->response->setJSON($res);
    }

    public function detailtable()
    {
        $transaction = $this->request->getPost('transaction') ?? 0;
        $data = $this->stockTransactionDetail->getOne(['id_transaction' => decrypted($transaction)])->getResultArray() ?? [];
        $res = [];

        for ($i=0; $i < count($data); $i++) {
            $row = $data[$i];
            $row['id'] = encrypted($row['id']);
            $res['data'][] = [
                'no' => "<span>" . ($i + 1) . "</span>",
                'product' => $row['product'],
                'qty' => $row['qty'],
                'note' => $row['note'],
                'action' => "
                <div class='d-flex flex-nowrap justify-content-center gap-1'>
                    <button class='btn btn-warning btn-action text-white' data-id='" . $row['id'] . "' onclick='updated(this)'><i class='bx bx-edit'></i></button>
                    <button class='btn btn-danger btn-action text-white' data-id='" . $row['id'] . "' onclick='deleted(this)'><i class='bx bx-trash'></i></button>
                </div>"
            ];
        }

        return $this->response->setJSON($res);
    }

    public function save()
    {
        $res = [];
        $id = $this->request->getPost('id');
        $location = $this->request->getPost('location');
        $category = $this->request->getPost('category');
        $date = $this->request->getPost('date');
        $note = $this->request->getPost('note');
        $products = $this->request->getPost('products');

        try {
            if (empty($location)) throw new Exception('location required');
            if (empty($category)) throw new Exception('category required');
            if (empty($date)) throw new Exception('date required');
            // if (empty($products)) throw new Exception('products required');

            $data = [
                'id_location' => decrypted($location),
                'id_category' => decrypted($category),
                'date' => $date,
                'note' => $note,
                'isrelease' => false
            ];

            if (empty($id)) {
                $data['transcode'] = 'TRC' . date('Ymd');
                $data['created_by'] = $this->session->user;
                $data['created_at'] = date('Y-m-d H:i:s');
                $transId = $this->stockTransaction->store($data);

                $finalTranscode = 'TRC' . date('dmy') . $transId;
                $this->stockTransaction->edit($transId, ['transcode' => $finalTranscode]);
            } else {
                $transaction = $this->stockTransaction->getOne(['id' => decrypted($id)]);
                if ($transaction['isrelease']) {
                    throw new Exception('Cannot edit released transaction');
                }
                $data['updated_by'] = $this->session->user;
                $data['updated_at'] = date('Y-m-d H:i:s');
                $this->stockTransaction->edit(decrypted($id), $data);
                $transId = decrypted($id);
                $this->stockTransactionDetail->remove(['id_transaction' => $transId]);
            }

            // foreach ($products as $product) {
            //     $this->stockTransactionDetail->store([
            //         'id_transaction' => $transId,
            //         'id_product' => decrypted($product['id']),
            //         'qty' => $product['qty'],
            //         'note' => $product['note'] ?? ''
            //     ]);
            // }

            $res['success'] = 1;
            $res['link'] = base_url('transaction/form?id=' . urlencode(encrypted((string)$transId)));
        } catch (Exception $e) {
            $res = [
                'success' => 0,
                'error' => $e->getMessage()
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

            $transaction = $this->stockTransaction->getOne(['id' => decrypted($id)]);
            if ($transaction['isrelease']) {
                throw new Exception('Cannot delete released transaction');
            }

            $this->stockTransactionDetail->remove(['id_transaction' => decrypted($id)]);
            $this->stockTransaction->remove(decrypted($id));

            $res['success'] = 1;
        } catch (Exception $e) {
            $res = [
                'success' => 0,
                'error' => $e->getMessage()
            ];
        }

        return $this->response->setJSON($res);
    }

    public function release()
    {
        $res = [];
        $id = $this->request->getPost('id');

        try {
            if (empty($id)) throw new Exception('Id Required!');

            $this->stockTransaction->edit(decrypted($id), [
                'isrelease' => true,
                'updated_by' => $this->session->user,
                'updated_at' => date('Y-m-d H:i:s')
            ]);

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