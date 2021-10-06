<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ClassRecordings extends Model
{
    use HasFactory;
    protected $table ='tbl_class_recordings';
    protected  $primaryKey = 'cr_id';

}