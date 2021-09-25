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

        // $mail_review = $option_model->get_options_group("mail_review");
        // $before_send = $mail_review['before_send'];
        // $where = $document_model->where("date_review !=", "0000-00-00")->where("date_review <", date("Y-m-d", strtotime("+$before_send day")));
        // $this->data['documents_review'] = $where->findAll();


        // $mail_expire = $option_model->get_options_group("mail_expire");
        // $before_send = $mail_expire['before_send'];
        // $where = $document_model->where("date_expire !=", "0000-00-00")->where("date_expire <", date("Y-m-d", strtotime("+$before_send day")));
        // $this->data['documents_expire'] = $where->findAll();



        return view($this->data['content'], $this->data);
    }
    public function tablereview()
    {
        $Document_model = model("DocumentModel", false);
        $option_model = model("OptionModel");
        $mail_review = $option_model->get_options_group("mail_review");
        $before_send = $mail_review['before_send'];
        $limit = $this->request->getVar('length');
        $start = $this->request->getVar('start');
        $search = $this->request->getPost('search')['value'];
        $page = ($start / $limit) + 1;
        $where = $Document_model->where("date_review !=", "0000-00-00")->where("date_review <", date("Y-m-d", strtotime("+$before_send day")));
        // echo "<pre>";
        // print_r($swhere);
        $totalData = $where->countAllResults(false);

        //echo "<pre>";
        //print_r($totalData);
        //die();
        $totalFiltered = $totalData;

        if (empty($search)) {
            // $where
            // echo "1";die();
        } else {
            $where->like("code", $search)->like("name_vi", $search);
            $totalFiltered = $where->countAllResults(false);
        }

        // $where = $Document_model;
        $posts = $where->asObject()->orderby("date_review", "DESC")->paginate($limit, '', $page);

        // echo "<pre>";
        // print_r($posts);
        // die();
        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['name'] =  '<a class="" href="' . base_url("/admin/document/edit/$post->id") . '">' . $post->code . '.' . ($post->version < 10 ? "0" . $post->version : $post->version) . ' - ' . $post->name_vi . '</a>';
                $nestedData['date'] =  $post->date_review;
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
    public function tableexpire()
    {
        $Document_model = model("DocumentModel", false);
        $option_model = model("OptionModel");
        $mail_expire = $option_model->get_options_group("mail_expire");
        $before_send = $mail_expire['before_send'];
        $limit = $this->request->getVar('length');
        $start = $this->request->getVar('start');
        $search = $this->request->getPost('search')['value'];
        $page = ($start / $limit) + 1;
        $where = $Document_model->where("date_expire !=", "0000-00-00")->where("date_expire <", date("Y-m-d", strtotime("+$before_send day")));
        // echo "<pre>";
        // print_r($swhere);
        $totalData = $where->countAllResults(false);

        //echo "<pre>";
        //print_r($totalData);
        //die();
        $totalFiltered = $totalData;

        if (empty($search)) {
            // $where
            // echo "1";die();
        } else {
            $where->like("code", $search)->like("name_vi", $search);
            $totalFiltered = $where->countAllResults(false);
        }

        // $where = $Document_model;
        $posts = $where->asObject()->orderby("date_expire", "DESC")->paginate($limit, '', $page);

        // echo "<pre>";
        // print_r($posts);
        // die();
        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['name'] =  '<a class="" href="' . base_url("/admin/document/edit/$post->id") . '">' . $post->code . '.' . ($post->version < 10 ? "0" . $post->version : $post->version) . ' - ' . $post->name_vi . '</a>';
                $nestedData['date'] =  $post->date_expire;
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
}
