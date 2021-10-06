<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ClassDetails;
use App\Models\Categories;
use App\Models\FeedbackDetails;
use App\Models\UserRole;
use App\Models\User;
use Validator;
use Session;
use DB;

class ManageFeedbackAPI extends Controller
{
  public function teacherFeedbacks(Request $request) 
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
              $feedbacks = (new FeedbackDetails)->getTeacherFeedback($id);
            
              if(!empty($feedbacks)) 
              {
                return response()->json([
                  'message' => 'Feedbacks Fetched Successfully!',
                  'data' => $feedbacks
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

  public function studentFeedbacks(Request $request) 
  {
    try
    {
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            $id = $token;
            if (User::getUserType($token) == '3')
            {
              $feedbacks = (new FeedbackDetails)->getStudentFeedback($id);
              if(!empty($feedbacks)) 
              {
                return response()->json([
                  'message' => 'Feedbacks Fetched Successfully!',
                  'data' => $feedbacks
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

public function classFeedbacks(Request $request) 
  {
    try
    {
      $validator = Validator::make($request->all(), 
        [
          'class_id' => 'required'
        ],
        [
          'class_id.required' => 'Class ID is required'
        ]);
        if($validator->fails()){
          return response()->json($validator->errors()->toJson(), 400);
        }
        if ($validator == true){
        $class_id = $request->class_id;
        if (ClassDetails::where('id', $class_id)->exists()) {
        $feedbacks = (new FeedbackDetails)->getClassFeedback($class_id);
        if(!empty($feedbacks)) 
          {
            return response()->json([
                'message' => 'Feedbacks Fetched Successfully!',
                'data' => $feedbacks
                ], 200); 
          }else{
            return json_encode(array("status" => 300, "message" => 'Data Not Found!.'));
          }
        }else{
          return json_encode(array("status" => 300, "message" => 'Invalid Class ID!.'));
        }
        }
    }catch (Exception $e) {
       return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }
  public function postTeacherFeedback(Request $request) 
  {
    try{
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            if (User::getUserType($token) == '3') 
            {
              $validator = Validator::make($request->all(), 
              [
              'teacher_id' => 'required',
              'feedback' => 'required || max:200 || min:4',
              ],
              [
              'teacher_id.required' => 'Teacher ID is required',
              'feedback.required' => 'Please Enter Feedback',
              ]);

              if($validator->fails()){
                  return response()->json($validator->errors()->toJson(), 400);
              }
              if ($validator == true){
                $id = $token;
                
                $feedbackData = new FeedbackDetails;
                $feedbackData->teacher_id = $request->teacher_id;
                $feedbackData->teacher_feedback = $request->feedback;
                $feedbackData->flag = "Feedback by Student";
                $feedbackData->student_id = $id;
                if (UserRole::where('ur_id', $request->teacher_id)->where('user_type',2)->exists()) {

                if (! FeedbackDetails::where('student_id', $id)->where('teacher_id', $request->teacher_id)->exists() && !empty($feedbackData)) {

                  $feedbackData->save();

                  return response()->json([
                            'message' => 'Feedback sent Successfully to the Teacher!',
                            'data' => $feedbackData
                          ], 201);
                  }else{
                    return json_encode(array("status" => 300, "message" => 'You have Already Sent Feedback to the Teacher!'));
                  }
                }else{
                   return json_encode(array("status" => 300, "message" => 'Invalid Teacher ID!'));
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


  public function postStudentFeedback(Request $request) 
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
              'student_id' => 'required',
              'feedback' => 'required || max:200 || min:4',
              ],
              [
              'student_id.required' => 'Student ID is required',
              'feedback.required' => 'Please Enter Feedback',
              ]);

              if($validator->fails()){
                  return response()->json($validator->errors()->toJson(), 400);
              }
              if ($validator == true){
                $id = $token;
                
                $feedbackData = new FeedbackDetails;
                $feedbackData->student_id = $request->student_id;
                $feedbackData->progressive_feedback = $request->feedback;
                $feedbackData->flag = "Feedback by Teacher";
                $feedbackData->teacher_id = $id;
                if (UserRole::where('ur_id', $request->student_id)->where('user_type',3)->exists()) {

                if (! FeedbackDetails::where('teacher_id', $id)->where('student_id', $request->student_id)->exists() && !empty($feedbackData)) {

                  $feedbackData->save();

                  return response()->json([
                            'message' => 'Feedback sent Successfully to the Student!',
                            'data' => $feedbackData
                          ], 201);
                  }else{
                    return json_encode(array("status" => 300, "message" => 'You have Already Sent Feedback to the Student!'));
                    }
                  }else{
                    return json_encode(array("status" => 300, "message" => 'Invalid Student ID!'));
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

  public function postClassFeedback(Request $request) 
  {
    try{
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            if (User::getUserType($token) == '3') 
            {
              $validator = Validator::make($request->all(), 
              [
              'class_id' => 'required',
              'rating' => 'required',
              'feedback' => 'required || max:200 || min:4',
              ],
              [
              'class_id.required' => 'Class ID is required',
              'feedback.required' => 'Please Enter Feedback',
              'rating.required' => 'Please Provide Rating',
              ]);

              if($validator->fails()){
                  return response()->json($validator->errors()->toJson(), 400);
              }
              if ($validator == true){
                $id = $token;
                
                $feedbackData = new FeedbackDetails;
                $feedbackData->class_id = $request->class_id;
                $feedbackData->class_feedback = $request->feedback;
                $feedbackData->rating = $request->rating;
                $feedbackData->flag = "Feedback by Student to Class";
                $feedbackData->student_id = $id;

                if (ClassDetails::where('id', $request->class_id)->exists()) {

                if (! FeedbackDetails::where('student_id', $id)->where('class_id', $request->class_id)->exists() && !empty($feedbackData)) {

                  $feedbackData->save();

                  return response()->json([
                            'message' => 'Feedback sent Successfully!',
                            'data' => $feedbackData
                          ], 201);
                  }else{
                    return json_encode(array("status" => 300, "message" => 'You have Already Submitted your Feedback!'));
                    }
                  }else{
                    return json_encode(array("status" => 300, "message" => 'Invalid Class ID!'));
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