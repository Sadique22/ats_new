<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class UserRole extends Model
{
    use HasFactory;
    protected $table ='tbl_userrole';
    protected $fillable = ['user_type'];

    protected function GetUserRole($id){
     return (new UserRole)->where('ur_id', $id)->value('user_type');
    }

}
