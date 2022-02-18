<?php

namespace App\Models;


use App\Models\BaseModel;

class RequestLoanModel extends BaseModel
{
    protected $table      = 'request_loan';
    protected $returnType     = 'array';
    function format_row($row_a, $relation)
    {
        if (gettype($row_a) == "object") {
            if (in_array("documents", $relation)) {
                $id = $row_a->id;
                $builder = $this->db->table('request_loan_document')->join("document", "document.id = request_loan_document.document_id");
                $row_a->documents = $builder->where('request_id', $id)->get()->getResult();
            }
        } else {

            if (in_array("documents", $relation)) {
                $id = $row_a['id'];
                $builder = $this->db->table('request_loan_document')->join("document", "document.id = request_loan_document.document_id");
                $row_a['documents'] = $builder->where('request_id', $id)->get()->getResult("array");
            }
        }
        return $row_a;
    }
}
