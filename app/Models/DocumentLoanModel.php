<?php

namespace App\Models;


use App\Models\BaseModel;

class DocumentLoanModel extends BaseModel
{
    protected $table      = 'document_loan';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    function format_row($row_a, $relation)
    {
        if (gettype($row_a) == "object") {
            if (in_array("user", $relation)) {
                $user_id = $row_a->user_id;
                $builder = $this->db->table('auth_users');
                $row_a->user = $builder->where('id', $user_id)->get()->getFirstRow();
            }
            if (in_array("user_receive", $relation)) {
                $user_id = $row_a->user_id_receive;
                $builder = $this->db->table('auth_users');
                $row_a->user_receive = $builder->where('id', $user_id)->get()->getFirstRow();
            }
            if (in_array("document", $relation)) {
                $document_id = $row_a->document_id;
                $builder = $this->db->table('document');
                $row_a->document = $builder->where('id', $document_id)->get()->getFirstRow();
            }
            if (in_array("status_loan", $relation)) {
                $status_id = $row_a->status_id_loan;
                $builder = $this->db->table('document_status');
                $row_a->status_loan = $builder->where('id', $status_id)->get()->getFirstRow();
            }

            if (in_array("status_return", $relation)) {
                $status_id = $row_a->status_id_return;
                $builder = $this->db->table('document_status');
                $row_a->status_return = $builder->where('id', $status_id)->get()->getFirstRow();
            }
        } else {
            if (in_array("user", $relation)) {
                $user_id = $row_a['user_id'];
                $builder = $this->db->table('auth_users');
                $row_a['user'] = $builder->where('id', $user_id)->get()->getFirstRow("array");
            }
            if (in_array("user_receive", $relation)) {
                $user_id = $row_a['user_id_receive'];
                $builder = $this->db->table('auth_users');
                $row_a['user_receive'] = $builder->where('id', $user_id)->get()->getFirstRow("array");
            }
            if (in_array("document", $relation)) {
                $document_id = $row_a['document_id'];
                $builder = $this->db->table('document');
                $row_a['document'] = $builder->where('id', $document_id)->get()->getFirstRow("array");
            }
            if (in_array("status_loan", $relation)) {
                $status_id = $row_a['status_id_loan'];
                $builder = $this->db->table('document_status');
                $row_a['status_loan'] = $builder->where('id', $status_id)->get()->getFirstRow("array");
            }
            if (in_array("status_return", $relation)) {
                $status_id = $row_a['status_id_return'];
                $builder = $this->db->table('document_status');
                $row_a['status_return'] = $builder->where('id', $status_id)->get()->getFirstRow("array");
            }
        }
        return $row_a;
    }
}
