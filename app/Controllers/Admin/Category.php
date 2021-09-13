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
            // echo "<pre>";
            // print_r($this->data['products']);
            // die();
            $this->data['documents_add'] = $Document_model->asObject()->findAll();

            $this->data['documents_disable'] = array_map(function ($item) {
                return $item->document_id;
            }, (array) $this->data['documents']);
            // echo "<pre>";
            // print_r($this->data['products_disable']);
            // die();


            return view($this->data['content'], $this->data);
        }
    }

    public function deletemenu()
    {
        $id = $this->request->getPost('id');
        $CategoryModel = model("CategoryModel");
        $CategoryModel->delete($id);
    }

    public function saveorder()
    {
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
        $ProductCategoryModel = model("ProductCategoryModel");
        $ProductCategoryModel->delete($id);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }


    public function addproductcategory()
    {
        $ProductCategoryModel = model("ProductCategoryModel");
        $data = json_decode($this->request->getVar('data'), true);
        $category_id = $this->request->getVar('category_id');

        $list = $ProductCategoryModel->where(array("category_id" => $category_id))->asObject()->findAll();
        $max_order = 0;
        foreach ($list as $row) {
            if ($max_order < $row->order) {
                $max_order = $row->order;
            }
        }
        $list_product = array_map(function ($item) {
            return $item->product_id;
        }, (array) $list);
        $data = array_diff($data, $list_product);
        $max_order++;
        foreach ($data as $key => $product_id) {

            $array = array(
                'product_id' => $product_id,
                'category_id' => $category_id,
                'order' => $max_order
            );
            $ProductCategoryModel->insert($array);
        }
        echo json_encode(1);
    }
}
