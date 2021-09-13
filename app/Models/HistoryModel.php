<?php

namespace App\Models;

use App\Models\BaseModel;

class HistoryModel extends BaseModel
{
    protected $table  = 'user_audit_trails';
    
    protected $useTimestamps = false;
    protected $useSoftDeletes = false;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
