<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RequestNewClass extends Model
{
    use HasFactory;
    protected $table ='tbl_newclass_request';
    protected  $primaryKey = 'ncr_id';

}    
