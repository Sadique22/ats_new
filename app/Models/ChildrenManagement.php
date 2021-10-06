<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ChildrenManagement extends Model
{
    use HasFactory;
    protected $table ='tbl_children_management';
    protected  $primaryKey = 'child_id';

}