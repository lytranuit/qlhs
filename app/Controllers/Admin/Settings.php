<?php

namespace App\Controllers\Admin;


class Settings extends BaseController
{
    public function index()
    {
        if (isset($_POST['post'])) {
            $data = $_POST;
            $option_model = model("OptionModel");

            foreach ($data['id'] as $key => $id) {
                $value = isset($data["value$id"]) ? $data["value$id"] : null;
                $option_model->update($id, array('value' => $value));
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            $option_model = model("OptionModel");

            $tins =
                //echo "<pre>";
                //print_r($tins);
                //die();
                $this->data['mail_review'] = $option_model->where("group", 'mail_review')->orderBy("order", "asc")->findAll();;
            $this->data['mail_expire'] = $option_model->where("group", 'mail_expire')->orderBy("order", "asc")->findAll();;

            return view($this->data['content'], $this->data);
        }
    }
}
