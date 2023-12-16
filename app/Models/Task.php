<?php

namespace App\Models;

//use CodeIgniter\Model;
use App\Models\CustomModel;

class Task extends CustomModel
{
    protected $table            = 'tasks';
    //protected $primaryKey       = 'id';
    //protected $useAutoIncrement = true;
    //protected $returnType       = 'array';
    //protected $useSoftDeletes   = false;
    //protected $protectFields    = true;
    protected $allowedFields    = ['title', 'user_id', 'description', 'status'];

    // Dates
    //protected $useTimestamps = false;
    //protected $dateFormat    = 'datetime';
    //protected $createdField  = 'created_at';
    //protected $updatedField  = 'updated_at';
    //protected $deletedField  = 'deleted_at';

    protected $fieldsWithFormat = [
        'title as Titulo', 
        'description as Description', 
        'status as Estado',  
        'created_at as "Fecha de creacion"', 
        'updated_at as "Fecha de ultima actualizacion"', 
    ];

    
}
