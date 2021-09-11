<?php

namespace App\Models;

use CodeIgniter\Model;

class CategoryModel extends Model
{
    protected $table      = 'category';
    protected $primaryKey = 'id';

    protected $returnType     = 'App\Entities\Category';
    protected $useSoftDeletes = true;

    protected $allowedFields = [];

    protected $protectFields = false;
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
            if (in_array("document", $relation)) {
                $category_id = $row_a->id;
                $builder = $this->db->table('document_category')->join("category", "document_category.category_id = category.id");
                $row_a->documents = $builder->where('category_id', $category_id)->get()->getResult();
            }
        } else {
            if (in_array("document", $relation)) {
                $category_id = $row_a['id'];
                $builder = $this->db->table('document_category')->join("category", "document_category.category_id = category.id");
                $row_a['documents'] = $builder->where('category_id', $category_id)->get()->getResult("array");
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
