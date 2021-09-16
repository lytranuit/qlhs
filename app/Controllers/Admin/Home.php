<?php

namespace App\Controllers\Admin;


class Home extends BaseController
{
    public function index()
    {
        $document_model = model("DocumentModel");
        $this->data['num_doc'] = $document_model->countAllResults();
        $this->data['num_doc_in_inventory'] = $document_model->where("status_id", 2)->countAllResults();
        $this->data['num_doc_in_loan'] = $document_model->where("status_id", 4)->countAllResults();
        $this->data['num_doc_expire'] = $document_model->where("date_expire <", date("Y-m-d"))->countAllResults();
        return view($this->data['content'], $this->data);
    }
}
