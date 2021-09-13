<?php

namespace App\Models;

use CodeIgniter\Database\BaseBuilder;
use App\Models\BaseModel;
use phpDocumentor\Reflection\Types\Null_;

class DocumentModel extends BaseModel
{
    protected $table      = 'document';
    protected $primaryKey = 'id';

    protected $returnType     = 'App\Entities\Document';

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
}
