<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ScheduleDetails extends Model
{
    use HasFactory;
    protected $table ='tbl_schedule';
    protected  $primaryKey = 's_id';

  public function getClassSchedule($id)
  {
    return DB::table('tbl_schedule')->select('tbl_schedule.s_id','tbl_schedule.class_id','tbl_schedule.teacher_id','tbl_schedule.schedule_desc','tbl_schedule.schedule_date','tbl_schedule.schedule_time','class_details.class_title','users.name')
    ->leftjoin('class_details', 'tbl_schedule.class_id', 'class_details.id')
    ->leftjoin('users', 'tbl_schedule.teacher_id', 'users.id')
    ->where('tbl_schedule.class_id',$id)->paginate(10);
  }

  public function getSingleSchedule($id)
  {
    return DB::table('tbl_schedule')->select('tbl_schedule.s_id','tbl_schedule.class_id','tbl_schedule.teacher_id','tbl_schedule.schedule_desc','tbl_schedule.schedule_time','tbl_schedule.schedule_date')
    ->where('tbl_schedule.s_id',$id)->get();
  }

  public function getScheduleDetails($id,$links_id)
  {
    return DB::table('tbl_schedule')->select('tbl_schedule.*')
    ->where('tbl_schedule.class_id',$id)
    ->whereNotIn('tbl_schedule.s_id',$links_id)
    ->get();
  }

  public function getClassRecordingData($id,$links_id)
  {
    return DB::table('tbl_schedule')->select('tbl_schedule.*')
    ->where('tbl_schedule.class_id',$id)
    ->whereIn('tbl_schedule.s_id',$links_id)
    ->get();
  }
  
}