<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class FeedbackDetails extends Model
{
    use HasFactory;
    protected $table ='tbl_feedbacks';
    protected  $primaryKey = 'f_id';

  public function getStudentFeedback($id)
  {
    return DB::table('tbl_feedbacks')->select('tbl_feedbacks.*','users.name','users.email','users.contact')
    ->join('users', 'tbl_feedbacks.teacher_id', 'users.id')
    ->where('tbl_feedbacks.student_id',$id)->where('tbl_feedbacks.flag',"Feedback by Teacher")->orderBy('tbl_feedbacks.f_id','desc')->paginate(10);
  } 

  public function getTeacherFeedback($id)
  {
    return DB::table('tbl_feedbacks')->select('tbl_feedbacks.*','users.name','users.email','users.contact')
    ->join('users', 'tbl_feedbacks.student_id', 'users.id')
    ->where('tbl_feedbacks.teacher_id',$id)->where('tbl_feedbacks.flag',"Feedback by Student")->orderBy('tbl_feedbacks.f_id','desc')->paginate(10);
  }

  public function getApprovedTeacherFeedback($id)
  {
    return DB::table('tbl_feedbacks')->select('tbl_feedbacks.*','users.name','users.email','users.contact')
    ->join('users', 'tbl_feedbacks.student_id', 'users.id')
    ->where('tbl_feedbacks.teacher_id',$id)->where('tbl_feedbacks.flag',"Feedback by Student")->where('tbl_feedbacks.f_status',1)->orderBy('tbl_feedbacks.f_id','desc')->get();
  }

  public function getClassFeedback($id)
  {
    return DB::table('tbl_feedbacks')->select('tbl_feedbacks.f_id','tbl_feedbacks.class_id','tbl_feedbacks.student_id','tbl_feedbacks.class_feedback','tbl_feedbacks.rating','tbl_feedbacks.flag','tbl_feedbacks.f_status','tbl_feedbacks.created_at','users.name','users.email','users.contact','users.id')
    ->join('users', 'tbl_feedbacks.student_id', 'users.id')
    ->where('tbl_feedbacks.class_id',$id)->where('tbl_feedbacks.flag',"Feedback by Student to Class")->orderBy('tbl_feedbacks.f_id','desc')->paginate(10);
  }

  public function getApprovedClassFeedback($id)
  {
    return DB::table('tbl_feedbacks')->select('tbl_feedbacks.f_id','tbl_feedbacks.class_id','tbl_feedbacks.student_id','tbl_feedbacks.class_feedback','tbl_feedbacks.rating','tbl_feedbacks.flag','tbl_feedbacks.f_status','tbl_feedbacks.created_at','users.name','users.email','users.contact','users.id')
    ->join('users', 'tbl_feedbacks.student_id', 'users.id')
    ->where('tbl_feedbacks.class_id',$id)->where('tbl_feedbacks.flag',"Feedback by Student to Class")->where('tbl_feedbacks.f_status',1)->orderBy('tbl_feedbacks.f_id','desc')->get();
  }

  protected function getUserFeedback($id,$u_type)
  {
    if($u_type == 2){
      return DB::table('tbl_feedbacks')->select('tbl_feedbacks.*','users.name','users.email','users.contact')
        ->join('users', 'tbl_feedbacks.student_id', 'users.id')
        ->where('tbl_feedbacks.teacher_id',$id)->where('tbl_feedbacks.flag',"Feedback by Student")->orderBy('tbl_feedbacks.f_id','desc')->paginate(10);
      }
    elseif ($u_type == 3) {
      return DB::table('tbl_feedbacks')->select('tbl_feedbacks.*','users.name','users.email','users.contact')
         ->join('users', 'tbl_feedbacks.teacher_id', 'users.id')
         ->where('tbl_feedbacks.student_id',$id)->where('tbl_feedbacks.flag',"Feedback by Teacher")->orderBy('tbl_feedbacks.f_id','desc')->paginate(10);
      }
  }

  // public function getClassTeacherFeedback($id)
  // {
  //   return DB::table('tbl_feedbacks')->select('tbl_feedbacks.f_id','tbl_feedbacks.class_id','tbl_feedbacks.student_id','tbl_feedbacks.teacher_feedback','tbl_feedbacks.rating','tbl_feedbacks.flag','tbl_feedbacks.f_status','tbl_feedbacks.created_at','users.name','users.email','users.contact','users.id')
  //   ->join('users', 'tbl_feedbacks.teacher_id', 'users.id')
  //   ->where('tbl_feedbacks.class_id',$id)->where('tbl_feedbacks.flag',"Feedback by Student")->orderBy('tbl_feedbacks.f_id','desc')->get();
  // }
 

}