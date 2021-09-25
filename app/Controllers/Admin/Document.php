<?php

namespace App\Controllers\Admin;

use App\Models\FileModel;
use App\Libraries\Ciqrcode;

class Document extends BaseController
{
    public function index()
    {
        // echo date("Y-m-d H:i:s");
        // die();

        $DocumentStatus_model = model("DocumentStatusModel");
        $this->data['status'] = $DocumentStatus_model->asObject()->findAll();
        return view($this->data['content'], $this->data);
    }

    public function edit($id)
    { /////// trang ca nhan
        if (isset($_POST['dangtin'])) {

            $Document_model = model("DocumentModel");
            $Document_category_model = model("DocumentCategoryModel");
            $DocumentFile_model = model("DocumentFileModel");
            $data = $this->request->getPost();
            /* Update */

            // echo "<pre>";
            // print_r($data);
            // die();
            $obj_old = $Document_model->where(array('id' => $id))->asArray()->first();
            $obj = $Document_model->create_object($data);
            ///Check hiện hành
            if (isset($obj['is_active']) && $obj['is_active']) {
                $Document_model->where("code", $obj['code'])->set("is_active", 0)->update();
            }
            ///UPDATE SEND REVIEW & EXPIRE
            if (isset($obj['date_review']) && $obj_old['date_review'] != $obj['date_review']) {
                $obj['time_send_review'] = NULL;
            }
            ///UPDATE SEND REVIEW & EXPIRE
            if (isset($obj['date_expire']) && $obj_old['date_expire'] != $obj['date_expire']) {
                $obj['time_send_expire'] = NULL;
            }
            ///UPDATE
            $Document_model->update($id, $obj);

            /* CATEGORY */
            $related_new = array();
            if (isset($data['category_list'])) {
                $related_new = array_merge($related_new, $data['category_list']);
                unset($data['category_list']);
            }
            //print_r($data);
            //die();

            $array = $Document_category_model->where('document_id', $id)->findAll();
            $related_old = array_map(function ($item) {
                return $item['category_id'];
            }, (array) $array);
            $array_delete = array_diff($related_old, $related_new);
            $array_add = array_diff($related_new, $related_old);
            foreach ($array_add as $row) {
                $array = array(
                    'category_id' => $row,
                    'document_id' => $id
                );
                $Document_category_model->insert($array);
            }
            foreach ($array_delete as $row) {
                $array = array(
                    'category_id' => $row,
                    'document_id' => $id
                );
                $Document_category_model->where($array)->delete();
            }

            /*
             * File
             */
            // print_r($data['files']);
            // die();
            $DocumentFile_model->where(array('document_id' => $id))->set(['document_id' => null])->update();
            if (isset($data['files'])) {
                foreach ($data['files'] as $row) {
                    $array = array(
                        'document_id' => $id,
                    );
                    $DocumentFile_model->update($row, $array);
                }
                // die();
            }


            $description = "User " . user()->name . " updated a document";
            $Document_model->trail(1, 'update', $obj, $obj_old, $description);

            return redirect()->to(base_url('admin/' . $this->data['controller']));
        } else {
            $Document_model = model("DocumentModel");
            $category_model = model("CategoryModel");
            $DocumentStatus_model = model("DocumentStatusModel");
            $Document_category_model = model("DocumentCategoryModel");
            $tin = $Document_model->where(array('id' => $id))->asObject()->first();
            $Document_model->relation($tin, array('files', "categories"));
            // echo "<pre>";
            // print_r($tin);
            // die();

            /*category*/
            // $category = $Document_category_model->where(array('document_id' => $id))->findAll();
            //print_r($category);
            //die();

            if (!empty($tin->categories)) {
                $cate_id = array();
                foreach ($tin->categories as $key => $cate) {
                    $cate_id[] = $cate->category_id;
                }
                $tin->category_list = $cate_id;
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
            $this->data['status'] = $DocumentStatus_model->asObject()->findAll();
            // print_r($this->data['status']);die();
            $this->data['category'] = $category_model
                ->orderBy('parent_id', 'ASC')->orderBy('sort', 'ASC')->asArray()->findAll();
            $this->data['category'] = html_product_category_nestable($this->data['category'], 'parent_id', 0);

            return view($this->data['content'], $this->data);
        }
    }

    public function add()
    { /////// trang ca nhan
        if (isset($_POST['dangtin'])) {
            helper("auth");
            $Document_model = model("DocumentModel");
            $Document_category_model = model("DocumentCategoryModel");
            $DocumentFile_model = model("DocumentFileModel");
            $data = $this->request->getPost();

            $obj = $Document_model->create_object($data);

            ///Check hiện hành
            if (isset($obj['is_active']) && $obj['is_active']) {
                $Document_model->where("code", $obj['code'])->set("is_active", 0)->update();
            }
            //INSERT
            $id = $Document_model->insert($obj);
            /* CATEGORY */
            $related_new = array();
            if (isset($data['category_list'])) {
                $related_new = array_merge($related_new, $data['category_list']);
                unset($data['category_list']);
            }
            foreach ($related_new as $row) {
                $array = array(
                    'category_id' => $row,
                    'document_id' => $id
                );
                $Document_category_model->insert($array);
            }

            /*
             * File
             */
            // print_r($data['image_other']);
            // die();
            if (isset($data['files'])) {
                foreach ($data['files'] as $row) {
                    $array = array(
                        'document_id' => $id,
                    );
                    $DocumentFile_model->update($row, $array);
                }
                // die();
            }
            //QRCODE

            /* Load QR Code Library */
            // $this->load->library('ciqrcode');
            /* Data */
            $data_qr = base_url("admin/" . $this->data['controller'] . "/edit/$id");
            $dir = FCPATH . "assets/qrcode/";
            $code1 =  $obj['code'] . "." . ($obj['version'] < 10 ? "0" . $obj['version'] : $obj['version']);
            $save_name  = $id . "_" . $code1  . '.png';

            /* QR Code File Directory Initialize */
            if (!file_exists($dir)) {
                mkdir($dir, 0775, true);
            }

            /* QR Configuration  */
            $ciqrcode = new Ciqrcode();

            /* QR Data  */
            $params['data']     = $data_qr;
            $params['level']    = 'L';
            $params['size']     = 10;
            $params['savename'] = $dir . $save_name;

            $ciqrcode->generate($params);
            $Document_model->update($id, array("image_url" => "/assets/qrcode/$save_name"));
            return redirect()->to(base_url('admin/' . $this->data['controller']));
        } else {
            //load_editor($this->data);

            $category_model = model("CategoryModel");
            $DocumentStatus_model = model("DocumentStatusModel");
            $this->data['status'] = $DocumentStatus_model->asObject()->findAll();
            $this->data['category'] = $category_model
                ->orderBy('parent_id', 'ASC')->orderBy('sort', 'ASC')->asArray()->findAll();
            $this->data['category'] = html_product_category_nestable($this->data['category'], 'parent_id', 0);

            return view($this->data['content'], $this->data);
        }
    }

    public function upversion($id)
    { /////// trang ca nhan
        if (isset($_POST['dangtin'])) {
            helper("auth");
            $Document_model = model("DocumentModel");
            $Document_category_model = model("DocumentCategoryModel");
            $DocumentFile_model = model("DocumentFileModel");
            $data = $this->request->getPost();

            $obj = $Document_model->create_object($data);

            ///Check hiện hành
            if (isset($obj['is_active']) && $obj['is_active']) {
                $Document_model->where("code", $obj['code'])->set("is_active", 0)->update();
            }
            //INSERT
            $id = $Document_model->insert($obj);
            /* CATEGORY */
            $related_new = array();
            if (isset($data['category_list'])) {
                $related_new = array_merge($related_new, $data['category_list']);
                unset($data['category_list']);
            }
            foreach ($related_new as $row) {
                $array = array(
                    'category_id' => $row,
                    'document_id' => $id
                );
                $Document_category_model->insert($array);
            }

            /*
             * File
             */
            // print_r($data['image_other']);
            // die();
            if (isset($data['files'])) {
                foreach ($data['files'] as $row) {
                    $array = array(
                        'document_id' => $id,
                    );
                    $DocumentFile_model->update($row, $array);
                }
                // die();
            }
            //QRCODE

            /* Load QR Code Library */
            // $this->load->library('ciqrcode');
            /* Data */
            $data_qr = base_url("admin/" . $this->data['controller'] . "/edit/$id");
            $hexocde = bin2hex($data_qr);
            $dir = FCPATH . "assets/qrcode/";
            $save_name  = $hexocde . '.png';

            /* QR Code File Directory Initialize */
            if (!file_exists($dir)) {
                mkdir($dir, 0775, true);
            }

            /* QR Configuration  */
            $ciqrcode = new Ciqrcode();

            /* QR Data  */
            $params['data']     = $data_qr;
            $params['level']    = 'L';
            $params['size']     = 10;
            $params['savename'] = $dir . $save_name;

            $ciqrcode->generate($params);
            $Document_model->update($id, array("image_url" => "/assets/qrcode/$save_name"));
            return redirect()->to(base_url('admin/' . $this->data['controller']));
        } else {
            //load_editor($this->data);
            $Document_model = model("DocumentModel");
            $tin = $Document_model->where(array('id' => $id))->asObject()->first();
            $tin->version = $tin->version + 1;
            $this->data['tin'] = $tin;
            $category_model = model("CategoryModel");
            $DocumentStatus_model = model("DocumentStatusModel");
            $this->data['status'] = $DocumentStatus_model->asObject()->findAll();
            $this->data['category'] = $category_model
                ->orderBy('parent_id', 'ASC')->orderBy('sort', 'ASC')->asArray()->findAll();
            $this->data['category'] = html_product_category_nestable($this->data['category'], 'parent_id', 0);
            return view($this->data['content'], $this->data);
        }
    }

    public function fileupload()
    {

        $DocumentFileModel = model("DocumentFileModel");
        $data = array();

        // Read new token and assign to $data['token']
        $data['token'] = csrf_hash();
        if ($files = $this->request->getFiles()) {

            $data['success'] = 1;
            $data['items'] = array();
            foreach ($files['files'] as $file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    // Get file name and extension
                    $name = $file->getName();
                    $ext = $file->getClientExtension();
                    // $size = $file->get

                    // Get random file name
                    $newName = $file->getRandomName();
                    $mimeType = $file->getClientMimeType();

                    // Store file in public/uploads/ folder
                    $dir = FCPATH . '/assets/upload/document/';
                    $file->move($dir, $newName);

                    $array = array(
                        'name' => $name,
                        'url' => '/assets/upload/document/' . $newName,
                        'ext' => $ext,
                        'mimeType' => $mimeType
                    );
                    $obj = $DocumentFileModel->create_object($array);
                    $id = $DocumentFileModel->insert($obj);

                    $item = $DocumentFileModel->where(array('id' => $id))->asArray()->first();
                    // Response
                    $data['items'][] = $item;
                }
            }
        } else {
            // Response
            $data['success'] = 0;
            $data['message'] = 'File not uploaded.';
        }

        return $this->response->setJSON($data);
    }
    public function up($id)
    { /////// trang ca nhan
        $Document_model = model("DocumentModel");
        $data['date'] = date("Y-m-d H:i:s");
        $obj = $Document_model->create_object($data);
        $Document_model->update($id, $obj);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function remove($id)
    { /////// trang ca nhan
        $DocumentModel = model("DocumentModel");
        $DocumentModel->delete($id);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    public function loan()
    { /////// trang ca nhan
        if (isset($_POST)) {
            helper("auth");
            $Document_model = model("DocumentModel");
            $DocumentLoanModel = model("DocumentLoanModel");
            $data = $this->request->getPost();
            $document_id = $data['document_id'];
            $tin = $Document_model->where(array('id' => $document_id))->asObject()->first();
            $data['status_id_loan'] = $tin->status_id;
            if ($data['status_id_loan'] != 4) {
                $obj = $DocumentLoanModel->create_object($data);
                $DocumentLoanModel->insert($obj);

                $Document_model->update($tin->id, array('status_id' => 4));

                $description = "User " . user()->name . " lent a document";
                $DocumentLoanModel->trail(1, 'loan', $obj, null, $description);
            }

            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    public function receive()
    { /////// trang ca nhan
        if (isset($_POST)) {
            helper("auth");
            $Document_model = model("DocumentModel");
            $DocumentLoanModel = model("DocumentLoanModel");
            $data = $this->request->getPost();
            $id = $data['id'];
            $document_id = $data['document_id'];
            $document_status = $data['status_id_return'];

            $obj = $DocumentLoanModel->create_object($data);
            $DocumentLoanModel->update($id, $obj);

            $Document_model->update($document_id, array('status_id' => $document_status));

            $description = "User " . user()->name . " received a document";
            $DocumentLoanModel->trail(1, 'receive', $obj, null, $description);


            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }
    public function table()
    {
        $Document_model = model("DocumentModel", false);
        $limit = $this->request->getVar('length');
        $start = $this->request->getVar('start');
        $search = $this->request->getPost('search')['value'];
        $search_type = $this->request->getPost('search_type');
        $search_status = $this->request->getPost('search_status');
        $filter = $this->request->getPost('filter');
        $page = ($start / $limit) + 1;
        $where = $Document_model;
        if ($filter == "1")
            $where->where("is_active", 1);
        // echo "<pre>";
        // print_r($swhere);
        $totalData = $where->countAllResults(false);

        //echo "<pre>";
        //print_r($totalData);
        //die();
        $totalFiltered = $totalData;


        if ($search_type == "status" && $search_status != "") {
            $where->where("status_id", $search_status);
            $totalFiltered = $where->countAllResults(false);
        } elseif (empty($search)) {
            // $where = $Document_model;
            // echo "1";die();
        } elseif ($search_type == "code") {
            $where->like("code", $search, "after");
            $totalFiltered = $where->countAllResults(false);
        } else {
            $where->like($search_type, $search);
            $totalFiltered = $where->countAllResults(false);
        }

        // $where = $Document_model;
        $posts = $where->asObject()->orderby("id", "DESC")->paginate($limit, '', $page);

        $Document_model->relation($posts, array('files', "status"));
        // echo "<pre>";
        // print_r($posts);
        // die();
        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] =  $post->id;
                $nestedData['code'] = '<a href="' . base_url("admin/" . $this->data['controller'] . "/edit/" . $post->id) . '"><i class="fas fa-pencil-alt mr-2"></i>' . $post->code . "." . ($post->version < 10 ? "0" . $post->version : $post->version) . '</a>';
                $nestedData['name_vi'] = '<a href="' . base_url("admin/" . $this->data['controller'] . "/edit/" . $post->id) . '">' . $post->name_vi . '</a>';
                $nestedData['version'] = $post->version;
                $nestedData['file'] = "";
                if (isset($post->files)) {
                    foreach ($post->files as $row) {
                        $nestedData['file'] .= '<div class="">
                        <div class="file-icon" data-type="' . $row->ext . '"></div>
                        <a href="' . $row->url . '" download="' . $row->name . '">' . $row->name . '</a>
                    </div>';
                    }
                }
                // if (isset($post->samecode)) {
                //     $nestedData['version'] = "";
                //     foreach ($post->samecode as $row) {
                //         $nestedData['version'] .= '<div class="">'
                //             . '<a href="' . base_url("admin/" . $this->data['controller'] . "/edit/" . $row->id) . '">' . $row->code . "." . $row->version . '</a>'
                //             . '</div>';
                //     }
                // }
                $nestedData['status'] = isset($post->status) ? $post->status->name : $post->status_id;

                $nestedData['action'] = '<div class="btn-group"><a href="' . base_url("admin/" . $this->data['controller'] . "/upversion/" . $post->id) . '" class="btn btn-warning btn-sm" title="Lên ấn bản?" data-type="confirm">'
                    . '<i class="fas fa-arrow-alt-circle-up">'
                    . '</i>'
                    . '</a><a href="' . base_url("admin/" . $this->data['controller'] . "/remove/" . $post->id) . '" class="btn btn-danger btn-sm" title="Xóa tài liệu?" data-type="confirm">'
                    . '<i class="fas fa-trash-alt">'
                    . '</i>'
                    . '</a></div>';

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
    public function tableloan($id)
    {
        $DocumentLoanModel = model("DocumentLoanModel");
        $limit = $this->request->getVar('length');
        $start = $this->request->getVar('start');
        $page = ($start / $limit) + 1;
        $where = $DocumentLoanModel->where("document_id", $id);

        $totalData = $where->countAllResults();
        //echo "<pre>";
        //print_r($totalData);
        //die();
        $totalFiltered = $totalData;
        if (empty($this->request->getPost('search')['value'])) {

            $where = $DocumentLoanModel->where("document_id", $id);
        } else {
            // $search = $this->request->getPost('search')['value'];
            // $sWhere = "(LOWER(code) LIKE LOWER('%$search%') OR name_vi like '%" . $search . "%')";
            // $where =  $Document_model->where($sWhere);
            // $totalFiltered = $where->countAllResults();
            // $where = $Document_model->where($sWhere);
            $where = $DocumentLoanModel->where("document_id", $id);
        }

        $where = $DocumentLoanModel->where("document_id", $id);
        $posts = $where->asObject()->orderby("id", "DESC")->paginate($limit, '', $page);

        $DocumentLoanModel->relation($posts, array('user', 'status_loan', 'status_return', 'user_receive'));
        // echo "<pre>";
        // print_r($posts);
        // die();
        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['user'] = isset($post->user) ? $post->user->name : $post->user_id;
                $nestedData['user_receive'] = isset($post->user_receive) ? $post->user_receive->name : $post->user_id_receive;
                $nestedData['user_loan'] = $post->user_loan;
                $nestedData['date_loan'] = $post->date_loan;
                $nestedData['note_loan'] = $post->note_loan;
                $nestedData['date_return'] = $post->date_return;
                $nestedData['note_return'] = $post->note_return;

                $nestedData['status_loan'] = isset($post->status_loan) ? $post->status_loan->name : $post->status_id_loan;
                $nestedData['status_return'] = isset($post->status_return) ? $post->status_return->name : $post->status_id_return;

                if ($post->user_id_receive < 1) {
                    $nestedData['user_receive'] = '<a href="" class="btn btn-primary btn-sm mr-2 button_receive" data-target="#receive-modal" data-toggle="modal" data-id="' . $post->id . '">'
                        . '<i class="fas fa-undo-alt">'
                        . '</i> Nhận lại tài liệu'
                        . '</a>';
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
}
