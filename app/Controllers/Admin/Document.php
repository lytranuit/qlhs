<?php

namespace App\Controllers\Admin;

use App\Models\FileModel;

class Document extends BaseController
{

    public function index()
    {
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
            $obj = $Document_model->create_object($data);
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
            return redirect()->to(base_url('admin/' . $this->data['controller']));
        } else {
            $Document_model = model("DocumentModel");
            $category_model = model("CategoryModel");
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
            $id = $Document_model->save($obj);
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
            return redirect()->to(base_url('admin/' . $this->data['controller']));
        } else {
            //load_editor($this->data);

            $category_model = model("CategoryModel");
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
                    $id = $DocumentFileModel->insert($array);

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
        $DocumentModel->where(array("id" => $id))->delete();
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function table()
    {
        $Document_model = model("DocumentModel");
        $limit = $this->request->getVar('length');
        $start = $this->request->getVar('start');
        $page = ($start / $limit) + 1;
        $where = $Document_model;

        $totalData = $where->countAllResults();
        //echo "<pre>";
        //print_r($totalData);
        //die();
        $totalFiltered = $totalData;
        if (empty($this->request->getPost('search')['value'])) {
            //            $max_page = ceil($totalFiltered / $limit);

            $where = $Document_model;
        } else {
            $search = $this->request->getPost('search')['value'];
            $sWhere = "(LOWER(code) LIKE LOWER('%$search%') OR name_vi like '%" . $search . "%')";
            $where =  $Document_model->where($sWhere);
            $totalFiltered = $where->countAllResults();
            $where = $Document_model->where($sWhere);
        }

        $where = $Document_model;
        $posts = $where->asObject()->orderby("id", "DESC")->paginate($limit, '', $page);
        // echo "<pre>";
        // print_r($posts);
        // die();
        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['code'] = $post->code;
                $nestedData['name_vi'] = $post->name_vi;
                $nestedData['file'] = "";
                $nestedData['status'] = $post->status;

                $nestedData['action'] = '<a href="' . base_url("admin/" . $this->data['controller'] . "/edit/" . $post->id) . '" class="btn btn-warning btn-sm mr-2" title="edit">'
                    . '<i class="fas fa-pencil-alt">'
                    . '</i>'
                    . '</a><a href="' . base_url("admin/" . $this->data['controller'] . "/remove/" . $post->id) . '" class="btn btn-danger btn-sm mr-2" title="remove" data-type="confirm">'
                    . '<i class="fas fa-trash-alt">'
                    . '</i>'
                    . '</a>';

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
