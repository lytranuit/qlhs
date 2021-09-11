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

            $tins = $option_model->where("group", 'general')->orderBy("order", "asc")->findAll();
            //echo "<pre>";
            //print_r($tins);
            //die();
            $this->data['tins'] = $tins;

            return view($this->data['content'], $this->data);
        }
    }
    public function sendemail()
    { /////// trang ca nhan
        $option_model = model("OptionModel");
        if (isset($_POST['settings'])) {
            $data = $_POST;
            foreach ($data['id'] as $key => $id) {
                $value = $data['value'][$key];
                $option_model->update($id, array('value' => $value));
            }
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        } else {
            $tins = $option_model->where("group", 'send_mail')->orderBy("order", "asc")->asObject()->findAll();
            $this->data['tins'] = $tins;

            load_editor($this->data);
            //            echo "<pre>";
            //            print_r($tins);
            //            die();
            return view($this->data['content'], $this->data);
        }
    }
}
