<?php

namespace App\Libraries;

class HeaderWidget
{
    private $data = array();
    public function __construct()
    {
    }

    public function index()
    {
        $category_model = model("CategoryModel");
        $this->data['categories'] = $category_model->where("is_menu", 0)->orderby("date", "DESC")->findAll();
        $this->data['menus'] = $category_model->where("is_menu", 1)->orderby("date", "DESC")->findAll();
        //echo "<pre>";
        //print_r($this->data['news']);
        //die();
        return view("lib/header/index", $this->data);
    }
}
