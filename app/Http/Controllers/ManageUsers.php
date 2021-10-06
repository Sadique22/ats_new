<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ClassDetails;
use App\Models\Categories;
use App\Models\UserRole;
use App\Models\SwitchRole;
use App\Models\FeedbackDetails;
use App\Models\UserAdditionalinfo;
use App\Models\ManageNotifications;
use App\Models\User;
use App\Notifications\AccountNotification;
use App\Notifications\RequestStatus;
use Stevebauman\Location\Facades\Location;
use Notification;
use Session;
use DB;

class ManageUsers extends Controller
{
  public function index() 
  {
   try{
	   $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1'){
	      //$users = User::get();
	      $users =  User::getUsers();
        $verifiedUsers = User::verifiedUsers();
	      return view('users/all-users',compact('users','verifiedUsers'));
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

  public function deleteUser($id) 
  {
  	try{
	     $user_id = Auth::id();
         $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '1'){
	     
	      $user = User::find($id);
        $userData = User::get()->where('id',$id);
        $this->deleteUserNotification($userData);
	      $user->delete();

	    return redirect()->back()->with('message', 'User Has been Deleted Successfully!!');
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

  public function editUser($id) 
  {
  	try{
  	   $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1'){
	    $data = User::UserData($id);
	    return view('users/edit-user',compact('data'));
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

  public function updateUser(Request $req, $id) 
  {
  	try{
  	   $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1'){

    	$validatedData = $req->validate([
        'name' => 'required',
        'email' => 'required',
        'contact' => 'required',
         ]);

	    if ($validatedData == true) {
	           
	      $user = User::find($id);
	      $user->name = $req->name;
	      $user->email = $req->email;
	      $user->contact = $req->contact;
	     
	      if (!empty($user)) {
	      $user->save();
	      return redirect()->back()->with('message', 'User Details Updated Successfully!!');
	      }
	      else{
	      return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
	        }
	      return view('Users/edit-user',compact('user'));
	      }
		  else{
		  return redirect()->back()->with('message', 'Please Insert Proper Data!!');
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

  public function viewUserData($id) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1'){
      $userdata = User::where('id',$id)->get();
      $user_role = UserRole::where('ur_id',$id)->value('user_type');
      $additional_data = User::getAdditionalData($id);
      return view('users/view-user-data',compact('userdata','id','additional_data','user_role'));
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

  public function addAditinalInfo()
  {
   try{
        $user_id = Auth::id();
        $UserRole = UserRole::GetUserRole($user_id);
        if ($UserRole == '2' || $UserRole == '3'){
        return view('users/additional-info',compact('UserRole','user_id'));
        }else{
          return view('auth.login')->with('message', 'You are not authorized to access!');
        }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function postAdditionalInfo(Request $request)
  {
   try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
        if ($UserRole == '2'){
            $id = $user_id;
          
            $userInfo = User::find($id);
            $userInfo->qualification = isset($request->qualification) ? $request->qualification : NULL;
            $userInfo->occupation = isset($request->occupation) ? $request->occupation : NULL;
            $userInfo->gender = isset($request->gender) ? $request->gender : NULL;
         
          if (!empty($userInfo)) {
            $userInfo->save();
              if ($request->expertise == null) {
                return redirect()->back()->with('message', 'Please Enter Proper Schedule Details');
              }else{
                $expertise = $request->expertise;
                $user_id = Auth::id();
                $this->postExpertise($user_id,$expertise);
              }

              $userData = User::get()->where('id',$user_id);
              $this->additionalInfoNotification($userData);

              return redirect()->back()->with('message', 'Your Information has been Updated Successfully!!');
            }
        }
        elseif($UserRole == '3'){
            $id = $user_id;
         
            $userInfo = User::find($id);
            $userInfo->qualification = isset($request->qualification) ? $request->qualification : NULL;
            $userInfo->occupation = isset($request->occupation) ? $request->occupation : NULL;
            $userInfo->gender = isset($request->gender) ? $request->gender : NULL;
       
          if (!empty($userInfo)) {
            $userInfo->save();
              if ($request->interest == null) {
                return redirect()->back()->with('message', 'Please Enter Field of interest');
              }else{
                $interest = $request->interest;
                $user_id = Auth::id();
                $this->postInterest($user_id,$interest);
              }
              
              $userData = User::get()->where('id',$user_id);
              $this->additionalInfoNotification($userData);

              return redirect()->back()->with('message', 'Your Information has been Added Successfully!!');
            }
        }elseif($UserRole == '4'){
          $validatedData = $request->validate([
            'qualification' => 'required',
            'occupation' => 'required',
            'gender'  => 'required'
            ],
            [
            'qualification.required' => 'Please Enter Qualification',
            'occupation.required' => 'Please Enter Occupation',
            'gender.required' => 'Please Select Your Gender'
             ]);
            $id = $user_id;
          if ($validatedData == true) {
            $userInfo = User::find($id);
            $userInfo->qualification = $request->qualification;
            $userInfo->occupation = $request->occupation;
            $userInfo->gender = $request->gender;
          }else{
            return redirect()->back()->with('message', 'Something Went Wrong!');
          }
          if (!empty($userInfo)) {
            $userInfo->save();

              $userData = User::get()->where('id',$user_id);
              $this->additionalInfoNotification($userData);

            return redirect()->back()->with('message', 'Your Information has been Added Successfully!!');
            }
        }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function postExpertise($user_id,$expertise)
  {
    $Expertise = new UserAdditionalinfo;
    $cnt = count($expertise);
    $i=0;
    for ($i=0; $i < $cnt; $i++) { 
     DB::table('user_additional_info')->insert(
                 array(
               'user_id'            =>  $user_id,
               'field_of_expertise' =>  $expertise[$i],
               'uai_flag'           =>  'Teacher' 
             )
         );
      }
  }

  public function postInterest($user_id,$interest)
  {
    $Interest = new UserAdditionalinfo;
    $cnt = count($interest);
    $i=0;
    for ($i=0; $i < $cnt; $i++) { 
     DB::table('user_additional_info')->insert(
                 array(
               'user_id'           =>  $user_id,
               'field_of_interest' =>  $interest[$i],
               'uai_flag'          =>  'Student' 
             )
         );
      }
  }

  public function editadditionalinfo() 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '2')
       {
        $data = User::UserAdditionalinfo($user_id);
        $expertise = User::UserFieldofExpertise($user_id);
        return view('users/edit-additionalinfo',compact('data','expertise','UserRole'));
       }
       elseif($UserRole == '3')
       {
        $data = User::UserAdditionalinfo($user_id);
        $interest = User::UserFieldofInterest($user_id);
        return view('users/edit-additionalinfo',compact('data','interest','UserRole'));
       }
       elseif($UserRole == '4')
       {
        $data = User::UserAdditionalinfo($user_id);
        return view('users/edit-additionalinfo',compact('data','UserRole'));
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

  public function updateAdditionalInfo(Request $request)
  {
   try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
        if ($UserRole == '2'){
        $validatedData = $request->validate([
            'qualification' => 'required',
            'occupation' => 'required',
            'gender'  => 'required'
            ],
            [
            'qualification.required' => 'Please Enter Qualification',
            'occupation.required' => 'Please Enter Occupation',
            'gender.required' => 'Please Select Your Gender'
             ]);
            $id = $user_id;
          if ($validatedData == true) {
            $userInfo = User::find($id);
            $userInfo->qualification = $request->qualification;
            $userInfo->occupation = $request->occupation;
            $userInfo->gender = $request->gender;
          }else{
            return redirect()->back()->with('message', 'Something Went Wrong!');
          }
          if (!empty($userInfo)) {
            $userInfo->save();
              $userData = User::get()->where('id',$user_id);
              $this->additionalInfoNotification($userData);
            return redirect()->back()->with('message', 'Your Information has been Updated Successfully!!');
            }
        }
        elseif($UserRole == '3' || $UserRole == '4'){
          $validatedData = $request->validate([
            'qualification' => 'required',
            'occupation' => 'required',
            'gender'  => 'required'
            ],
            [
            'qualification.required' => 'Please Enter Qualification',
            'occupation.required' => 'Please Enter Occupation',
            'gender.required' => 'Please Select Your Gender'
             ]);
            $id = $user_id;
          if ($validatedData == true) {
            $userInfo = User::find($id);
            $userInfo->qualification = $request->qualification;
            $userInfo->occupation = $request->occupation;
            $userInfo->gender = $request->gender;
          }else{
            return redirect()->back()->with('message', 'Something Went Wrong!');
          }
          if (!empty($userInfo)) {
            $userInfo->save();

            $userData = User::get()->where('id',$user_id);
            $this->additionalInfoNotification($userData);

            return redirect()->back()->with('message', 'Your Information has been Updated Successfully!!');
            }
        }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function addMoreFieldsUser(Request $request)
  {
   try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '2') 
       {
        $Expertise = new UserAdditionalinfo;
        $fields = $request->expertise;
        $cnt = count($fields);
        $i=0;
        for ($i=0; $i < $cnt; $i++) { 
          DB::table('user_additional_info')->insert(
                array(
               'user_id'            =>  $user_id,
               'field_of_expertise' =>  $fields[$i],
               'uai_flag'           =>  'Teacher' 
             )
           );
          }
           return redirect()->back()->with('message', 'Fields Added Successfully!!');
        }
        elseif ($UserRole == '3')
        {
          $Interest = new UserAdditionalinfo;
          $fields = $request->interest;
          $cnt = count($fields);
          $i=0;
          for ($i=0; $i < $cnt; $i++) { 
            DB::table('user_additional_info')->insert(
                array(
               'user_id'            =>  $user_id,
               'field_of_interest' =>  $fields[$i],
               'uai_flag'           =>  'Student' 
              )
            );
          }
           return redirect()->back()->with('message', 'Fields Added Successfully!!');
        }else{
           return view('auth.login')->with('message', 'You are not authorized to access!');
        }
    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
        }
  }

  public function deleteUserField($uai_id) 
  {
    try{
       $user_id = Auth::id();
         $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '2' || $UserRole == '3'){
          $id = $uai_id;
          $field = UserAdditionalinfo::find($id);
          $field->delete();
        return redirect()->back()->with('fault', 'Field Deleted Successfully!!');
        }else {
        return view('auth.login')->with('message', 'You are not authorized to access!');
         }
       }catch (Exception $e) {
          return response()->json([
            'message' => 'Server Error!'
              ], 500);
      }
  }

  public function addTeacherBio(Request $request) 
  {
    try{
 
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '2'){

      $validatedData = $request->validate([
        'user_bio' => 'required | max:240',
         ],[
        'user_bio.required' => 'Please Enter Your Bio!'
         ]);
    
      if ($validatedData == true) {
        $id = $user_id;
        $update_bio = User::find($id);
        $update_bio->user_bio = $request->user_bio;
       
        if (!empty($update_bio)) {
        $update_bio->save();
          return redirect()->back()->with('message', 'Bio has been Updated Successfully!!');
        }else{
          return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
          }
        }else{
        return redirect()->back()->with('message', 'Please Insert Proper Data!!');
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

//Switching User Role: Student||Teacher
public function switchUserRole(Request $request) 
{
  try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
      
        $id = $user_id;

        if ($UserRole == '2') {
          $user_r = User::find($id);
          $user_r->user_type = 3;
          $user_r->save();

          $data =  DB::table('tbl_userrole')->where('ur_id', $id)->update(array('user_type' => '3')); 
        }else{
          $user_r = User::find($id);
          $user_r->user_type = 2;
          $user_r->save();

          $data =  DB::table('tbl_userrole')->where('ur_id', $id)->update(array('user_type' => '2')); 
        }
        return redirect()->back()->with('message', 'Role Switched Successfully!!');

      }catch (Exception $e) {
      return response()->json([
        'message' => 'Server Error!'
      ], 500);
    }
}
//Switching User Role: Parent||Teacher
public function switchUserRoleParent(Request $request) 
{
  try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
      
        $id = $user_id;

        if ($UserRole == '2') {
          $user_r = User::find($id);
          $user_r->user_type = 4;
          $user_r->save();

          $data =  DB::table('tbl_userrole')->where('ur_id', $id)->update(array('user_type' => '4')); 
        }else{
          $user_r = User::find($id);
          $user_r->user_type = 2;
          $user_r->save();

          $data =  DB::table('tbl_userrole')->where('ur_id', $id)->update(array('user_type' => '2')); 
        }
        return redirect()->back()->with('message', 'Role Switched Successfully!!');

      }catch (Exception $e) {
      return response()->json([
        'message' => 'Server Error!'
      ], 500);
    }
} 

public function switchRoleRequest(Request $request) 
{
  try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
      
        if ($UserRole == '3' || $UserRole == '4') {
          $user_role = new SwitchRole;
          $user_role->user_id = $user_id;
          $user_role->tsr_status = 0;
          $user_role->tsr_user_type = $UserRole;
          if (!empty($user_role)) {
           $user_role->save();
           return redirect()->back()->with('message', 'Your Request has been sent Successfully!!');
          }else{
            return redirect()->back()->with('message', 'Something went wrong!!');
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

//Switching User Role: Teacher--Student
public function switchUserRoleTeacher(Request $request) 
{
  try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
        if(!SwitchRole::where('user_id',$user_id)->exists()){
          $user_role = new SwitchRole;
          $user_role->user_id = $user_id;
          $user_role->tsr_status = 1;
          $user_role->tsr_user_type = $UserRole;
          $user_role->save();
        }
        $id = $user_id;
        if ($UserRole == '2') {
          $user_r = User::find($id);
          $user_r->user_type = 3;
          $user_r->save();
          $data =  DB::table('tbl_userrole')->where('ur_id', $id)->update(array('user_type' => '3')); 
        }else{
          $user_r = User::find($id);
          $user_r->user_type = 2;
          $user_r->save();
          $data =  DB::table('tbl_userrole')->where('ur_id', $id)->update(array('user_type' => '2')); 
        }
        return redirect()->back()->with('message', 'Role Switched Successfully!!');
      }catch (Exception $e) {
      return response()->json([
        'message' => 'Server Error!'
      ], 500);
    }
}

public function manageSwitchRoleRequest(Request $request) 
{
  try{
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      
        if ($UserRole == '1') {
          $all_requests = (new SwitchRole)->getSwitchRoleRequests();
           return view('manage-requests/switch-role-requests',compact('all_requests'));
        }else{
          return view('auth.login')->with('message', 'You are not authorized to access!');
        }
      }catch (Exception $e) {
      return response()->json([
        'message' => 'Server Error!'
      ], 500);
    }
}

public function adminRequestApproval($status,$id,$s_id)
{
   try
    {
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '1') 
      {
       if ($status == 1) {
        $requestdetails = SwitchRole::find($id);
        $requestdetails->tsr_status = 1;
        $requestdetails->save();

        $name = User::where('id',$s_id)->value('name');
        $email = User::where('id',$s_id)->value('email');
        $this->requestAcceptNotification($name,$email,$s_id);

          return redirect()->back()->with('message', 'Request Accepted Successfully!!');

        }elseif($status == 2){
        $requestdetails = SwitchRole::find($id);
        $requestdetails->tsr_status = 2;
        $requestdetails->save();

        $name = User::where('id',$s_id)->value('name');
        $email = User::where('id',$s_id)->value('email');
        $this->requestDeclinedNotification($name,$email,$s_id);

          return redirect()->back()->with('fault', 'Request Declined!!');
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

//Get All Verified Teachers:
  public function GetAllTeachers()
  {
   try{
        $clientIP = request()->ip();
        //$clientIP = '72.229.28.185';
        $access_location = \Location::get($clientIP);
        $teachers = (new ClassDetails)->getTeachers();
        $teachers_data = (new ClassDetails)->getTeachersDetails();
        $featured_class = (new ClassDetails)->getFeaturedClasses();
        $categories = (new Categories)->getCategories();
        return view('users/all-verified-teachers',compact('teachers','teachers_data','access_location','featured_class','categories'));
      }catch (Exception $e) {
      return response()->json([
        'message' => 'Server Error!'
      ], 500);
    }
  }

//Get teacher created classes:
  public function GetTeacherClasses($id)
  {
   try{
        $clientIP = request()->ip();
        //$clientIP = '72.229.28.185';
        $access_location = \Location::get($clientIP);
        $t_id = base64_decode($id);
        $teachers_classes = (new ClassDetails)->getApprovedClassesTeacherDash($t_id);
        $featured_class = (new ClassDetails)->getFeaturedClasses();
        $categories = (new Categories)->getCategories();
        $teachers = (new ClassDetails)->getTeachers();
        return view('manage-classes/teacher-classes',compact('teachers','access_location','id','teachers_classes','featured_class','categories'));
      }catch (Exception $e) {
      return response()->json([
        'message' => 'Server Error!'
      ], 500);
    }
  }
//Get Teacher Details:
  public function teacherDetails($t_id)
  {
   try{
        $clientIP = request()->ip();
        //$clientIP = '72.229.28.185';
        $access_location = \Location::get($clientIP);
        $id = base64_decode($t_id);
        $field_of_expertise = (new ClassDetails)->getTeacherFieldsofExpertise($id);
        $teacher_details = (new ClassDetails)->getSingleTeachersDetails($id);
        $featured_class = (new ClassDetails)->getFeaturedClasses();
        $categories = (new Categories)->getCategories();
        $teachers = (new ClassDetails)->getTeachers();
        return view('users/teacher-details',compact('teachers','id','teacher_details','access_location','field_of_expertise','featured_class','categories'));
      }catch (Exception $e) {
      return response()->json([
        'message' => 'Server Error!'
      ], 500);
    }
  } 

//Get User Notifications:
  public function userNotifications() 
  {
   try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '3' || $UserRole == '4'){
        $user_notifications =  (new ManageNotifications)->getStudentAllNotifications($user_id);
       }elseif ($UserRole == '2') {
        $user_notifications =  (new ManageNotifications)->getTeacherAllNotifications($user_id);
       }elseif ($UserRole == '1') {
        $user_notifications =  (new ManageNotifications)->getAdminAllNotifications($user_id);
       }else {
        return view('auth.login')->with('message', 'You are not authorized to access!');
      }
      return view('users/user-notifications',compact('UserRole','user_notifications'));
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
   }
//Delete User Notifications (Single):
  public function deleteUserNotifications($id) 
  {
   try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '2' || $UserRole == '3' || $UserRole == '4' || $UserRole == '1'){
          $not_data = ManageNotifications::find($id);
          $not_data->delete();
          return redirect()->back()->with('message', 'Notifications Removed Successfully!!');
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

  public function clearAllNotifications() 
  {
   try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
        if ($UserRole == '3' || $UserRole == '4'){
            $ids = ManageNotifications::where('not_to',$user_id)->pluck('tn_id');
            // DB::table("tbl_notifications")->whereIn('tn_id',explode(",",$ids))->delete();
            $cnt = count($ids);
            $i=0;
            for ($i=0; $i < $cnt; $i++) { 
            $delete = ManageNotifications::where('not_to',$user_id)->where('tn_user_type',3)->delete();
            }
        }elseif($UserRole == '2'){
            $ids = ManageNotifications::where('not_to',$user_id)->pluck('tn_id');
            $cnt = count($ids);
            $i=0;
            for ($i=0; $i < $cnt; $i++) { 
            $delete = ManageNotifications::where('not_to',$user_id)->where('tn_user_type',2)->delete();
            }
        }elseif($UserRole == '1'){
            $ids = ManageNotifications::where('not_to',$user_id)->pluck('tn_id');
            $cnt = count($ids);
            $i=0;
            for ($i=0; $i < $cnt; $i++) { 
            $delete = ManageNotifications::where('not_to',$user_id)->where('tn_user_type',1)->delete();
            }
        }else {
        return view('auth.login')->with('message', 'You are not authorized to access!');
      }
        return redirect()->back()->with('message', 'Notifications Cleared Successfully!!');
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
   }

  public function seenNotifications($id,$url) 
  {
   try{
       $not_url = base64_decode($url);
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '2' || $UserRole == '3' || $UserRole == '4' || $UserRole == '1'){
          $seen_notification = ManageNotifications::find($id);
          $seen_notification->not_seen = 1;
          $seen_notification->save();

          return redirect($not_url);
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
  
  public function redirect()
  {
    return redirect('/dashboard');
  }

//Email Notifications:
  public function deleteUserNotification($userData){
       $mailData = [
          'body' => 'Your account has been Suspended by the Admin',
          'thanks' => 'Thank you',
          'mailText' => 'Any Time Study',
          'mailUrl' => url('/'),
          'mail_id' => 007
        ];
  
        Notification::send($userData, new AccountNotification($mailData));
    }

  public function additionalInfoNotification($userData){
      $mailData = [
          'body' => 'Your Information Has Been Updated Successfully, you can update your Information also',
          'thanks' => 'Thank you',
          'mailText' => 'Update Info',
          'mailUrl' => url('/edit-userinfo'),
          'mail_id' => 007
        ];
  
        Notification::send($userData, new AccountNotification($mailData));
    }

  public function requestDeclinedNotification($name,$email,$s_id){
      $mailData = [
            'body' => 'Your Request for Teacher Role Has Been Declined!',
            'thanks' => 'Thank you,'.' '.$name,
            'mailText' => 'Dashoard',
            'mailUrl' => url('/dashboard'),
            'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Switch Role";
          $addNotification->not_to =  $s_id;
          $addNotification->tn_user_type =  3;
          $addNotification->not_url = '/dashboard';
          $addNotification->not_details = 'Your Request for Teacher Role Has Been Declined by the Admin!';
          $addNotification->save();

        Notification::route('mail' , $email)->notify(new RequestStatus($mailData)); 
  }

  public function requestAcceptNotification($name,$email,$s_id){

     $mailData = [
            'body' => 'Your Request for Teacher Role Has Been Accepted, you can now switch your role on your dashboard by clicking on "Switch Role Button".',
            'thanks' => 'Thank you,'.' '.$name,
            'mailText' => 'Dashoard',
            'mailUrl' => url('/dashboard'),
            'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Switch Role";
          $addNotification->not_to =  $s_id;
          $addNotification->tn_user_type =  3;
          $addNotification->not_url =  '/dashboard';
          $addNotification->not_details = 'Your Request for Teacher Role Has Been Accepted, you can now switch your role on your dashboard by clicking on "Switch Role Button".';
          $addNotification->save();
        
        Notification::route('mail' , $email)->notify(new RequestStatus($mailData)); 
  }

  public function location(Request $request){
   
        $clientIP = '223.236.37.175'; 
        //$ip = request()->ip();
        //dd($data->countryName);
        //$clientIP = request()->ip();
        $data = \Location::get($clientIP);  
        dd($data);            
  }


}