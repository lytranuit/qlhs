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
        $list = $this->get_category_child($id);
        $builder = $this->db->table('document_category');
        $builder->select('document.*');
        return $builder->join('document', 'document.id = document_category.document_id')->whereIn('category_id', $list)->orderby('document.date', "DESC")->groupBy("document_category.document_id")->get()->getResult();
    }
    function get_category_child($category_id)
    {
        $list = array($category_id);
        $builder = $this->db->table('category');
        $children = $builder->where('parent_id', $category_id)->get()->getResult();
        foreach ($children as $child) {
            $child_id = $child->id;
            $list[] = $child_id;
            $list_child = $this->get_category_child($child_id);
            $list = array_merge($list, $list_child);
        }

        return $list;
    }
    protected $useTimestamps = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
