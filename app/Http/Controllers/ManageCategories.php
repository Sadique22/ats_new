<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ClassDetails;
use App\Models\Categories;
use App\Models\UserRole;
use Session;
use DB;

class ManageCategories extends Controller
{
  public function index() 
  {
    try{
        $user_id = Auth::id();
        $UserRole = UserRole::GetUserRole($user_id);
       if($UserRole == '1')
        {
          $categories = Categories::orderBy('c_id','desc')->paginate(10);
          return view('categories/view-categories',compact('categories'));
        }
         else {
          return view('auth.login')->with('message', 'Please Login First');
        }
    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function addCategoryView() 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1'){
       return view('categories/add-category');
       }else{
        return view('auth.login')->with('message', 'You are not authorized to access!');
       }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function deleteCategory($c_id) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1'){
        $categorydetails = (new Categories)->deleteCategory($c_id);
        //$categorydetails->delete();
        return redirect()->back()->with('message', 'Category Deleted Successfully!!');
      } 
      else {
        return view('auth.login')->with('message', 'You are not authorized to access!');
       }
     }catch (Exception $e) {
         return response()->json([
           'message' => 'Server Error!'
              ], 500);
      }
  }

  public function insertData(Request $req) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1'){
        $validatedData = $req->validate([
          'c_name' => 'required',
          'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
       
        if ($validatedData == true) {
          $categorydetails = new Categories;
          $imageName = time().'.'.$req->image->extension();  
          $req->image->move(public_path('assets/img/classes/'), $imageName);

          $categorydetails->category_image = 'assets/img/classes/' . $imageName;

          $categorydetails->c_name = $req->c_name;
          $categorydetails->bg_color = $req->bg_color; 
          
          if (!empty($categorydetails)) {
          $categorydetails->save();
          return redirect()->back()->with('message', 'Category Created Successfully!!');
          }
          else{
          return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
          }
        }
       }
       else {
          return view('auth.login')->with('message', 'You are not authorized to access!');
        }
      }catch (Exception $e) {
          return response()->json([
            'message' => 'Server Error!'
               ], 500);
      }
  }

   public function editCategory($id) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1'){
      $category = (new Categories)->CategoryData($id);
      return view('categories/edit-category',compact('category'));
      }
      else {
        return view('auth.login')->with('message', 'You are not authorized to access!');
      } 
    }catch (Exception $e) {
          return response()->json([
            'message' => 'Server Error!'
               ], 500);
      }
  }
  

  public function updateCategory(Request $req, $id) 
  {
    try{
        $user_id = Auth::id();
        $UserRole = UserRole::GetUserRole($user_id);
        if ($UserRole == '1'){
        $categorydetails = Categories::find($id);     
        $categorydetails->c_name = $req->c_name;
        if(!empty($req->image)){
          $imageName = time().'.'.$req->image->extension();  
          $req->image->move(public_path('assets/img/classes/'), $imageName);
          $categorydetails->category_image = 'assets/img/classes/' . $imageName;
        }
       
        $categorydetails->bg_color = $req->bg_color;
        $categorydetails->c_status = isset($req->cat_status) ?  $req->cat_status   : 0;
       
        if (!empty($categorydetails)) {
        $categorydetails->save();
        return redirect()->back()->with('message', 'Category Details Updated Successfully!!');
        }
        else{
        return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
        }
        return view('manage-classes/edit-class',compact('classes'));
        }
        else {
          return view('auth.login')->with('message', 'You are not authorized to access!');
        } 
    }catch (Exception $e) {
          return response()->json([
            'message' => 'Server Error!'
               ], 500);
      }
  }

  public function categoryStatus($status,$id)
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '1') {
          if ($status == 1) {
            $category_status = Categories::find($id);
            $category_status->c_status = 0;
            $category_status->save();
            return redirect()->back()->with('fault', 'Category Deactivated!!');
           }else{
            $category_status = Categories::find($id);
            $category_status->c_status = 1;
            $category_status->save();
            return redirect()->back()->with('message', 'Category Activated Successfully!!');
           }
          }else {
            return view('auth.login')->with('message', 'You are not authorized to access!');
          }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }


}