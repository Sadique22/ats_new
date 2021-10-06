<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ClassDetails;
use App\Models\EnrollmentDetails;
use App\Models\ChildrenManagement;
use App\Models\Promocodes;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Notification;
use Session;
use DB;

class ManageChildren extends Controller
{
  public function index() 
  {
   try{
     $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '4'){
        $childrens = ChildrenManagement::where('parent_id',$user_id)->paginate(10);
        return view('parent/manage-children',compact('childrens'));
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

  public function addNewChildren(Request $request)
  {
   try{
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '4') {
          $validatedData = $request->validate([
            'child_name' => 'required|max:50',
            'child_age' => 'required|numeric|min:6|max:20',
            'child_gender' =>'required'
            ],
            [
            'child_name.required' => 'Please Enter Children Name',
            'child_age.required' => 'Please Enter Children Age',
            'child_gender.required' => 'Please Select Gender'
            ]);
          if ($validatedData == true) {
          $childrendata = new ChildrenManagement;
          $childrendata->parent_id = $user_id;
          $childrendata->child_name = $request->child_name;
          $childrendata->child_age = $request->child_age;
          $childrendata->child_gender = $request->child_gender;
          if (!empty($childrendata)) {
            $childrendata->save();
            return redirect()->back()->with('message', 'Children Added Successfully!!');
          }else{
            return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
          }
        }else{
          return redirect()->back()->with('message', 'Data not Valid!!');
        }
      }else{
        return view('auth.login')->with('message', 'You are not authorized to access!');
      }
    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
    }
  }

  public function editChildren($id)
  {
   try{
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
        if ($UserRole == '4') {
          $user_id = base64_decode($id);
        $childrendata = ChildrenManagement::where('child_id',$user_id)->get(); 
        return view('parent/edit-children',compact('childrendata','UserRole'));
      }else{
        return view('auth.login')->with('message', 'You are not authorized to access!');
      }
    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
    }
  }

  public function updateChildren(Request $request)
  {
   try{
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '4') {
          $validatedData = $request->validate([
            'child_name' => 'required|max:50',
            'child_age' => 'required|numeric|min:6|max:20',
            'child_gender' =>'required'
            ],
            [
            'child_name.required' => 'Please Enter Children Name',
            'child_age.required' => 'Please Enter Children Age',
            'child_gender.required' => 'Please Select Gender'
            ]);
          if ($validatedData == true) {
          $id = $request->user_id; 
          $childrendata = ChildrenManagement::find($id);
          $childrendata->child_name = $request->child_name;
          $childrendata->child_age = $request->child_age;
          $childrendata->child_gender = $request->child_gender;
          if (!empty($childrendata)) {
            $childrendata->save();
            return redirect()->back()->with('message', 'Children Details Updated Successfully!!');
          }else{
            return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
          }
        }else{
          return redirect()->back()->with('message', 'Data not Valid!!');
        }
      }else{
        return view('auth.login')->with('message', 'You are not authorized to access!');
      }
    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
    }
  }

  public function deleteChildren($id) 
  {
   try{
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '4') {
     
      $childrendata = ChildrenManagement::find($id);
      $childrendata->delete();
      return redirect()->back()->with('message', 'Children Deleted Successfully!!');
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

  public function childrenEnrolledClasses($id) 
  {
   try{
     $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '4'){
        $child_id = base64_decode($id);
        $classes = (new ClassDetails)->getChildrenClass($child_id);
        $teachers = (new ClassDetails)->getStudentClassTeachers($user_id);
        $childrens = ChildrenManagement::where('parent_id',$user_id)->get();
        return view('manage-classes/view-classes',compact('classes','childrens','teachers','UserRole'));
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

  public function parentChildrenDetails($id) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);

        if ($UserRole == 1) {
          $childrens = ChildrenManagement::where('parent_id',$id)->paginate(10);
          $parent_name = User::where('id',$id)->value('name');
          if (!empty($childrens)) {
           return view('parent/children-details',compact('childrens','parent_name','UserRole'));
          }else {
           return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
          }
        }else{
          return view('auth.login')->with('message', 'You are not Authorized to access this page!');
        }
      }catch (Exception $e) {
         return response()->json([
           'message' => 'Server Error!'
              ], 500);
      }
  }

}