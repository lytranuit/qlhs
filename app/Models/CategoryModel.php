<?php

namespace App\Models;

use App\Models\BaseModel;

class CategoryModel extends BaseModel
{
    protected $table      = 'category';
    protected $primaryKey = 'id';

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

    // protected $useTimestamps = true;
    // protected $createdField  = 'created_at';
    // protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // //protected $validationRules    = [];
    // //protected $validationMessages = [];
    // protected $skipValidation     = true;
}
