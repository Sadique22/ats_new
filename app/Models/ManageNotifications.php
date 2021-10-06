<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ManageNotifications extends Model
{
    use HasFactory;
    protected $table ='tbl_notifications';
    protected  $primaryKey = 'tn_id';

	public function getStudentNotifications($id)
	{
	  return DB::table('tbl_notifications')->select('tbl_notifications.*')
	    ->where('tbl_notifications.not_to',$id)
	    ->where('tbl_notifications.tn_user_type',3)
	    ->where('tbl_notifications.not_seen',0)
	    ->orderBy('tbl_notifications.tn_id','desc')
	    ->limit(5)
	    ->get();
	}

	public function getTeacherNotifications($id)
	{
	  return DB::table('tbl_notifications')->select('tbl_notifications.*')
	    ->where('tbl_notifications.not_to',$id)
	    ->where('tbl_notifications.tn_user_type',2)
	    ->where('tbl_notifications.not_seen',0)
	    ->orderBy('tbl_notifications.tn_id','desc')
	    ->limit(5)
	    ->get();
	}

	public function getAdminNotifications($id)
	{
	  return DB::table('tbl_notifications')->select('tbl_notifications.*')
	    ->where('tbl_notifications.not_to',$id)
	    ->where('tbl_notifications.tn_user_type',1)
	    ->where('tbl_notifications.not_seen',0)
	    ->orderBy('tbl_notifications.tn_id','desc')
	    ->limit(5)
	    ->get();
	}

	public function getStudentAllNotifications($id)
	{
	  return DB::table('tbl_notifications')->select('tbl_notifications.*')
	    ->where('tbl_notifications.not_to',$id)
	    ->where('tbl_notifications.tn_user_type',3)
	    ->orderBy('tbl_notifications.tn_id','desc')
	    ->paginate(10);
	}

	public function getTeacherAllNotifications($id)
	{
	  return DB::table('tbl_notifications')->select('tbl_notifications.*')
	    ->where('tbl_notifications.not_to',$id)
	    ->where('tbl_notifications.tn_user_type',2)
	    ->orderBy('tbl_notifications.tn_id','desc')
	    ->paginate(10);
	}

	public function getAdminAllNotifications($id)
	{
	  return DB::table('tbl_notifications')->select('tbl_notifications.*')
	    ->where('tbl_notifications.not_to',$id)
	    ->where('tbl_notifications.tn_user_type',1)
	    ->orderBy('tbl_notifications.tn_id','desc')
	    ->paginate(10);
	}
}