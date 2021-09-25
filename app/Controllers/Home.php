<?php

namespace App\Controllers;


class Home extends BaseController
{
    public function index()
    {
        return redirect()->to(base_url('admin'));
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
            $documents = $where->orderby("date_review", "DESC")->findAll();
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
                    echo $email->printDebugger();
                    file_put_contents(FCPATH . "writable/logs/email_review_error_" . time() . ".txt", $email->printDebugger());
                } else {
                    $ids = array_map(function ($item) {
                        return $item->id;
                    }, $documents);
                    $document_model->update($ids, array('time_send_review' => date("Y-m-d H:i:s")));
                    $content = implode(",", $ids) . "\r\n";
                    $content .= $mail_review['email_to'];

                    $dir = FCPATH . "writable/logs/" . date("Y-m-d");
                    if (!is_dir($dir))
                        mkdir($dir, 0777, true);
                    file_put_contents($dir . "/email_review_" . time() . ".txt", $content);
                }
            }
        }

        $mail_expire = $option_model->get_options_group("mail_expire");
        if ($mail_expire['is_send']) {
            $before_send = $mail_expire['before_send'];
            $where = $document_model->where("date_expire !=", "0000-00-00")->where("date_expire <", date("Y-m-d", strtotime("+$before_send day")));
            if ($mail_expire['type_send'] == 1)
                $where->where("time_send_expire", NULL);
            $documents = $where->orderby("date_expire", "DESC")->findAll();
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
                    echo $email->printDebugger();
                    file_put_contents(FCPATH . "writable/logs/email_expire_error_" . time() . ".txt", $email->printDebugger());
                } else {
                    $ids = array_map(function ($item) {
                        return $item->id;
                    }, $documents);
                    $document_model->update($ids, array('time_send_expire' => date("Y-m-d H:i:s")));

                    $content = implode(",", $ids) . "\r\n";
                    $content .= $mail_expire['email_to'];
                    $dir = FCPATH . "writable/logs/" . date("Y-m-d");
                    if (!is_dir($dir))
                        mkdir($dir, 0777, true);
                    file_put_contents($dir . "/email_expire_" . time() . ".txt", $content);
                }
            }
        }
    }
}
