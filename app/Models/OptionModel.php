<?php

namespace App\Models;


use App\Models\BaseModel;

class OptionModel extends BaseModel
{
    protected $table      = 'options';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
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
