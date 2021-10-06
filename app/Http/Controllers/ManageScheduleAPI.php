<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ClassDetails;
use App\Models\ScheduleDetails;
use App\Models\User;
use App\Models\UserRole;
use Validator;
use Session;
use DB;

class ManageScheduleAPI extends Controller
{

 public function postSchedule(Request $request)
  {
    try{
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            if (User::getUserType($token) == '2') 
            {
              $validator = Validator::make($request->all(), 
              [
              'schedule_desc' => 'required',
              'schedule_date' => 'required',
              'class_id' => 'required'
              ],
              [
              'schedule_desc.required' => 'Please Provide Schedule Info.',
              'schedule_date.required' => 'Please Provide Date',
              'class_id.required' => 'Please Provide Class ID'
              ]);

              if($validator->fails()){
                  return response()->json($validator->errors()->toJson(), 400);
              }
              if ($validator == true){
                $id = $token;
                
                $scheduledetails = new ScheduleDetails;
                $class_id = $request->class_id;
                $cnt = count($request->schedule_desc);
                $i=0;
                for ($i=0; $i < $cnt; $i++) { 
                 DB::table('tbl_schedule')->insert(
                             array(
                           'teacher_id'     =>  $id,
                           'class_id'       =>  $class_id,
                           'schedule_desc'  =>  $request->schedule_desc[$i],
                           'schedule_date'  =>  $request->schedule_date[$i]
                         )
                     );
                  }
                  return response()->json([
                            'message' => 'Schedule Added Successfully!',
                          ], 201);
              }else{
                  return json_encode(array("status" => 300, "message" => 'Something Went Wrong!'));
                  }
            }else{
                return json_encode(array("status" => 300, "message" => 'Not Authenticated.'));
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

  public function deleteSchedule(Request $request) 
  {
    try{
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            if (User::getUserType($token) == '2') 
            {
              $validator = Validator::make($request->all(), [
              'schedule_id' => 'required',
              ],[
                'schedule_id.required' => 'Please Provide Schedule ID'
              ]);

              if($validator->fails()){
                  return response()->json($validator->errors()->toJson(), 400);
              }
             if ($validator == true) 
             {
                   $id = $request->schedule_id;
                   $scheduledetails = ScheduleDetails::find($id);

                if (!empty($scheduledetails) && ScheduleDetails::where('s_id', $id)->exists()){
                   $scheduledetails->delete();
                   return response()->json([
                      'message' => 'Schedule Deleted Successfully!',
                        ], 201);
               }else{
                return json_encode(array("status" => 300, "message" => 'Invalid ID!')); 
                  }
              }else{
              return json_encode(array("status" => 300, "message" => 'Something Went Wrong!'));
                }
            }else{
              return json_encode(array("status" => 300, "message" => 'Not Authenticated.'));
              }
          }else{
             return json_encode(array("status" => 300, "message" => 'Not Authorized.'));
          }
        }else{
             return json_encode(array("status" => 300, "message" => 'Token Cannot be Empty.'));
            }
      }catch (Exception $e) {
         return response()->json([
           'message' => 'Server Error!'
              ], 500);
      }
  }
  
  public function getClassSchedule(Request $request) 
  {
    try
    {
      $validator = Validator::make($request->all(), [
        'class_id' => 'required',
        ],
        [
          'class_id.required' => 'Please Provide Class ID'
        ]);

      if($validator->fails()){
        return response()->json($validator->errors()->toJson(), 400);
      }
      if ($validator == true) 
      {
        $class_id = $request->class_id;
        $schedules = (new ScheduleDetails)->getClassSchedule($class_id);
        if(!empty($schedules)) 
        {
          return response()->json([
            'message' => 'Schedule Details Fetched Successfully!',
            'data' => $schedules
          ], 200); 
        }else{
          return json_encode(array("status" => 300, "message" => 'Data Not Found!.'));
          }
      }else{
          return json_encode(array("status" => 300, "message" => 'Something Went Wrong!.'));
        }

    }catch (Exception $e) {
       return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function updateClassSchedule(Request $request) 
  {
    try{
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            if (User::getUserType($token) == '2') 
            {
              
              $validator = Validator::make($request->all(), [
              's_id' =>'required'
              ]);
               $id = $request->s_id;
              if (ScheduleDetails::where('s_id', $id)->exists()) {
              $inputs = $request->all();
             
            }else{
              return json_encode(array("status" => 300, "message" => 'Invalid Schedule ID!'));
            }
              if($validator->fails()){
                  return response()->json($validator->errors()->toJson(), 400);
              }
              if ($validator == true){
                $scheduledetails = ScheduleDetails::where('s_id', $id)->where('teacher_id', $token )->update($inputs);
                if (!empty($scheduledetails)){
                  return response()->json([
                            'message' => 'Schedule Details Updated Successfully!',
                            'data' => $inputs
                          ], 201);
                }else{
                  return json_encode(array("status" => 300, "message" => 'Updation Failed!'));
                  }
              }else{
                  return json_encode(array("status" => 300, "message" => 'Something Went Wrong!'));
                  }
            }else{
                return json_encode(array("status" => 300, "message" => 'Not Authenticated.'));
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