<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class EnrollmentDetails extends Model
{
    use HasFactory;
    protected $table ='enrollment_details';
    protected  $primaryKey = 'enr_id';

  public function getEnrolledStudents($id)
  {
    return DB::table('enrollment_details')->select('enrollment_details.*', 'class_details.id','class_details.class_title','class_details.class_desc','class_details.price_usd','class_details.price_inr','class_details.image_path','class_details.video_path','users.name','users.email','users.contact')
    ->join('class_details', 'enrollment_details.class_id', 'class_details.id')
    ->join('users', 'users.id', 'enrollment_details.student_id')
    ->where('enrollment_details.teacher_id',$id)
    ->where('enrollment_details.payment_status','success')
    ->where('enrollment_details.is_subscribed',1)
    ->orderBy('enrollment_details.enr_id','desc')
    ->paginate(10);
  }

  public function getClassEnrolledStudents($id)
  {
    return DB::table('enrollment_details')->select('enrollment_details.*', 'class_details.id','class_details.class_title','users.name','users.email','users.contact')
    ->join('class_details', 'enrollment_details.class_id', 'class_details.id')
    ->join('users', 'users.id', 'enrollment_details.student_id')
    ->where('enrollment_details.class_id',$id)
    ->where('enrollment_details.payment_status','success')
    ->where('enrollment_details.is_subscribed',1)
    ->orderBy('enrollment_details.enr_id','desc')
    ->get();
  }

  public function EnrolledStudentsNotification($id)
  {
    return DB::table('enrollment_details')->select('users.*')
    ->join('class_details', 'enrollment_details.class_id', 'class_details.id')
    ->join('users', 'users.id', 'enrollment_details.student_id')
    ->where('enrollment_details.class_id',$id)
    ->where('enrollment_details.payment_status','success')
    ->where('enrollment_details.is_subscribed',1)
    ->orderBy('enrollment_details.enr_id','desc')
    ->get();
  }

  public function getUnsubscribedClassesDetails()
  {
    return DB::table('enrollment_details')->select('enrollment_details.*', 'class_details.id','class_details.class_title','users.name','users.email','users.contact')
    ->join('class_details', 'enrollment_details.class_id', 'class_details.id')
    ->join('users', 'users.id', 'enrollment_details.student_id')
    ->where('enrollment_details.payment_status','success')
    ->where('enrollment_details.is_subscribed',0)
    ->orderBy('enrollment_details.enr_id','desc')
    ->paginate(10);
  }

  public function getUnsubscribedClassesStudent($id)
  {
    return DB::table('enrollment_details')->select('enrollment_details.*', 'class_details.id','class_details.class_title','class_details.class_desc','class_details.price_usd','class_details.price_inr','class_details.image_path','class_details.video_path','users.name','users.email','users.contact')
    ->join('class_details', 'enrollment_details.class_id', 'class_details.id')
    ->join('users', 'users.id', 'enrollment_details.teacher_id')
    ->where('enrollment_details.student_id',$id)
    ->where('enrollment_details.payment_status','success')
    ->where('enrollment_details.is_subscribed',0)
    ->orderBy('enrollment_details.enr_id','desc')
    ->paginate(10);
  }


}