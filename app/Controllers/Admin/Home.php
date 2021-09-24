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
    function cron()
    {

        $document_model = model("DocumentModel");
        $option_model = model("OptionModel");

        $mail_review = $option_model->get_options_group("mail_review");
        if ($mail_review['is_send']) {
            $before_send = $mail_review['before_send'];
            $where = $document_model->where("date_review !=", "0000-00-00")->where("date_review <", date("Y-m-d", strtotime("+$before_send day")));
            if ($mail_review['type_send'] == 1)
                $where->where("time_send_review", NULL);
            $documents = $where->findAll();
            // echo "<pre>";
            // print_r($documents);
            // die();
            if (!empty($documents)) {
                $email = \Config\Services::email();
                // echo "<pre>";
                // print_r($email);
                // die();
                // $config['protocol'] = 'sendmail';
                // $config['mailPath'] = '/usr/sbin/sendmail';
                // $config['charset']  = 'iso-8859-1';
                // $config['wordWrap'] = true;

                // $email->initialize($config);


                $email->setFrom('lytranuit@gmail.com', "PYMEPHARCO SENDER");
                $email->setTo(explode(",", $mail_review['email_to']));
                // $email->setCC('another@another-example.com');
                // $email->setBCC('them@their-example.com');

                $data['documents'] = $documents;
                $message = view("backend/template/review", $data);
                // echo $message;die();
                $email->setSubject($mail_review['email_subject']);
                $email->setMessage($message);
                // echo "<pre>";
                // print_r($email);
                // die();
                if (!$email->send()) {
                    // Will only print the email headers, excluding the message subject and body
                } else {
                    $ids = array_map(function ($item) {
                        return $item->id;
                    }, $documents);
                    $document_model->update($ids, array('time_send_review' => date("Y-m-d H:i:s")));
                }
                echo $email->printDebugger();
            }
        }

        $mail_expire = $option_model->get_options_group("mail_expire");
        if ($mail_expire['is_send']) {
            $before_send = $mail_expire['before_send'];
            $where = $document_model->where("date_expire!=", "0000-00-00")->where("date_expire <", date("Y-m-d", strtotime("+$before_send day")));
            if ($mail_expire['type_send'] == 1)
                $where->where("time_send_expire", NULL);
            $documents = $where->findAll();
            // echo "<pre>";
            // print_r($documents);
            // die();
            if (!empty($documents)) {
                $email = \Config\Services::email();
                // echo "<pre>";
                // print_r($email);
                // die();
                // $config['protocol'] = 'sendmail';
                // $config['mailPath'] = '/usr/sbin/sendmail';
                // $config['charset']  = 'iso-8859-1';
                // $config['wordWrap'] = true;

                // $email->initialize($config);


                $email->setFrom('lytranuit@gmail.com', "PYMEPHARCO SENDER");
                $email->setTo(explode(",", $mail_expire['email_to']));
                // $email->setCC('another@another-example.com');
                // $email->setBCC('them@their-example.com');

                $data['documents'] = $documents;
                $message = view("backend/template/expire", $data);
                // echo $message;die();
                $email->setSubject($mail_expire['email_subject']);
                $email->setMessage($message);
                // echo "<pre>";
                // print_r($email);
                // die();
                if (!$email->send()) {
                    // Will only print the email headers, excluding the message subject and body
                } else {
                    $ids = array_map(function ($item) {
                        return $item->id;
                    }, $documents);
                    $document_model->update($ids, array('time_send_expire' => date("Y-m-d H:i:s")));
                }
                echo $email->printDebugger();
            }
        }
    }
}
