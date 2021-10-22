<?php

namespace App\Models;

use CodeIgniter\Model;

class BaseModel extends Model
{
    protected $primaryKey = 'id';

    protected $returnType     = 'object';
    protected $useSoftDeletes = true;

    protected $allowedFields = [];

    protected $protectFields = false;
    protected $afterInsert = ['insertTrail'];

    protected $afterDelete = ['deleteTrail'];
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

    function create_object($data)
    {
        $db = $this->db;
        $array = $db->getFieldNames($this->table);
        $obj = array();
        foreach ($array as $key) {
            if (isset($data[$key])) {
                $obj[$key] = $data[$key];
                if (($key == "date_expire" || $key == "date_effect" || $key == "date_review") && $data[$key] == "")
                    $obj[$key] = null;
            } else
                continue;
        }

        return $obj;
    }
    public function insertTrail($params)
    {
        $id = $params['id'];
        $data = $params['data'];
        $this->trail($id, 'insert', $data, null, null);
        return $params;
    }

    public function deleteTrail($params)
    {
        // echo "<pre>";
        // print_r($params);
        // die();

        $list_id = $params['id'];
        $result = $params['result'];
        if (empty($list_id)) return;
        if ($result != 1) return;
        foreach ($list_id as $id) {
            $this->trail($id, 'delete', null, $id, null);
        }
        return $params;
    }
    // public function updateTrail($params)
    // {
    //     $id = $params['id'];
    //     $data = $params['data'];
    //     // $object = $this->find()
    //     echo "<pre>";
    //     print_r($params);
    //     die();
    //     // $this->trail($id, 'update', null, $data, null, null);
    // }

    public function trail($status, $event, $set = NULL, $previous_values = NULL, $description = null)
    {
        $table = $this->table;
        if (!$status) return 1;  // event not performed
        if ($event == 'update') {
            $this->diff_on_update($previous_values, $set);
            //data has not been update
            if (empty($previous_values) && empty($set))
                return 1;
        }
        $old_value = $new_value = null;
        if (!empty($previous_values)) $old_value = json_encode($previous_values, JSON_UNESCAPED_UNICODE);
        if (!empty($set)) $new_value = json_encode($set, JSON_UNESCAPED_UNICODE); // For delete event it stores where condition


        if (is_null($description)) {
            if ($event === 'insert') {
                $description =   "USER '" . user()->name . "' added a $this->table";
            } elseif ($event == "update") {
                $description =   "USER '" . user()->name . "' edited a $this->table";
            } elseif ($event == "delete") {
                $description = "USER '" . user()->name . "' removed a $this->table";
            }
        }
        $request = \Config\Services::request();
        return $this->db->table('user_audit_trails')->insert(
            array(
                'user_id' => user_id(),
                'name' => user()->name,
                'event' => $event,
                'table_name' => $table,
                'old_values' => $old_value,
                'new_values' => $new_value,
                'url' => $request->getPath(),
                'ip_address' => $request->getIPAddress(),
                'user_agent' => $request->getUserAgent(),
                'description' => $description,
                'created_at' => date('Y-m-d H:i:s'),
            )
        );
    }
    public function diff_on_update(&$old_value, &$new_value)
    {
        $old = [];
        $new = [];
        foreach ($new_value as $key => $val) {
            if (isset($new_value[$key])) {
                if (isset($old_value[$key])) {
                    if ($new_value[$key] != $old_value[$key]) {
                        $old[$key] = $old_value[$key];
                        $new[$key] = $new_value[$key];
                    }
                } else {
                    $old[$key] = '';
                    $new[$key] = $new_value[$key];
                }
            }
        }

        $old_value = $old;
        $new_value = $new;
    }
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    //protected $validationRules    = [];
    //protected $validationMessages = [];
    protected $skipValidation     = true;
}
