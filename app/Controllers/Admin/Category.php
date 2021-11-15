<?php

namespace App\Controllers\Admin;


class Category extends BaseController
{
    function __construct()
    {
        // $this->group = 'CATEGORY';
    }
    public function index()
    {

        // load_datatable($this->data);
        $category_model = model("CategoryModel");
        $categories = $category_model->orderby('sort', "ASC")->asArray()->findAll();
        //echo "<pre>";
        //print_r($category);
        //die();
        if (empty($categories))
            $categories = array();
        $this->data['html_nestable'] = html_nestable($categories, 'parent_id', 0,  $this->data['controller']);
        return view($this->data['content'], $this->data);
    }
    public function add()
    { /////// trang ca nhan
        if (isset($_POST['dangtin'])) {
            helper("auth");
            if (!in_groups(array('admin', 'editor'))) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(lang('Auth.notEnoughPrivilege'));
            }
            $Category_model = model("CategoryModel");
            $data = $this->request->getPost();
            $obj = $Category_model->create_object($data);
            $Category_model->insert($obj);
            return redirect()->to(base_url('admin/' . $this->data['controller']));
        } else {
            //load_editor($this->data);
            return view($this->data['content'], $this->data);
        }
    }

    public function edit($id)
    { /////// trang ca nhan
        if (isset($_POST['dangtin'])) {
            if (!in_groups(array('admin', 'editor'))) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(lang('Auth.notEnoughPrivilege'));
            }
            $Category_model = model("CategoryModel");
            $data = $this->request->getPost();

            $obj_old = $Category_model->where(array('id' => $id))->asArray()->first();
            $obj = $Category_model->create_object($data);
            // print_r($obj);die();
            $Category_model->update($id, $obj);

            $description = "User " . user()->name . " updated a category";
            $Category_model->trail(1, 'update', $obj, $obj_old, $description);
            return redirect()->to(base_url('admin/' . $this->data['controller']));
        } else {
            $Category_model = model("CategoryModel");
            $Document_model = model("DocumentModel");
            $Document_category_model = model("DocumentCategoryModel");
            $tin = $Category_model->where(array('id' => $id))->asObject()->first();
            $this->data['tin'] = $tin;
            //echo "<pre>";
            //print_r($tin);
            //die();   
            $this->data['documents'] = $Document_category_model->document_by_category($id);
            // $Document_model->relation($this->data['documents'], array('files', "status"));
            // echo "<pre>";
            // print_r($this->data['products']);
            // die();
            // $this->data['documents_add'] = $Document_model->asObject()->findAll();

            $this->data['documents_disable'] = array_map(function ($item) {
                return $item->id;
            }, (array) $this->data['documents']);
            // echo "<pre>";
            // print_r($this->data['products_disable']);
            // die();


            return view($this->data['content'], $this->data);
        }
    }
    public function tabledocument()
    {
        $Document_model = model("DocumentModel", false);
        $limit = $this->request->getVar('length');
        $start = $this->request->getVar('start');
        $orders = $this->request->getVar('order');
        $category_id = $this->request->getVar('category_id');
        $search = $this->request->getPost('search')['value'];
        $page = ($start / $limit) + 1;

        $Document_category_model = model("DocumentCategoryModel");
        $docs = $Document_category_model->document_by_category($category_id);

        $ids = array_map(function ($item) {
            return $item->id;
        }, (array)$docs);
        $where = $Document_model->whereIn("id", $ids);
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
                    case 'status':
                        $where->orderby('status_id', $dir);
                        break;
                    case 'type':
                        $where->orderby('type_id', $dir);
                        break;
                }
            }
        }
        // $where = $Document_model;
        $posts = $where->orderby("id", "DESC")->asObject()->paginate($limit, '', $page);


        $Document_model->relation($posts, array('files', "status", "type"));
        // echo "<pre>";
        // print_r($posts);
        // die();
        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] =  '<a href="' . base_url("admin/document/edit/" . $post->id) . '"><i class="fas fa-pencil-alt mr-2"></i>' . $post->id . '</a>';
                $nestedData['code'] = '<a href="' . base_url("admin/document/edit/" . $post->id) . '">' . $post->code . "." . $post->version . '</a>';
                $nestedData['name_vi'] = '<a href="' . base_url("admin/document/edit/" . $post->id) . '">' . $post->name_vi . '</a>';
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
                $nestedData['status'] = isset($post->status) ? $post->status->name : $post->status_id;
                $nestedData['type'] = isset($post->type) ? $post->type->name : $post->type_id;
                $nestedData['action'] = "";
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
    public function deletemenu()
    {
        if (!in_groups(array('admin', 'editor'))) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(lang('Auth.notEnoughPrivilege'));
        }

        $id = $this->request->getPost('id');
        $CategoryModel = model("CategoryModel");
        $CategoryModel->delete($id);
    }

    public function saveorder()
    {

        if (!in_groups(array('admin', 'editor'))) {
            return redirect('login');
        }
        $CategoryModel = model("CategoryModel");
        $data = json_decode($this->request->getPost('data'), true);
        foreach ($data as $key => $row) {
            if (isset($row['id'])) {
                $id = $row['id'];
                $parent_id = isset($row['parent_id']) && $row['parent_id'] != "" ? $row['parent_id'] : 0;
                $array = array(
                    'parent_id' => $parent_id,
                    'sort' => $key
                );
                $CategoryModel->update($id, $array);
            }
        }

        $description = "User " . user()->name . " reorder categories";
        $CategoryModel->trail(1, 'reorder', null, null, $description);
    }

    public function remove_product($id)
    { /////// trang ca nhan

        if (!in_groups(array('admin', 'editor'))) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(lang('Auth.notEnoughPrivilege'));
        }
        $ProductCategoryModel = model("ProductCategoryModel");
        $ProductCategoryModel->delete($id);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }


    public function adddocumentcategory()
    {

        if (!in_groups(array('admin', 'editor'))) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(lang('Auth.notEnoughPrivilege'));
        }
        $DocumentCategoryModel = model("DocumentCategoryModel");
        $CategoryModel = model("CategoryModel");
        $data = json_decode($this->request->getVar('data'), true);
        $category_id = $this->request->getVar('category_id');

        $list = $DocumentCategoryModel->where(array("category_id" => $category_id))->asObject()->findAll();
        $list_document = array_map(function ($item) {
            return $item->document_id;
        }, (array) $list);
        $data = array_diff($data, $list_document);
        $list_parents = $CategoryModel->get_category_parents($category_id);
        $array = [];
        foreach ($data as $key => $document_id) {
            $array[] =  array(
                'document_id' => $document_id,
                'category_id' => $category_id,
            );
            foreach ($list_parents as $parent_id) {
                $array[] =  array(
                    'document_id' => $document_id,
                    'category_id' => $parent_id,
                );
            }
        }

        // print_r($array);
        // die();
        $DocumentCategoryModel->insertBatch($array);
        echo json_encode(1);
    }

    public function adddocument($code)
    {

        if (!in_groups(array('admin', 'editor'))) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(lang('Auth.notEnoughPrivilege'));
        }
        $DocumentCategoryModel = model("DocumentCategoryModel");
        $CategoryModel = model("CategoryModel");
        $DocumentModel = model("DocumentModel");
        $category_id = $this->request->getPost('category_id');

        $document = $DocumentModel->where('uuid', $code)->first();
        if (empty($document)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        $list_parents = $CategoryModel->get_category_parents($category_id);
        $array = [];
        $document_id = $document->id;
        $array[] =  array(
            'document_id' => $document_id,
            'category_id' => $category_id,
        );
        foreach ($list_parents as $parent_id) {
            $array[] =  array(
                'document_id' => $document_id,
                'category_id' => $parent_id,
            );
        }


        // print_r($array);
        // die();
        $DocumentCategoryModel->insertBatch($array);
        echo json_encode(1);
    }

    public function documentlist()
    {
        $Document_model = model("DocumentModel");
        $data = $this->request->getPost('data');
        $documents_disable = $this->request->getPost('documents_disable');
        $documents_disable = $documents_disable ? $documents_disable : [];
        $search = $data['q'];
        $data = $Document_model->where("(code like '%$search%')")->asArray()->paginate(100, '', 0);
        $results = array();
        foreach ($data as $row) {
            $results[] = array("id" => $row['id'], 'disabled' => in_array($row['id'], $documents_disable) ? true : false, 'text' => $row['code'] . "." . $row['version'] . ' - ' . $row['name_vi']);
        }
        echo json_encode(array('q' => $search, 'results' => $results));
        die();
    }
}
