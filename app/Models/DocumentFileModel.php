<?php

namespace App\Models;


use App\Models\BaseModel;

class DocumentFileModel extends BaseModel
{
    protected $table      = 'document_file';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
}
