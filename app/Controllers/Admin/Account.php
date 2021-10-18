<?php

namespace App\Controllers\Admin;

class Account extends BaseController
{
    public function index()
    {
        $id_user = user_id();
        $User_model = model("Myth\Auth\Authorization\UserModel");
        // print_r($_POST);
        // die();
        if (isset($_POST['edit_user'])) {
            //      print_r($_POST);
            // die();
            $data = $this->request->getPost();
            $obj = $User_model->create_object($data);
            // print_r($obj);die();
            $code = $User_model->update($id_user, $obj);
            // print_r($code);
            // die();
        }
        $user = $User_model->where(array('id' => $id_user))->asObject()->first();
        $this->data['user'] = $user;
        return view($this->data['content'], $this->data);
    }
    function changepass()
    {
        $id_user = user_id();
        $User_model = model("Myth\Auth\Authorization\UserModel");

		$this->auth = service('authentication');
        $user = $User_model->find($id_user);
        if (!isset($_POST['password']) || (isset($_POST['password']) && !$this->auth->attempt(['username' => $user->username, 'password' => $_POST['password']], false))) {
            echo json_encode(array('code' => 402, "msg" => "Mật khẩu cũ không đúng."));
            die();
        }
        if (!isset($_POST['confirmpassword']) || !isset($_POST['newpassword']) || (isset($_POST['newpassword']) && isset($_POST['confirmpassword']) && $_POST['newpassword'] != $_POST['confirmpassword'])) {
            echo json_encode(array('code' => 403, "msg" => "Xác nhận mật khẩu mới không đúng."));
            die();
        }
        // echo "<pre>";
        // print_r($user);
        // die();
        $user->setPassword($_POST['newpassword']);
        $User_model->save($user);
        echo json_encode(array('code' => 400, "msg" => "Thay đổi mật khẩu thành công."));
        die();
    }
}
