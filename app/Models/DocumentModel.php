<?php

namespace App\Models;

use App\Models\BaseModel;
use App\Libraries\Ciqrcode;


class DocumentModel extends BaseModel
{
    protected $table      = 'document';
    protected $primaryKey = 'id';

    protected $returnType     = 'object';

    protected $afterInsert = ['insertTrail', 'create_qr'];

    protected function initialize()
    {

        $this->ciqrcode = new Ciqrcode();
    }
    function format_row($row_a, $relation)
    {
        if (gettype($row_a) == "object") {
            if (in_array("categories", $relation)) {
                $document_id = $row_a->id;
                $builder = $this->db->table('document_category')->join("category", "document_category.category_id = category.id");
                $row_a->categories = $builder->where('document_id', $document_id)->get()->getResult();
            }
            if (in_array("files", $relation)) {
                $document_id = $row_a->id;
                $builder = $this->db->table('document_file');
                $row_a->files = $builder->where('document_id', $document_id)->get()->getResult();
            }
            if (in_array("status", $relation)) {
                $status_id = $row_a->status_id;
                $builder = $this->db->table('document_status');
                $row_a->status = $builder->where('id', $status_id)->get()->getFirstRow();
            }
            if (in_array("type", $relation)) {
                $type_id = $row_a->type_id;
                $builder = $this->db->table('document_type');
                $row_a->type = $builder->where('id', $type_id)->get()->getFirstRow();
            }
            if (in_array("samecode", $relation)) {
                $code = $row_a->code;
                $builder = $this->db->table('document');
                $row_a->samecode = $builder->where("deleted_at", NULL)->where('code', $code)->get()->getResult();
            }
        } else {
            if (in_array("categories", $relation)) {
                $document_id = $row_a['id'];
                $builder = $this->db->table('document_category')->join("category", "document_category.category_id = category.id");
                $row_a['categories'] = $builder->where('document_id', $document_id)->get()->getResult("array");
            }
            if (in_array("files", $relation)) {
                $document_id = $row_a['id'];
                $builder = $this->db->table('document_file');
                $row_a['files'] = $builder->where('document_id', $document_id)->get()->getResult("array");
            }
            if (in_array("status", $relation)) {
                $status_id = $row_a['status_id'];
                $builder = $this->db->table('document_status');
                $row_a['status'] = $builder->where('id', $status_id)->get()->getFirstRow("array");
            }
            if (in_array("type", $relation)) {
                $type_id = $row_a['type_id'];
                $builder = $this->db->table('document_type');
                $row_a['type'] = $builder->where('id', $type_id)->get()->getFirstRow("array");
            }

            if (in_array("samecode", $relation)) {
                $code = $row_a['code'];
                $builder = $this->db->table('document');
                $row_a['samecode'] = $builder->where("deleted_at", NULL)->where('code', $code)->get()->getResult("array");
            }
        }
        return $row_a;
    }
    function set_value_active()
    {
        $this->where("is_active", 1);
        return $this;
    }
    public function create_qr($params)
    {
        // print_r($params);
        // die();
        $id = $params['id'];
        $document = $this->find($id);
        $data_qr = base_url("qrcode/document") . "/" . urlencode($document->uuid);
        $dir = FCPATH . "assets/qrcode/";
        $save_name =  $id . "_" . time()  . '.png';

        /* QR Code File Directory Initialize */
        if (!file_exists($dir)) {
            mkdir($dir, 0775, true);
        }

        /* QR Configuration  */

        /* QR Data  */
        $params['data']     = $data_qr;
        $params['level']    = 'L';
        $params['size']     = 10;
        $params['savename'] = $dir . $save_name;

        $this->ciqrcode->generate($params);
        $this->update($id, array("image_url" => "/assets/qrcode/$save_name"));
        return $params;
    }
}
