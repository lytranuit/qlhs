<?php

namespace App\Models;

use CodeIgniter\Database\BaseBuilder;
use CodeIgniter\Model;
use phpDocumentor\Reflection\Types\Null_;

class DocumentModel extends Model
{
    protected $table      = 'document';
    protected $primaryKey = 'id';

    protected $returnType     = 'App\Entities\Document';
    protected $useSoftDeletes = true;
    protected $protectFields = false;

    // protected $allowedFields = ['image_url', 'name_vi', 'name_en', 'name_jp', 'description_vi', 'description_en', 'description_jp', 'version','date','date_effect','date_'];

    protected function initialize()
    {
        $db = $this->db;
        $array = $db->getFieldNames($this->table);
        foreach ($array as $key) {
            $this->allowedFields[] = $key;
        }
    }

    public function relation(&$data, $relation = array())
    {
        $type = gettype($data);
        if ($type == "array" && !isset($data['id'])) {
            foreach ($data as &$row) {
                $row = $this->format_row($row, $relation);
            }
        } else {
            $data = $this->format_row($data, $relation);
        }

        return $data;
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
        }
        return $row_a;
    }
    function create_object($data)
    {
        $db = $this->db;
        $array = $db->getFieldNames($this->table);
        $obj = array();
        foreach ($array as $key) {
            if (isset($data[$key])) {
                $obj[$key] = $data[$key];
            } else
                continue;
        }

        return $obj;
    }
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    //protected $validationRules    = [];
    //protected $validationMessages = [];
    protected $skipValidation     = true;
}
