<?php

namespace App\Controllers\Admin;


class Home extends BaseController
{
    public function index()
    {
        $document_model = model("DocumentModel");
        $option_model = model("OptionModel");
        $DocumentStatus_model = model("DocumentStatusModel");
        $this->data['num_doc'] = $document_model->countAllResults();
        $this->data['num_doc_in_inventory'] = $document_model->where("status_id", 2)->countAllResults();
        $this->data['num_doc_in_loan'] = $document_model->where("status_id", 4)->countAllResults();

        $mail_expire = $option_model->get_options_group("mail_expire");
        $before_send_expire = $mail_expire['before_send'];
        $this->data['num_doc_expire'] = $document_model->where("date_expire <", date("Y-m-d", strtotime("+$before_send_expire day")))->countAllResults();


        $mail_review = $option_model->get_options_group("mail_review");
        $before_send_review = $mail_review['before_send'];
        $this->data['num_doc_review'] = $document_model->where("date_review <", date("Y-m-d", strtotime("+$before_send_review day")))->countAllResults();

        $this->data['num_doc_out_review'] = $document_model->where("date_review <", date("Y-m-d"))->countAllResults();

        
        $this->data['status'] = $DocumentStatus_model->asObject()->findAll();

        return view($this->data['content'], $this->data);
    }
}
