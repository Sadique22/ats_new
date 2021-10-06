<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class SwitchRole extends Model
{
    use HasFactory;
    protected $table ='tbl_switch_role';
    protected  $primaryKey = 'tsr_id';

    public function getSwitchRoleRequests(){
    	return DB::table('tbl_switch_role')->select('tbl_switch_role.*', 'users.name')->join('users', 'tbl_switch_role.user_id', 'users.id')->whereIn('tsr_user_type',[3, 4])->paginate(10);
    }
}