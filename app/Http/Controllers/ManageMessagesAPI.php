<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ClassDetails;
use App\Models\User;
use App\Models\UserRole;
use App\Models\MessageDetails;
use Session;
use Validator;
use DB;

class ManageMessagesAPI extends Controller
{
  public function sendMessage(Request $request)
  {
    try{
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            if (User::getUserType($token) == '3' || User::getUserType($token) == '2' || User::getUserType($token) == '1') 
            {
              $validator = Validator::make($request->all(), 
              [
              'sent_to' => 'required',
              'message' => 'required || max:200'
              ],
              [
              'sent_to.required' => 'Please Provide User ID.',
              'message.required' => 'Please Enter Message'
              ]);

              if($validator->fails()){
                  return response()->json($validator->errors()->toJson(), 400);
              }
              if ($validator == true && User::getUserType($token) == '3'){
              $sent_by = $token;
              $messageData = new MessageDetails;
	            $messageData->sent_to = $request->sent_to;
	            $messageData->sent_by = $sent_by;
	            $messageData->message = $request->message;
	            $messageData->flag = "Student to Teacher";
	            }
	           elseif($validator == true && User::getUserType($token) == '2'){
	           	$sent_by = $token;
              $messageData = new MessageDetails;
	            $messageData->sent_to = $request->sent_to;
	            $messageData->sent_by = $sent_by;
	            $messageData->message = $request->message;
	            $messageData->flag = "Teacher to Student";
	            }
	           else{
                  return json_encode(array("status" => 300, "message" => 'Something Went Wrong!'));
                  }
                if (!empty($messageData)) {
                  $messageData->save();
                return response()->json([
                    'message' => 'Message Sent Successfully!',
                  ], 201);
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

    public function receivedMessage(Request $request) 
  {
    try
    {
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            if (User::getUserType($token) == '2' || User::getUserType($token) == '3') 
            {
              $id = $token;
              $messageData = (new MessageDetails)->GetUserMessages($id);
            if(!empty($messageData)) 
            {
              return response()->json([
                'message' => 'Messages Fetched Successfully!',
                'data' => $messageData
                ], 200); 
            }else{
              return json_encode(array("status" => 300, "message" => 'Data Not Found!.'));
            }
            }else{
            return json_encode(array("status" => 300, "message" => 'Not Authorized.'));
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

}