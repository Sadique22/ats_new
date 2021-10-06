<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\EnrollmentDetails;
use App\Models\ClassDetails;
use App\Models\Categories;
use App\Models\User;
use Validator;
use Session;
use DB;

class EnrollClassAPI extends Controller
{

  public function enrolledStudents(Request $request) 
  {
   try
    {
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            $id = $token;
            if (User::getUserType($token) == '2') 
            {
              $e_classes = (new EnrollmentDetails)->getEnrolledStudents($id);
            
              if(!empty($e_classes)) 
              {
                return response()->json([
                  'message' => 'All Enrolled Students Fetched Successfully!',
                  'data' => $e_classes
                  ], 200); 
              }else{
                return json_encode(array("status" => 300, "message" => 'Data Not Found!.'));
              }
            }else{
              return json_encode(array("status" => 300, "message" => 'Not Authorized!.'));
            }
          }else{
            return json_encode(array("status" => 300, "message" => 'Not Authenticated.'));
          }
      }else{
        return json_encode(array("status" => 300, "message" => 'Token cannot be empty.'));
      }
    }catch (Exception $e) {
       return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

   public function classEnrolledStudents(Request $request) 
  {
   try
    {
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            if (User::getUserType($token) == '2') 
            {
              $validator = Validator::make($request->all(), [
              'class_id' => 'required',
              ]);
              if($validator->fails()){
              return response()->json($validator->errors()->toJson(), 400);
              }
              if ($validator == true){
                $id = $request->class_id;
                if (ClassDetails::where('id', $id)->exists()) {
                $e_classes = (new EnrollmentDetails)->getClassEnrolledStudents($id);
              }else{
              return json_encode(array("status" => 300, "message" => 'Invalid Class ID!.'));
                }
              }
              if(!empty($e_classes)) 
              {
                return response()->json([
                  'message' => 'Enrolled Students of the Class Fetched Successfully!',
                  'data' => $e_classes
                  ], 200); 
              }else{
                return json_encode(array("status" => 300, "message" => 'Data Not Found!.'));
              }
            }else{
              return json_encode(array("status" => 300, "message" => 'Not Authorized!.'));
            }
          }else{
            return json_encode(array("status" => 300, "message" => 'Not Authenticated.'));
          }
      }else{
        return json_encode(array("status" => 300, "message" => 'Token cannot be empty.'));
      }
    }catch (Exception $e) {
       return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function updateUserProfile(Request $request) 
  {
    try{
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            $inputs = $request->all();
              if (!empty($inputs)){
                $user_details = User::where('id', $token)->update($inputs);
                if (!empty($user_details)){
                  return response()->json([
                            'message' => 'Class Details Updated Successfully!',
                            'data' => $inputs
                          ], 201);
                }else{
                  return json_encode(array("status" => 300, "message" => 'Updation Failed!'));
                  }
              }else{
                  return json_encode(array("status" => 300, "message" => 'Something Went Wrong!'));
                  }
          }else{
                return json_encode(array("status" => 300, "message" => 'Not Authorized.'));
                }
      }else{
          return json_encode(array("status" => 300, "message" => 'Token Cannot be Empty.'));
          }

    }catch (Exception $e){
      return response()->json([
        'message' => 'Server Error!'
      ], 500);
    }
  }

}