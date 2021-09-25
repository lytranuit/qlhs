<?php

namespace App\Controllers\Admin;

class User extends BaseController
{
    public function __construct()
    {
        if (!in_groups(array('admin'))) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound(lang('Auth.notEnoughPrivilege'));
        }
    }

    public function index()
    {
        return view($this->data['content'], $this->data);
    }

    public function add()
    { /////// trang ca nhan
        if (isset($_POST['username'])) {
            $User_model = model('Myth\Auth\Authorization\UserModel');
            $data = $this->request->getPost();
            $data['email'] = time() . "@gmail.com";
            //print_r($data);
            //die();
            $user = new \Myth\Auth\Entities\User($data);
            $data['password_hash'] = $user->password_hash;
            $obj = $User_model->create_object($data);
            // echo "<pre>";
            // print_r($obj);
            // die();
            $id = $User_model->insert($obj);
            if ($id > 0) {
                if (isset($data['groups'])) {
                    $group_model = model("Myth\Auth\Authorization\GroupModel");
                    foreach ($data['groups'] as $row) {
                        $group_model->addUserToGroup($id, $row);
                    }
                }
                return redirect()->to(base_url('admin/user'));
            } else {
                print_r($User_model->errors());
            }
            // print_r($User_model->errors());
            // print_r($id);
            // die();
        } else {

            $group_model = model("Myth\Auth\Authorization\GroupModel");
            $this->data['groups'] = $group_model->asArray()->findAll();
            return view($this->data['content'], $this->data);
        }
    }

    public function edit($id)
    { /////// trang ca nhan
        if (isset($_POST['dangtin'])) {

            $User_model = model("Myth\Auth\Authorization\UserModel");
            $data = $this->request->getPost();

            $obj_old = $User_model->asArray()->find($id);
            $obj = $User_model->create_object($data);
            $User_model->update($id, $obj);

            // print_r($User_model->errors());
            // die();
            if (isset($data['groups'])) {
                $group_model = model("Myth\Auth\Authorization\GroupModel");
                // print_r($Myth\Auth\Authorization\GroupModel);
                // die();
                $group_model->removeUserFromAllGroups($id);
                foreach ($data['groups'] as $row) {
                    $group_model->addUserToGroup($id, $row);
                }
            }

            $User_model->trail(1, 'update', $obj, $obj_old, null);
            return redirect()->to(base_url('admin/user'));
        } else {
            $User_model = model("Myth\Auth\Authorization\UserModel");
            $tin = $User_model->where(array('id' => $id))->asObject()->first();
            $User_model->relation($tin, array("groups"));
            $tin->groups = array_map(function ($item) {
                return $item['group_id'];
            }, $tin->groups);
            //echo "<pre>";
            //print_r($tin);
            //die();

            $group_model = model("Myth\Auth\Authorization\GroupModel");
            $this->data['groups'] = $group_model->asArray()->findAll();
            $this->data['tin'] = $tin;
            return view($this->data['content'], $this->data);
        }
    }

    public function remove($id)
    { /////// trang ca nhan
        $User_model = model("Myth\Auth\Authorization\UserModel");
        $User_model->delete($id);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function table()
    {
        $User_model = model('Myth\Auth\Authorization\UserModel');
        $limit = $this->request->getVar('length');
        $start = $this->request->getVar('start');
        $page = ($start / $limit) + 1;
        $where = $User_model;

        $totalData = $where->countAllResults();
        //echo "<pre>";
        //print_r($totalData);
        //die();
        $totalFiltered = $totalData;

        $where = $User_model;
        $posts = $where->asObject()->orderby("id", "DESC")->paginate($limit, '', $page);
        //echo "<pre>";
        //print_r($posts);
        //die();
        $User_model->relation($posts, array("groups"));
        //echo "<pre>";
        //print_r($posts);
        //die();
        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['id'] = $post->id;
                $nestedData['name'] = $post->name;
                $nestedData['description'] =  $post->description;
                $nestedData['username'] =  $post->username;
                $nestedData['groups'] =  implode(", ", $post->groups_string);
                // $image = isset($post->image->src) ? base_url() . $post->image->src : "";
                // $nestedData['image'] = "<img src='$image' width='100'/>";
                $nestedData['action'] = '<a href="' . base_url("admin/user/edit/" . $post->id) . '" class="btn btn-warning btn-sm mr-2" title="edit">'
                    . '<i class="fas fa-pencil-alt">'
                    . '</i>'
                    . '</a>'
                    . '<a href="' . base_url("admin/user/remove/" . $post->id) . '" class="btn btn-danger btn-sm" data-type="confirm" title="remove">'
                    . '<i class="far fa-trash-alt">'
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

    public function checkusername()
    {
        $username = $this->request->getVar('username');
        $user_model = model("Myth\Auth\Authorization\UserModel");
        $check = $user_model->where(array("username" => $username))->asArray()->findAll();
        if (!$check) {
            echo json_encode(array('success' => 1));
        } else {
            echo json_encode(array('success' => 0, 'msg' => "Tài khoản đã tồn tại!"));
        }
    }
}
