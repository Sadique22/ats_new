<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ClassDetails;
use App\Models\Categories;
use App\Models\KeywordDetails;
use App\Models\User;
use App\Models\UserRole;
use Session;
use DB;

class SearchFilterAPI extends Controller
{
  public function search($data) 
  {
   try{
   	if (!empty($data) && isset($data)) {
   		$classes = DB::table('class_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
          ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
          ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
          ->leftjoin('users','class_details.created_by','users.id')
          ->where('class_details.status',1)
          ->where('class_details.class_title', 'LIKE', '%' . $data . '%')
          ->distinct()
          ->get();
        if (!empty($classes) && isset($classes)) 
        {
          	 return response()->json([
		        'message' => 'Search Results for: '.$data,
		        'data' => $classes
		       ], 201); 
        }else{
          	return json_encode(array("status" => 404, "message" => 'Sorry, No Data Found!'));
          }
   	}else{
   	        return json_encode(array("status" => 300, "message" => 'Please Input Something to Search!'));	
   	}
   }catch (Exception $e){
      return response()->json([
        'message' => 'Server Error!'
      ], 500);
    }  
  }

  public function categorySearch($id) 
  {
   try{
   	if (!empty($id) && Categories::where('c_id', $id)->exists()) {
   		$cat_name = (new Categories)->where('c_id', $id)->value('c_name');
   		$classes = DB::table('class_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
		    ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
		    ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
		    ->leftjoin('users','class_details.created_by','users.id')
		    ->where('class_details.category',$id)
		    ->where('class_details.status',1)
		    ->distinct()
		    ->get();
        if (!empty($classes) && isset($classes)) 
        {
          	 return response()->json([
		        'message' => 'Search Results for- '.$cat_name,
		        'data' => $classes
		       ], 201); 
        }else{
          	return json_encode(array("status" => 404, "message" => 'Sorry, No Data Found!'));
          }
   	}else{
   	        return json_encode(array("status" => 300, "message" => 'Wrong Category ID!'));	
   	}
   }catch (Exception $e){
      return response()->json([
        'message' => 'Server Error!'
      ], 500);
    }  
  }

  public function advanceSearch(Request $request) 
  {
   try{
       $inputs = $request->all();
        if (!empty($inputs)){
	   	    $category     = null;
		    $maxPrice     = null;
		    $minPrice     = null;
		    $rating       = null;
		    $teacherName  = null;
		    $keyword      = null;
		    if(isset($inputs['category_id']) && Categories::where('c_id', $inputs['category_id'])->exists()) {
		        $category = $inputs['category_id'];
		    }
		    if(isset($inputs['minPrice']) && isset($inputs['maxPrice'])) {
		        $maxPrice = $inputs['maxPrice'];
		        $minPrice = $inputs['minPrice'];
		    }
		    if(isset($inputs['rating'])) {
		        $rating = $inputs['rating'];
		    }
		    if(isset($inputs['teacherId'])) {
		        $teacherName = $inputs['teacherId'];
		    }
		    if(isset($inputs['keyword'])) {
		        $keyword = $inputs['keyword'];
		    }

		    $classes = DB::table('class_details')->select('class_details.*','tbl_categories.*','users.email','users.name','tbl_feedbacks.class_id','tbl_feedbacks.rating',DB::raw('(select cast(avg(rating)AS DECIMAL (12,1))from tbl_feedbacks where class_id = class_details.id AND rating > 0 ) as avg_rating'))
	        ->join('tbl_categories', 'tbl_categories.c_id', 'class_details.category')
	        ->leftjoin('users','class_details.created_by','users.id')
	        ->leftjoin('tbl_feedbacks','class_details.id','tbl_feedbacks.class_id')
	        ->where('class_details.status',1)
	        ->When($category,function($query) use ($category) {
	            $query->where('class_details.category', $category);
	          })
	        ->When($maxPrice,function($query)use ($minPrice,$maxPrice) {
	            $query->whereBetween('class_details.price',[$minPrice,$maxPrice]);
	          })
	        ->When($rating,function($query)use ($rating) {
	            $query->where(DB::raw('(select cast(avg(rating)AS DECIMAL (12,0))from tbl_feedbacks where class_id = class_details.id AND rating > 0 )'),$rating);
	            })
	        ->When($teacherName,function($query)use ($teacherName) {
	            $query->where('class_details.created_by',$teacherName);
	          })
	        ->When($keyword,function($query)use ($keyword) {
	            $query->where('class_details.keywords','LIKE', '%' . $keyword . '%');
	          })
	        ->distinct()
	        ->get();
		    if (!empty($classes) && isset($classes)) 
	        {
	          	return response()->json([
			        'message' => 'Search Results',
			        'data' => $classes
			       ], 201); 
	        }else{
	          	return json_encode(array("status" => 404, "message" => 'Sorry, No Data Found!'));
	          }
	    }else{
			return json_encode(array("status" => 404, "message" => 'Please provide Input to Search!'));
		}
     }catch (Exception $e){
       return response()->json([
         'message' => 'Server Error!'
        ], 500);
    }  
  }

}