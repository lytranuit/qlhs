<?php

namespace App\Models;


use App\Models\BaseModel;

class DocumentCategoryModel extends BaseModel
{
    protected $table      = 'document_category';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    public function document_by_category($id)
    {
        $builder = $this->db->table('document_category');
        $builder->select('*,document_category.id as pc_id');
        return $builder->where(array('category_id' => $id))->orderby('document.date', "DESC")->join('document', 'document.id = document_category.document_id')->get()->getResult();
    }
}
