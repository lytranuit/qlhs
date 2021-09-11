<?php

namespace App\Models;


use CodeIgniter\Model;

class DocumentCategoryModel extends Model
{
    protected $table      = 'document_category';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = [];
    protected $protectFields = false;

    //protected $useTimestamps = false;
    //protected $createdField  = 'created_at';
    //protected $updatedField  = 'updated_at';
    //protected $deletedField  = 'deleted_at';

    //protected $validationRules    = [];
    //protected $validationMessages = [];
    //protected $skipValidation     = true;
    protected function initialize()
    {
        $db = $this->db;
        $array = $db->getFieldNames($this->table);
        foreach ($array as $key) {
            $this->allowedFields[] = $key;
        }
    }
    public function document_by_category($id)
    {
        $builder = $this->db->table('document_category');
        $builder->select('*,document_category.id as pc_id');
        return $builder->where(array('category_id' => $id))->orderby('document.date', "DESC")->join('document', 'document.id = document_category.document_id')->get()->getResult();
    }
}
