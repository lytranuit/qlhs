<?php

namespace App\Controllers\Admin;

class User extends BaseController
{

    public function index()
    {
        return view($this->data['content'], $this->data);
    }

    public function add()
    { /////// trang ca nhan
        if (isset($_POST['username'])) {
            helper("auth");

            $User_model = model(UserModel::class);
            $config =  config('Auth');
            $allowedPostFields = array_merge(['password'], $config->validFields, $config->personalFields);

            $data = $this->request->getPost();
            $data['email'] = time() . "@gmail.com";
            //print_r($data);
            //die();
            $user = new \Myth\Auth\Entities\User($data);
            //print_r($user);
            //die();
            if (!$User_model->save($user)) {
                print_r($User_model->errors());
                die();
            }
            $id = $User_model->getInsertID();

            if (isset($data['groups'])) {
                $groupModel = model(GroupModel::class);
                foreach ($data['groups'] as $row) {
                    $groupModel->addUserToGroup($id, $row);
                }
            }
            return redirect()->to(base_url('admin/user'));
        } else {

            $group_model = model("GroupModel");
            $this->data['groups'] = $group_model->asArray()->findAll();
            return view($this->data['content'], $this->data);
        }
    }

    public function edit($id)
    { /////// trang ca nhan
        if (isset($_POST['dangtin'])) {

            $User_model = model("UserModel");
            $data = $this->request->getPost();
            $obj = $User_model->find($id);
            $obj->fill($data);
            $User_model->save($obj);

            if (isset($data['groups'])) {
                $groupModel = model(GroupModel::class);
                $groupModel->removeUserFromAllGroups($id);
                foreach ($data['groups'] as $row) {
                    $groupModel->addUserToGroup($id, $row);
                }
            }
            return redirect()->to(base_url('admin/user'));
        } else {
            $User_model = model("UserModel");
            $tin = $User_model->where(array('id' => $id))->asObject()->first();
            $User_model->relation($tin, array("groups"));
            $tin->groups = array_map(function ($item) {
                return $item['group_id'];
            }, $tin->groups);
            //echo "<pre>";
            //print_r($tin);
            //die();

            $group_model = model("GroupModel");
            $this->data['groups'] = $group_model->asArray()->findAll();
            $this->data['tin'] = $tin;
            return view($this->data['content'], $this->data);
        }
    }

    public function remove($id)
    { /////// trang ca nhan
        $User_model = model("UserModel");
        $User_model->delete($id);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function table()
    {
        $User_model = model(UserModel::class);
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
        $user_model = model("UserModel");
        $check = $user_model->where(array("username" => $username))->asArray()->findAll();
        if (!$check) {
            echo json_encode(array('success' => 1));
        } else {
            echo json_encode(array('success' => 0, 'msg' => "Tài khoản đã tồn tại!"));
        }
    }
}
