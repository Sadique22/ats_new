<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use DB;

class StudyMaterials extends Model
{
    use HasFactory;
    protected $table ='tbl_study_materials';
    protected  $primaryKey = 'sm_id';

  public function getTeacherStudyMaterials($id)
  {
    return DB::table('tbl_study_materials')->select('tbl_study_materials.*', 'class_details.class_title','users.name')
    ->join('class_details', 'tbl_study_materials.class_id', 'class_details.id')
    ->join('users', 'tbl_study_materials.teacher_id', 'users.id')
    ->where('tbl_study_materials.teacher_id',$id)
    ->orderBy('tbl_study_materials.sm_id','desc')
    ->paginate(10);
  }

  public function getStudentStudyMaterials($id)
  {
     return DB::table('enrollment_details')->select('class_details.class_title','users.name','tbl_study_materials.*')
    ->join('class_details', 'enrollment_details.class_id', 'class_details.id')
    ->join('users', 'users.id', 'class_details.created_by')
    ->join('tbl_study_materials','tbl_study_materials.class_id','enrollment_details.class_id')
    ->where('enrollment_details.student_id',$id)
    ->where('enrollment_details.payment_status','success')
    ->where('tbl_study_materials.sm_status',1)
    ->paginate(10);
  }

  public function getAllStudyMaterials()
  {
    return DB::table('tbl_study_materials')->select('tbl_study_materials.*', 'class_details.class_title','users.name')
    ->join('class_details', 'tbl_study_materials.class_id', 'class_details.id')
    ->join('users', 'tbl_study_materials.teacher_id', 'users.id')
    ->orderBy('tbl_study_materials.sm_id','desc')
    ->paginate(10);
  }
  
} 
    