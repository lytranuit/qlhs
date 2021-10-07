<?php

namespace App\Controllers\Admin;


class Type extends BaseController
{
    function __construct()
    {
        // $this->group = 'Status';
    }
    public function index()
    {
        return view($this->data['content'], $this->data);
    }
    public function add()
    { /////// trang ca nhan
        if (isset($_POST['dangtin'])) {
            if (!in_groups(array('admin', 'editor'))) {
                throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(lang('Auth.notEnoughPrivilege'));
            }
            $DocumentTypeModel = model("DocumentTypeModel");
            $data = $this->request->getPost();
            $obj = $DocumentTypeModel->create_object($data);
            $DocumentTypeModel->insert($obj);
            return redirect()->to(base_url('admin/' . $this->data['controller']));
        } else {
            //load_editor($this->data);
            return view($this->data['content'], $this->data);
        }
    }

    public function edit($id)
    { /////// trang ca nhan
        if (isset($_POST['dangtin'])) {

            $DocumentTypeModel = model("DocumentTypeModel");
            $data = $this->request->getPost();

            $obj_old = $DocumentTypeModel->where(array('id' => $id))->asArray()->first();
            $obj = $DocumentTypeModel->create_object($data);
            // print_r($obj);die();
            $DocumentTypeModel->update($id, $obj);

            $description = "User " . user()->name . " updated a Type";
            $DocumentTypeModel->trail(1, 'update', $obj, $obj_old, $description);
            return redirect()->to(base_url('admin/' . $this->data['controller']));
        } else {
            $DocumentTypeModel = model("DocumentTypeModel");
            $tin = $DocumentTypeModel->where(array('id' => $id))->asObject()->first();
            $this->data['tin'] = $tin;
            return view($this->data['content'], $this->data);
        }
    }

    public function remove($id)
    { /////// trang ca nhan
        $DocumentTypeModel = model("DocumentTypeModel");
        $DocumentTypeModel->delete($id);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
    public function table()
    {
        $DocumentTypeModel = model("DocumentTypeModel", false);
        $limit = $this->request->getVar('length');
        $start = $this->request->getVar('start');
        $search = $this->request->getPost('search')['value'];
        $page = ($start / $limit) + 1;
        $where = $DocumentTypeModel;
        // echo "<pre>";
        // print_r($where);
        $totalData = $where->countAllResults(false);

        //echo "<pre>";
        //print_r($totalData);
        //die();
        $totalFiltered = $totalData;

        if (empty($search)) {
            // $where = $Document_model;
            // echo "1";die();
        } else {
            $where->like("name", $search);
            $totalFiltered = $where->countAllResults(false);
        }

        // $where = $Document_model;
        $posts = $where->asObject()->orderby("id", "DESC")->paginate($limit, '', $page);

        // echo "<pre>";
        // print_r($posts);
        // die();
        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] =  '<a href="' . base_url("admin/" . $this->data['controller'] . "/edit/" . $post->id) . '"><i class="fas fa-pencil-alt mr-2"></i>' . $post->id . '</a>';
                $nestedData['name'] = '<a href="' . base_url("admin/" . $this->data['controller'] . "/edit/" . $post->id) . '">' . $post->name . '</a>';
                $nestedData['action'] = "";
                if (in_groups(array('admin', 'editor')))
                    $nestedData['action'] = '<div class="btn-group"><a href="' . base_url("admin/" . $this->data['controller'] . "/remove/" . $post->id) . '" class="btn btn-danger btn-sm" title="Xóa tài liệu?" data-type="confirm">'
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
}
