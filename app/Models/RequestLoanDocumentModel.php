<?php

namespace App\Models;


use App\Models\BaseModel;

class RequestLoanDocumentModel extends BaseModel
{
    protected $table      = 'request_loan_document';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;
    protected $useTimestamps = false;

    protected $skipValidation     = true;
}
