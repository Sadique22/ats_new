<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class UserAdditionalinfo extends Model
{
    use HasFactory;
    protected $table ='user_additional_info';
    protected  $primaryKey = 'uai_id';

    
}