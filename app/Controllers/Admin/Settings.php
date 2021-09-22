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
                $value = $data['value'][$key];
                $option_model->update($id, array('value' => $value));
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            $option_model = model("OptionModel");

            $tins = $option_model->where("group", 'send_mail')->orderBy("order", "asc")->findAll();
            //echo "<pre>";
            //print_r($tins);
            //die();
            $this->data['tins'] = $tins;

            return view($this->data['content'], $this->data);
        }
    }
}
