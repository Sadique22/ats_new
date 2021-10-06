<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class RequestNewSchedule extends Model
{
    use HasFactory;
    protected $table ='tbl_schedule_request';
    protected  $primaryKey = 'sr_id';

    public function getRequestedSchedule(){
    return DB::table('tbl_schedule_request as request')
       ->leftjoin('users as sb', 'request.student_id', '=' ,'sb.id')
       ->leftjoin('users as st', 'request.teacher_id', '=' ,'st.id')
       ->leftjoin('class_details','request.class_id', 'class_details.id')
       ->select('request.*','sb.name as student','st.name as teacher','class_details.class_title')
       ->orderBy('request.sr_id','desc')
       ->paginate(15);
   }

   public function getUserRequestDetails($user_id){
    return DB::table('tbl_schedule_request')
       ->select('tbl_schedule_request.*','users.name','class_details.class_title')
       ->leftjoin('class_details','tbl_schedule_request.class_id', 'class_details.id')
       ->leftjoin('users','tbl_schedule_request.teacher_id','users.id')
       ->orderBy('tbl_schedule_request.sr_id','desc')
       ->where('tbl_schedule_request.student_id',$user_id)
       ->paginate(15);
   }
}    
