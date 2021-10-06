<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassDetails;
use App\Models\Categories;
use App\Models\User;
use App\Models\UserRole;
use Session;
use DB;

class Search extends Controller
{
  public function index(Request $request) 
  {
    $featured_class = (new ClassDetails)->getFeaturedClasses();
    $teachers = (new ClassDetails)->getTeachers();
    $categories = (new Categories)->getCategories();
    //$clientIP = request()->ip();
    $clientIP = '72.229.28.185';
    $access_location = \Location::get($clientIP); 
    $searchtext = preg_replace('/[^A-Za-z0-9\-]/', '', $request->data);
    $classes = DB::table('class_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
          ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
          ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
          ->leftjoin('users','class_details.created_by','users.id')
          ->where('class_details.status',1)
          ->where('class_details.class_title', 'LIKE', '%' . $searchtext . '%')
          ->orWhere('class_details.keywords', 'LIKE', '%' . $searchtext . '%')
          ->distinct()
          ->get();
    
    return view('search-pages/search',compact('classes','access_location','featured_class','categories','searchtext','teachers'));
  }

  public function advanceSearh(Request $request) 
  {

    $featured_class = (new ClassDetails)->getFeaturedClasses();
    $categories = (new Categories)->getCategories();
    $teachers = (new ClassDetails)->getTeachers();
    //$clientIP = request()->ip();
    $clientIP = '72.229.28.185';
    $access_location = \Location::get($clientIP); 
    $category     = null;
    $maxPrice     = null;
    $minPrice     = null;
    $rating       = null;
    $cat_name     = null;
    $teacherName  = null;
    $teacher_name = null;
    $keyword      = null;
    $data = $request;

      if (isset($request->category_name)) {
      $cat_name = (new Categories)->where('c_id', $request->category_name)->value('c_name');
      }
      if (isset($request->teacherName)) {
      $teacher_name = (new User)->where('id', $request->teacherName)->value('name');
      }
      if(isset($request->category_name)) {
        $category = $request->category_name;
      }
      if(isset($request->minPrice) && isset($request->maxPrice)) {
        $maxPrice = $request->maxPrice;
        $minPrice = $request->minPrice;
      }
      if(isset($request->rating)) {
        $rating = $request->rating;
      }
      if(isset($request->teacherName)) {
        $teacherName = $request->teacherName;
      }
      if(isset($request->keyword)) {
        $keyword = $request->keyword;
      }
      
      if($access_location->countryName == "India" && $access_location->countryCode == "IN"){
        $classes = DB::table('class_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
          ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
          ->leftjoin('users','class_details.created_by','users.id')
          ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
          ->where('class_details.status',1)
          ->When($category,function($query) use ($category) {
              $query->where('class_details.category', $category);
            })
          ->When($maxPrice,function($query)use ($minPrice,$maxPrice) {
              $query->whereBetween('class_details.price_inr',[$minPrice,$maxPrice]);
            })
          ->When($rating,function($query)use ($rating) {
              $query->where(DB::raw('(select cast(avg(rating)AS DECIMAL (12,0))from tbl_feedbacks where class_id = class_details.id AND rating > 0 )'),$rating);
              })
          ->When($teacherName,function($query)use ($teacherName) {
              $query->where('class_details.created_by',$teacherName);
            })
          ->When($keyword,function($query)use ($keyword) {
              $query->where('class_details.keywords','LIKE', '%' . $keyword . '%')->orWhere('class_details.class_title', 'LIKE', '%' . $keyword . '%')->where('class_details.status',1);
            })
          ->distinct()
          ->get();
        }else{
          $classes = DB::table('class_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
          ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
          ->leftjoin('users','class_details.created_by','users.id')
          ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
          ->where('class_details.status',1)
          ->When($category,function($query) use ($category) {
              $query->where('class_details.category', $category);
            })
          ->When($maxPrice,function($query)use ($minPrice,$maxPrice) {
              $query->whereBetween('class_details.price_usd',[$minPrice,$maxPrice]);
            })
          ->When($rating,function($query)use ($rating) {
              $query->where(DB::raw('(select cast(avg(rating)AS DECIMAL (12,0))from tbl_feedbacks where class_id = class_details.id AND rating > 0 )'),$rating);
              })
          ->When($teacherName,function($query)use ($teacherName) {
              $query->where('class_details.created_by',$teacherName);
            })
          ->When($keyword,function($query)use ($keyword) {
              $query->where('class_details.keywords','LIKE', '%' . $keyword . '%')->orWhere('class_details.class_title', 'LIKE', '%' . $keyword . '%')->where('class_details.status',1);
            })
          ->distinct()
          ->get();
        }

    return view('search-pages/search',compact('classes','access_location','featured_class','categories','data','cat_name','teacher_name','teachers'));
  }

  public function categorySearch($id) 
  {
   $featured_class = (new ClassDetails)->getFeaturedClasses();
   $cat_id = base64_decode($id);
   $cat_name = (new Categories)->where('c_id', $cat_id)->value('c_name');
   $categories = (new Categories)->getCategories();
   $teachers = (new ClassDetails)->getTeachers();
   //$clientIP = request()->ip();
   $clientIP = '72.229.28.185';
   $access_location = \Location::get($clientIP); 

   $classes = DB::table('class_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
      ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
      ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
      ->leftjoin('users','class_details.created_by','users.id')
      ->where('class_details.category',$cat_id)
      ->where('class_details.status',1)
      ->distinct()
      ->get();
    return view('search-pages/category-search',compact('classes','access_location','featured_class','categories','teachers','cat_name'));           
  }

  public function dashboardSearch() 
  {
    $teachers = (new ClassDetails)->getTeachers();
    $categories = (new Categories)->getCategories();
    $searchtext = preg_replace('/[^A-Za-z0-9\-]/', '', $_GET['data']);
    $classes = DB::table('class_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
          ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
          ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
          ->leftjoin('users','class_details.created_by','users.id')
          ->where('class_details.status',1)
          ->where('class_details.class_title', 'LIKE', '%' . $searchtext . '%')
          ->distinct()
          ->get();
    return view('search-pages/dashboard-search',compact('classes','categories','searchtext','teachers'));
  }

  public function adminSearch(Request $request) 
  {
    $occupation = null;
    $qualification = null;
    $teacherName = null;
    $rating       = null;
    $data = $request;

      if(isset($request->user_name)) {
        $teacherName = $request->user_name;
      }
      if(isset($request->user_qualification)) {
        $qualification = $request->user_qualification;
      }
      if(isset($request->user_occupation)) {
        $occupation = $request->user_occupation;
      }
      if(isset($request->rating)) {
        $rating = $request->rating;
      }

      $teacher = DB::table('users')->select('users.email','users.name','users.occupation','users.qualification','tbl_feedbacks.teacher_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where teacher_id = users.id AND rating > 0 ) as avg_rating'))
        ->leftjoin('tbl_userrole','users.id','tbl_userrole.ur_id')
        ->leftjoin('tbl_feedbacks','users.id','tbl_feedbacks.teacher_id')
        ->where('tbl_userrole.user_type',2)
        ->where('users.email_verified_at','!=','null')
        ->When($rating,function($query)use ($rating) {
            $query->where(DB::raw('(select cast(avg(rating)AS DECIMAL (12,0))from tbl_feedbacks where teacher_id = users.id AND rating > 0 )'),$rating);
            })
        ->When($teacherName,function($query)use ($teacherName) {
            $query->where('users.name','LIKE', '%' . $teacherName . '%')->orWhere('users.email','LIKE', '%' . $teacherName . '%');
          })
        ->When($qualification,function($query)use ($qualification) {
            $query->where('users.qualification','LIKE', '%' . $qualification . '%');
          })
        ->When($occupation,function($query)use ($occupation) {
            $query->where('users.occupation','LIKE', '%' . $occupation . '%');
          })
        ->distinct()
        ->get();

    return view('search-pages/admin-search',compact('teacher','data'));
  }

  public function expertiseSearch() 
  {
    $data = $_GET['expertise'];
    $teacher = DB::table('user_additional_info')->select('user_additional_info.field_of_expertise','user_additional_info.uai_flag','users.email','users.name','users.occupation','users.qualification','tbl_feedbacks.teacher_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where teacher_id = user_additional_info.user_id AND rating > 0 ) as avg_rating'))
          ->leftjoin('tbl_feedbacks','user_additional_info.user_id','tbl_feedbacks.teacher_id')
          ->leftjoin('users','user_additional_info.user_id','users.id')
          ->where('user_additional_info.uai_flag','Teacher')
          ->where('user_additional_info.field_of_expertise', 'LIKE', '%' . $data . '%')
          ->distinct()
          ->get();

      return view('search-pages/admin-search',compact('teacher','data'));
  }
  
}
