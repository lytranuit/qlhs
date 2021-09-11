<?php

namespace App\Models;

use CodeIgniter\Model;

class OptionModel extends Model
{
    protected $table      = 'options';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['value'];
    protected $protectFields = false;

    //protected $useTimestamps = false;
    //protected $createdField  = 'created_at';
    //protected $updatedField  = 'updated_at';
    //protected $deletedField  = 'deleted_at';

    //protected $validationRules    = [];
    //protected $validationMessages = [];
    //protected $skipValidation     = true;
    function get_options_in($id)
    {
        if (!is_array($id)) {
            $id = array($id);
        }

        $builder = $this->db->table('options');
        $rows = $builder->whereIn('key', $id)->get()->getResult("array");
        $return = array();
        foreach ($rows as $row) {
            $return[$row['key']] = $row['value'];
        }
        return $return;
    }
}
