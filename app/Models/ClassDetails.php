<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class ClassDetails extends Model
{
    use HasFactory;
    protected $table ='class_details';
    //protected $fillable = ['class_title', 'class_desc', 'price','category'];

  public function getClasses($id)
  {
    return DB::table('users')->select('users.*', 'class_details.*','tbl_categories.*')
    ->join('class_details', 'users.id', 'class_details.created_by')
    ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
    ->where('class_details.created_by',$id)->orderBy('class_details.id','desc')->paginate(10);
  }

  public function getClassData($id)
  {
    return DB::table('users')->select('users.*', 'class_details.*','tbl_categories.*')
    ->join('class_details', 'users.id', 'class_details.created_by')
    ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
    ->where('class_details.id',$id)->get();
  }


  public function getCategory()
  {
    return DB::table('tbl_categories')->select('tbl_categories.*', 'class_details.category')->join('class_details', 'tbl_categories.id', 'class_details.category')->get();
  }

  public function getTeachers()
  {
    return DB::table('users')->select('users.*')->where('user_type',2)->where('email_verified_at', '!=', 'null')->get();
  }

  public function getTeachersDetails(){
    return DB::table('users')->select('users.email','users.name','users.occupation','users.qualification','users.user_bio','users.id','tbl_feedbacks.teacher_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where teacher_id = users.id AND rating > 0 ) as avg_rating'))
        ->leftjoin('tbl_userrole','users.id','tbl_userrole.ur_id')
        ->leftjoin('tbl_feedbacks','users.id','tbl_feedbacks.teacher_id')
        ->where('tbl_userrole.user_type',2)
        ->where('users.email_verified_at','!=','null')
        ->distinct()
        ->get();
  }


  public function getSingleTeachersDetails($id){
    return DB::table('users')->select('users.email','users.name','users.occupation','users.qualification','users.user_bio','users.profile_photo_path','users.id','tbl_feedbacks.teacher_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where teacher_id = users.id AND rating > 0 ) as avg_rating'))
        ->leftjoin('tbl_userrole','users.id','tbl_userrole.ur_id')
        ->leftjoin('tbl_feedbacks','users.id','tbl_feedbacks.teacher_id')
        ->where('tbl_userrole.user_type',2)
        ->where('users.id',$id)
        ->where('users.email_verified_at','!=','null')
        ->distinct()
        ->get();
  }

  public function getTeacherFieldsofExpertise($id){
    return DB::table('user_additional_info')
        ->select('user_additional_info.field_of_expertise')
        ->where('user_additional_info.user_id',$id)
        ->get();
  }

  public function getAllClasses()
  {
    return DB::table('users')->select('users.*', 'class_details.*','tbl_categories.*')->join('class_details', 'users.id', 'class_details.created_by')->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')->whereIn('class_details.status',[0, 1, 2])->orderBy('class_details.id','desc')->paginate(10);
  }

  public function ClassesData($id)
  {
    return DB::table('class_details')->select('users.*', 'class_details.*','tbl_categories.c_name')
    ->join('users', 'users.id', 'class_details.created_by')
    ->leftjoin('tbl_categories','class_details.category','tbl_categories.c_id')
    ->where('class_details.id',$id)->get();
  }

  public function getApprovedClasses()
  {
     return DB::table('class_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
        ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
        ->leftjoin('users','class_details.created_by','users.id')
        ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
        ->where('class_details.status',1)->orderBy('class_details.id','desc')
        ->distinct()->get();
  }

  public function getLatestClasses()
  {
     return DB::table('class_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
        ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
        ->leftjoin('users','class_details.created_by','users.id')
        ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
        ->where('class_details.status',1)->orderBy('class_details.id','desc')
        ->distinct()->limit(3)->get();
  }

  public function getApprovedClassesTeacherDash($id)
  {
    return DB::table('users')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
    ->join('class_details', 'users.id', 'class_details.created_by')
    ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
    ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
    ->where('class_details.created_by',$id)
    ->where('class_details.status',1)
    ->orderBy('class_details.id','desc')
    ->get();
  }

  public function getApprovedClassesTeacher($id)
  {
    return DB::table('users')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
    ->join('class_details', 'users.id', 'class_details.created_by')
    ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
    ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
    ->where('class_details.created_by',$id)
    ->where('class_details.status',1)
    ->orderBy('class_details.id','desc')
    ->paginate(10);
  }

  public function getDeclinedClassesTeacher($id)
  {
    return DB::table('users')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
    ->join('class_details', 'users.id', 'class_details.created_by')
    ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
    ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
    ->where('class_details.created_by',$id)
    ->where('class_details.status',2)
    ->orderBy('class_details.id','desc')
    ->paginate(10);
  }

  public function getSavedClassesTeacher($id)
  {
    return DB::table('users')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
    ->join('class_details', 'users.id', 'class_details.created_by')
    ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
    ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
    ->where('class_details.created_by',$id)
    ->where('class_details.status',3)
    ->orderBy('class_details.id','desc')
    ->paginate(10);
  }

  public function getPendingClassesTeacher($id)
  {
    return DB::table('users')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
    ->join('class_details', 'users.id', 'class_details.created_by')
    ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
    ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
    ->where('class_details.created_by',$id)
    ->where('class_details.status',0)
    ->orderBy('class_details.id','desc')
    ->paginate(10);
  }

  public function getApprovedClassesAdmin()
  {
     return DB::table('class_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
        ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
        ->leftjoin('users','class_details.created_by','users.id')
        ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
        ->where('class_details.status',1)->orderBy('class_details.id','desc')
        ->distinct()->paginate(10);
  }

  public function getDeclinedClassesAdmin()
  {
     return DB::table('class_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
        ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
        ->leftjoin('users','class_details.created_by','users.id')
        ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
        ->where('class_details.status',2)->orderBy('class_details.id','desc')
        ->distinct()->paginate(10);
  }

  public function getPendingApprovalClassesAdmin()
  {
     return DB::table('class_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
        ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
        ->leftjoin('users','class_details.created_by','users.id')
        ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
        ->where('class_details.status',0)->orderBy('class_details.id','desc')
        ->distinct()->paginate(10);
  }

  public function getFeaturedClassesAdmin()
  {
     return DB::table('class_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
        ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
        ->leftjoin('users','class_details.created_by','users.id')
        ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
        ->where('class_details.is_featured',1)->orderBy('class_details.id','desc')
        ->distinct()->paginate(10);
  }

  public function getStudentClassDash($id)
  {
    return DB::table('enrollment_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
    ->join('class_details', 'enrollment_details.class_id', 'class_details.id')
    ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
    ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
    ->join('users', 'users.id', 'class_details.created_by')
    ->where('enrollment_details.student_id',$id)
    ->where('enrollment_details.payment_status','success')
    ->where('enrollment_details.is_subscribed',1)
    ->get();
  }

  public function getFeaturedClasses()
  {
     return DB::table('class_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
        ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
        ->leftjoin('users','class_details.created_by','users.id')
        ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
        ->where('class_details.status',1)
        ->where('class_details.is_featured',1)
        ->orderBy('class_details.id','desc')
        ->distinct()->get();
  }

  public function getTeacherApprovedClasses($id)
  {
    return DB::table('users as u')
    ->leftjoin('class_details as cd', 'u.id', 'cd.created_by')
    ->where('cd.created_by',$id)->where('cd.status',1)
    ->select('u.name as user_name','u.id as user_id','cd.class_title','cd.id as class_id')
    ->orderBy('cd.id','desc')->paginate(10);
  }
  
  public function getSingleClass($id)
  {
    return DB::table('users')->select('users.*', 'class_details.*','tbl_categories.*')->join('class_details', 'users.id', 'class_details.created_by')->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')->where('class_details.id',$id)->get();
  }

  public function getStudentClass($id)
  {
    return DB::table('enrollment_details')->select('class_details.*','enrollment_details.enr_id','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
    ->join('class_details', 'enrollment_details.class_id', 'class_details.id')
    ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
    ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
    ->join('users', 'users.id', 'class_details.created_by')
    ->where('enrollment_details.student_id',$id)
    ->where('enrollment_details.payment_status','success')
    ->where('enrollment_details.is_subscribed',1)
    ->paginate(10);
  }

  public function getChildrenClass($child_id)
  {
    return DB::table('enrollment_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
    ->join('class_details', 'enrollment_details.class_id', 'class_details.id')
    ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
    ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
    ->join('users', 'users.id', 'class_details.created_by')
    ->where('enrollment_details.child_id',$child_id)
    ->where('enrollment_details.payment_status','success')
    ->where('enrollment_details.is_subscribed',1)
    ->paginate(10);
  }

  public function getStudentClassTeachers($id)
  {
    return DB::table('enrollment_details')->select('users.name','users.id')
    ->join('class_details', 'enrollment_details.class_id', 'class_details.id')
    ->join('users', 'users.id', 'class_details.created_by')
    ->where('enrollment_details.student_id',$id)
    ->where('enrollment_details.payment_status','success')
    ->where('enrollment_details.is_subscribed',1)
    ->paginate(10);
  }

  protected function getClassName($id)
  {
    return (new ClassDetails)->where('id', $id)->value('class_title');
  }

  public function getKeywords(){
    return DB::table('class_details')->select('class_details.id','class_details.keywords','class_details.class_title','users.name','class_details.created_by')
      ->leftjoin('users','class_details.created_by','users.id')
      ->where('class_details.status',1)
      ->paginate(10);
  }

  public function GetKeywordData($id){
    return DB::table('class_details')->select('class_details.id','class_details.keywords','class_details.created_by','class_details.class_title')
      ->where('class_details.id',$id)
      ->get();
  }

  public function getUpcomingClassesTeacher($id)
  {
    $today = date('Y-m-d');
    return DB::table('users')->select('users.*', 'class_details.*','tbl_categories.*')
    ->join('class_details', 'users.id', 'class_details.created_by')
    ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
    ->where('class_details.created_by',$id)
    ->where('class_details.live_date','>=',$today)
    ->orderBy('class_details.id','desc')
    ->paginate(10);
  }

  public function getStudentUpcomingClasses($id)
  {
    $today = date('Y-m-d');
    return DB::table('enrollment_details')->select('enrollment_details.*', 'class_details.*','tbl_categories.*','users.name')
    ->join('class_details', 'enrollment_details.class_id', 'class_details.id')
    ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
    ->join('users', 'users.id', 'class_details.created_by')
    ->where('enrollment_details.student_id',$id)
    ->where('enrollment_details.payment_status','success')
    ->where('enrollment_details.is_subscribed',1)
    ->where('class_details.live_date','>=',$today)
    ->paginate(10);
  }

  public function getAdminUpcomingClasses()
  {
    $today = date('Y-m-d');
    return DB::table('users')->select('users.*', 'class_details.*','tbl_categories.*')->join('class_details', 'users.id', 'class_details.created_by')->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')->whereIn('class_details.status',[0, 1, 2])
      ->where('class_details.live_date','>=',$today)
      ->orderBy('class_details.id','desc')
      ->paginate(10);
  }

  public function getCompletedClassesTeacher($id)
  {
    $today = date('Y-m-d');
    return DB::table('users')->select('users.*', 'class_details.*','tbl_categories.*')
    ->join('class_details', 'users.id', 'class_details.created_by')
    ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
    ->where('class_details.created_by',$id)
    ->where('class_details.class_end_date','<',$today)
    ->where('class_details.status',1)
    ->orderBy('class_details.id','desc')
    ->paginate(10);
  }

  public function getCompletedClassesStudent($id)
  {
    $today = date('Y-m-d');
    return DB::table('enrollment_details')->select('enrollment_details.*', 'class_details.*','tbl_categories.*','users.name')
    ->join('class_details', 'enrollment_details.class_id', 'class_details.id')
    ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
    ->join('users', 'users.id', 'class_details.created_by')
    ->where('enrollment_details.student_id',$id)
    ->where('enrollment_details.payment_status','success')
    ->where('enrollment_details.is_subscribed',1)
    ->where('class_details.class_end_date','<',$today)
    ->paginate(10);
  }

  public function getCompletedClassesAdmin()
  {
    $today = date('Y-m-d');
    return DB::table('users')->select('users.*', 'class_details.*','tbl_categories.*')->join('class_details', 'users.id', 'class_details.created_by')->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')->where('class_details.status', 1)
      ->where('class_details.class_end_date','<',$today)
      ->orderBy('class_details.id','desc')
      ->paginate(10);
  }   
  
}
