<?php

namespace App\Controllers\Admin;


class Home extends BaseController
{
    public function index()
    {
        return view($this->data['content'], $this->data);
    }
}
