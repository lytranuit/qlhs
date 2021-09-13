<?php

namespace App\Controllers\Admin;


class History extends BaseController
{
    public function index()
    {
        return view($this->data['content'], $this->data);
    }

    public function table()
    {
        $History_model = model("HistoryModel");
        $limit = $this->request->getVar('length');
        $start = $this->request->getVar('start');
        $page = ($start / $limit) + 1;
        $where = $History_model;

        $totalData = $where->countAllResults();
        //echo "<pre>";
        //print_r($totalData);
        //die();
        $totalFiltered = $totalData;
        if (empty($this->request->getPost('search')['value'])) {
            //            $max_page = ceil($totalFiltered / $limit);

            $where = $History_model;
        } else {
            // $search = $this->request->getPost('search')['value'];
            // $sWhere = "(LOWER(code) LIKE LOWER('%$search%') OR name_vi like '%" . $search . "%')";
            // $where =  $Document_model->where($sWhere);
            // $totalFiltered = $where->countAllResults();
            $where = $History_model;
        }

        $where = $History_model;
        $posts = $where->asObject()->orderby("id", "DESC")->paginate($limit, '', $page);

        // $History_model->relation($posts, array('files', "status"));
        //        echo "<pre>";
        //        print_r($posts);
        //        die();
        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['created_at'] = $post->created_at;
                $nestedData['description'] = $post->description;
                $nestedData['name'] = $post->name;
                $nestedData['old_values'] = $post->old_values;
                $nestedData['new_values'] = $post->new_values;
                $nestedData['action'] = '';
                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw" => intval($this->request->getVar('draw')),
            "recordsTotal" => intval($totalFiltered),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $data
        );

        echo json_encode($json_data);
    }
}
