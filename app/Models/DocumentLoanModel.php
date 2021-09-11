<?php

namespace App\Models;


use CodeIgniter\Model;

class DocumentLoanModel extends Model
{
    protected $table      = 'document_loan';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = true;

    protected $allowedFields = [];
    protected $protectFields = false;
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
    //protected $skipValidation     = true;
}
