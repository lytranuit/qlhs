<?php

namespace App\Controllers\Admin;

use App\Models\FileModel;
use App\Libraries\Ciqrcode;

class Loan extends BaseController
{
    private $type_id = 0;
    function __construct()
    {
        $this->type_id = isset($_GET['type_id']) ? $_GET['type_id'] : 0;
    }
    public function index($type_id = 0)
    {
        // echo date("Y-m-d H:i:s");
        // die();

        if (!in_groups(array('admin', 'editor'))) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(lang('Auth.notEnoughPrivilege'));
        }
        $DocumentStatus_model = model("DocumentStatusModel");
        $this->data['status'] = $DocumentStatus_model->asObject()->findAll();
        $this->data['type_id'] =  $type_id;
        return view($this->data['content'], $this->data);
    }

    // public function index($type_id)
    // {
    //     // echo date("Y-m-d H:i:s");
    //     // die();

    //     $DocumentStatus_model = model("DocumentStatusModel");
    //     $this->data['status'] = $DocumentStatus_model->asObject()->findAll();
    //     $this->data['type_id'] =  $this->type_id;
    //     return view($this->data['content'], $this->data);
    // }
    public function edit($id)
    { /////// trang ca nhan

        if (!in_groups(array('admin', 'editor'))) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(lang('Auth.notEnoughPrivilege'));
        }
        $RequestLoanModel = model("RequestLoanModel");
        $tin = $RequestLoanModel->where(array('id' => $id))->asObject()->first();
        if ($tin->status_id == 1) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(lang('Auth.notEnoughPrivilege'));
        }
        if (isset($_POST['dangtin'])) {
            $RequestLoanModel = model("RequestLoanModel");
            $RequestLoanDocumentModel = model("RequestLoanDocumentModel");
            $data = $this->request->getPost();
            /* Update */

            // echo "<pre>";
            // print_r($data);
            // die();
            $obj_old = $RequestLoanModel->where(array('id' => $id))->asArray()->first();
            $obj = $RequestLoanModel->create_object($data);

            ///UPDATE
            $RequestLoanModel->update($id, $obj);



            /*
             * File
             */
            // print_r($data['files']);
            // die();
            $RequestLoanDocumentModel->where(array('request_id' => $id))->delete();
            if (isset($data['documents'])) {
                $array = [];
                foreach ($data['documents'] as $row) {
                    $array[] = array(
                        'document_id' => $row,
                        'request_id' => $id
                    );
                }
                // die();

                $RequestLoanDocumentModel->insertBatch($array);
            }


            $description = "User " . user()->name . " updated a document";
            $RequestLoanModel->trail(1, 'update', $obj, $obj_old, $description);

            return redirect()->to(base_url('admin/' . $this->data['controller']));
        } else {
            $RequestLoanModel = model("RequestLoanModel");
            $tin = $RequestLoanModel->where(array('id' => $id))->asObject()->first();
            $RequestLoanModel->relation($tin, array("documents"));
            // echo "<pre>";
            // print_r($tin);
            // die();

            /*category*/
            // $category = $Document_category_model->where(array('document_id' => $id))->findAll();
            //print_r($category);
            //die();

            if (!empty($tin->documents)) {
                $list = array();
                foreach ($tin->documents as $key => $document) {
                    $list[] = $document->document_id;
                }
                $tin->documents = $list;
            }
            // echo "<pre>";
            // print_r($tin);
            // die();
            $this->data['tin'] = $tin;
            //echo "<pre>";
            //print_r($tin);
            //die();
            //load_editor($this->data);
            //            load_chossen($this->data);

            $DocumentModel = model("DocumentModel");
            $this->data['documents'] = $DocumentModel->asObject()->findAll();

            return view($this->data['content'], $this->data);
        }
    }

    public function add()
    { /////// trang ca nhan
        if (isset($_POST['dangtin'])) {

            $RequestLoanModel = model("RequestLoanModel");
            $RequestLoanDocumentModel = model("RequestLoanDocumentModel");
            $data = $this->request->getPost();

            $data['date'] = date("Y-m-d H:i:s");
            $obj = $RequestLoanModel->create_object($data);
            //INSERT
            $id = $RequestLoanModel->insert($obj);
            /*
             * Document
             */
            // print_r($data['image_other']);
            // die();
            if (isset($data['documents'])) {
                $array = [];
                foreach ($data['documents'] as $row) {
                    $array[] = array(
                        'document_id' => $row,
                        'request_id' => $id
                    );
                }
                // die();

                $RequestLoanDocumentModel->insertBatch($array);
            }
            //QRCODE

            return redirect()->to(base_url('admin'));
        } else {
            //load_editor($this->data);

            $DocumentModel = model("DocumentModel");
            $this->data['documents'] = $DocumentModel->asObject()->findAll();

            return view($this->data['content'], $this->data);
        }
    }



    public function remove($id)
    { /////// trang ca nhan

        if (!in_groups(array('admin', 'editor'))) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(lang('Auth.notEnoughPrivilege'));
        }
        $RequestLoanModel = model("RequestLoanModel");
        $RequestLoanModel->delete($id);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    public function table()
    {
        $RequestLoanModel = model("RequestLoanModel", false);
        $limit = $this->request->getVar('length');
        $start = $this->request->getVar('start');
        $orders = $this->request->getVar('order');
        $search = $this->request->getPost('search')['value'];
        $page = ($start / $limit) + 1;
        $where = $RequestLoanModel;

        // echo "<pre>";
        // print_r($swhere);
        $totalData = $where->countAllResults(false);

        //echo "<pre>";
        //print_r($totalData);
        //die();
        $totalFiltered = $totalData;

        if (isset($orders)) {
            foreach ($orders as $order) {
                $data = $order['data'];
                $dir = $order['dir'];
                switch ($data) {
                    default:
                        $where->orderby($data, $dir);
                        break;
                }
            }
        }
        // $where = $RequestLoanModel;
        $posts = $where->orderby("id", "DESC")->asObject()->paginate($limit, '', $page);


        $RequestLoanModel->relation($posts, array('documents'));
        // echo "<pre>";
        // print_r($posts);
        // die();
        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] =  '<a href="' . base_url("admin/" . $this->data['controller'] . "/edit/" . $post->id) . '"><i class="fas fa-pencil-alt mr-2"></i>' . $post->id . '</a>';

                if ($post->status_id == 1) {
                    $nestedData['id'] =   $post->id;
                }

                $nestedData['name'] =  $post->name;
                $nestedData['documents'] = "";
                foreach ($post->documents as $document) {
                    $nestedData['documents'] .= '<div><a href="' . base_url("admin/document/edit/" . $document->document_id) . '">' . $document->code . "." . $document->version . " - " . $document->name_vi . '</a></div>';
                }
                $nestedData['date'] = date("Y-m-d", strtotime($post->date));
                $nestedData['note'] = $post->note;
                $nestedData['action'] = "";
                if ($post->status_id != 1) {
                    $nestedData['action'] = '<div class="btn-group"><a href="' . base_url("admin/" . $this->data['controller'] . "/success/" . $post->id) . '" class="btn btn-success btn-sm" title="Cho mượn?" data-type="confirm">'
                        . '<i class="fas fa-check"></i>'
                        . '</i>'
                        . '</a><a href="' . base_url("admin/" . $this->data['controller'] . "/remove/" . $post->id) . '" class="btn btn-danger btn-sm" title="Xóa tài liệu?" data-type="confirm">'
                        . '<i class="fas fa-trash-alt">'
                        . '</i>'
                        . '</a></div>';
                }

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($this->request->getVar('draw')),
            "recordsTotal" => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }

    public function success($id)
    { /////// trang ca nhan
        $RequestLoanModel = model("RequestLoanModel");

        $tin = $RequestLoanModel->where(array('id' => $id))->asObject()->first();
        $RequestLoanModel->relation($tin, array("documents"));
        $note = $tin->note;



        $DocumentModel = model("DocumentModel");
        $DocumentLoanModel = model("DocumentLoanModel");

        if (!empty($tin->documents)) {
            foreach ($tin->documents as $document) {
                $document_id = $document->document_id;
                $DocumentModel->update($document_id, array('status_id' => 4));

                $data['status_id_loan'] = $document->status_id;
                $data['user_id'] = user_id();
                $data['document_id'] = $document_id;
                $data['date_loan'] = date("Y-m-d");
                $data['note_loan'] = $tin->note;
                if ($data['status_id_loan'] != 4) {
                    $obj = $DocumentLoanModel->create_object($data);
                    $DocumentLoanModel->insert($obj);
                    $description = "User " . user()->name . " lent a document";
                    $DocumentLoanModel->trail(1, 'loan', $obj, null, $description);
                    continue;
                }
                ///Error
                $note .= "<br>Document ID:$document_id on loan";
            }
        }

        $RequestLoanModel->update($tin->id, array("status_id" => 1, "note" => $note));
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
