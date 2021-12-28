<?php

namespace App\Models;


use App\Models\BaseModel;

class ImportModel extends BaseModel
{
    protected $table      = 'import';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    function format_row($row_a, $relation)
    {
        if (gettype($row_a) == "object") {
            if (in_array("file", $relation)) {
                $file_id = $row_a->file_id;
                $builder = $this->db->table('document_file');
                $row_a->file = $builder->where('id', $file_id)->get()->getFirstRow();
            }
        } else {
            if (in_array("file", $relation)) {
                $file_id = $row_a['file_id'];
                $builder = $this->db->table('document_file');
                $row_a['file'] = $builder->where('id', $file_id)->get()->getFirstRow("array");
            }
        }
        return $row_a;
    }
}
