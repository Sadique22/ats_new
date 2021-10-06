<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use App\Models\ClassDetails;
use App\Models\Categories;
use App\Models\ScheduleDetails;
use App\Models\RequestNewClass;
use App\Models\RequestNewSchedule;
use App\Notifications\RequestStatus;
use App\Models\User;
use App\Models\UserRole;
use App\Models\ManageNotifications;
use Notification;
use Session;
use DB;

class ManageSchedule extends Controller
{

  public function classSchedule($id)
  {
    try
    {
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '2') 
      {
        $schedules = (new ScheduleDetails)->getClassSchedule($id);
        $live_date = ClassDetails::where('id',$id)->value('live_date');
        return view('class-schedule/view-schedule',compact('schedules','live_date','user_id','id'));
      }else{
           return view('auth.login')->with('message', 'You are not authorized to access!');
        }
    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function addSchedule(Request $req)
  {
    try
    {
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '2') 
      {
         $scheduledata = new ScheduleDetails;
         $schedule = $req->schedule_desc;
          $cnt = count($schedule);
          $i=0;
          for ($i=0; $i < $cnt; $i++) { 
           DB::table('tbl_schedule')->insert(
                       array(
                     'teacher_id'     =>  $req->teacher_id,
                     'class_id'       =>  $req->class_id,
                     'schedule_desc'  =>  $schedule[$i],
                     'schedule_date'  =>  $req->schedule_date[$i],
                     'schedule_time'  =>  $req->schedule_time[$i]
                   )
               );
            }

            $id = $req->class_id;
            $schedule_data = ScheduleDetails::where('class_id',$id)->orderBy('s_id','desc')->value('schedule_date');
            $schedule_end_date = ClassDetails::find($id);
            $schedule_end_date->class_end_date = $schedule_data;
            $schedule_end_date->save();

          return redirect()->back()->with('message', 'Schedule Added Successfully!!');
      }else{
           return view('auth.login')->with('message', 'You are not authorized to access!');
        }
    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  } 

  public function scheduleEdit($id,$class_id)
  {
    try
    {
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '2') 
      {
        $scheduledata = (new ScheduleDetails)->getSingleSchedule($id);
        $live_date = ClassDetails::where('id',$class_id)->value('live_date');
        return view('class-schedule/edit-schedule',compact('scheduledata','live_date','class_id'));
      }else{
           return view('auth.login')->with('message', 'You are not authorized to access!');
        }
    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  } 

  public function scheduleUpdate(Request $req,$id)
  {
    try
    {
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '2') 
      {
         $scheduledata = ScheduleDetails::find($id);
         $scheduledata->schedule_desc = $req->schedule_desc;
         $scheduledata->schedule_date = $req->schedule_date;
         $scheduledata->schedule_time = $req->schedule_time;
         if (!empty($scheduledata)) {
           $scheduledata->save();

            $id = $req->class_id;
            $schedule_data = ScheduleDetails::where('class_id',$id)->orderBy('s_id','desc')->value('schedule_date');
            $schedule_end_date = ClassDetails::find($id);
            $schedule_end_date->class_end_date = $schedule_data;
            $schedule_end_date->save();
            
          return redirect()->back()->with('message', 'Schedule Updated Successfully!!');
        }else{
          return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
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

  public function scheduleDelete($id) 
  {
    try{
        $user_id = Auth::id();
        $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '2') {

      $scheduledetails = ScheduleDetails::find($id);
      $scheduledetails->delete();
      return redirect()->back()->with('message', 'Schedule Deleted Successfully!!');
      }else {
        return view('auth.login')->with('message', 'You are not authorized to access!');
      }
    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

//Manage Request New Schedule
  public function requestNewSchedule(Request $request)
  {
    try
    {
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '3' || $UserRole == '4') 
      {
        $validatedData = $request->validate([
        'class_topic' => 'required | max:140',
        'attend_as' => 'required',
        'topic_start_date' => 'required'
         ],
         [
          'class_topic.required' => 'Please Enter Class Topic',
          'attend_as.required' => 'You want to attend as',
          'topic_start_date.required' => 'Please Select Start Date/Time'
         ]);
         if ($validatedData == true) {
         $scheduledata = new RequestNewSchedule;
         $scheduledata->class_id = $request->class_id;
         $scheduledata->student_id = $user_id;
         $scheduledata->teacher_id  = $request->teacher_id; 
         $scheduledata->class_topic = $request->class_topic;
         $scheduledata->attend_as = $request->attend_as;
         $scheduledata->topic_start_date = $request->topic_start_date;
         $scheduledata->sr_message = $request->sr_message;

         if (!empty($scheduledata)) {
            $scheduledata->save();

            $name = User::where('id',$user_id)->value('name');
            $email = User::where('id',$user_id)->value('email');
            $this->scheduleRequestSentNotification($name,$email);
         }
          return redirect()->back()->with('message', 'Your Request has been Sent Successfully!!');
          }else{
        return redirect()->back()->with('message', 'Please Insert Proper Data!!');
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

  public function newScheduleRequests()
  {
   try
    {
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '1') 
      {
        $scheduledata = (new RequestNewSchedule)->getRequestedSchedule();
        return view('manage-requests/view-schedule-requests',compact('scheduledata'));
      }else{
           return view('auth.login')->with('message', 'You are not authorized to access!');
        }
    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  } 

  public function getUserRequests()
  {
   try
    {
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '3' || $UserRole == '4') 
      {
        $scheduledata = (new RequestNewSchedule)->getUserRequestDetails($user_id);
        return view('manage-requests/view-user-requests',compact('scheduledata'));
      }else{
           return view('auth.login')->with('message', 'You are not authorized to access!');
        }
    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  } 

  public function requestStatus($status,$id,$s_id)
  {
   try
    {
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '1') 
      {
       if ($status == 1) {
        $requestdetails = RequestNewSchedule::find($id);
        $requestdetails->sr_status = 1;
        $requestdetails->save();
         
          $name = User::where('id',$s_id)->value('name');
          $email = User::where('id',$s_id)->value('email');
          $this->requestScheduleAcceptNotification($name,$email,$s_id);
        
          return redirect()->back()->with('message', 'Request Accepted Successfully!!');
        }elseif($status == 2){
        $requestdetails = RequestNewSchedule::find($id);
        $requestdetails->sr_status = 2;
        $requestdetails->save();

          $name = User::where('id',$s_id)->value('name');
          $email = User::where('id',$s_id)->value('email');
          $this->requestScheduleDeclinedNotification($name,$email,$s_id);

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

//Manage New Class/Topic Requests
  public function requestNewClass(Request $request)
  {
    try
    {
      $validatedData = $request->validate([
      'user_name' => 'required | max:35',
      'user_contact' => 'required | max:20',
      'user_email' => 'required | max:45',
      'ncr_pay' => 'required',
      'ncr_attend_as' => 'required',
      'ncr_start_date' => 'required',
      'ncr_class_detail' => 'required | max:400',
      'ncr_message' => 'max:190'
      ],
      [
      'user_name.required' => 'Please Enter Your Name',
      'user_contact.required' => 'Please Enter Your Contact Number',
      'user_email.required' => 'Please Enter Your Email',
      'ncr_pay.required' => 'How much amount you are willing to pay?',
      'ncr_attend_as.required' => 'You want to attend as?',
      'ncr_start_date.required' => 'Please Select Start Date',
      'ncr_class_detail.required' => 'Please Enter Class/Topic Details'
      ]);
        if ($validatedData == true) {
        $requestdata = new RequestNewClass;
        $requestdata->ncr_user_name = $request->user_name;
        $requestdata->ncr_user_contact = $request->user_contact;
        $requestdata->ncr_user_email  = $request->user_email; 
        $requestdata->ncr_start_date = $request->ncr_start_date;
        $requestdata->ncr_class_detail = $request->ncr_class_detail;
        $requestdata->ncr_message = $request->ncr_message;
        if ($request->ncr_attend_as == 1) {
          $requestdata->ncr_attend_as = $request->ncr_attend_as;
          $requestdata->ncr_group_member = $request->total_member;
        }else{
          $requestdata->ncr_attend_as = $request->ncr_attend_as;
          $requestdata->ncr_group_member = NULL;
        }
          $clientIP = request()->ip();
          //$clientIP = '72.229.28.185';
          $access_location = \Location::get($clientIP); 
          if($access_location->countryName == "India" && $access_location->countryCode == "IN"){
          $requestdata->ncr_pay = $request->ncr_pay.'₹';
          }else{
          $requestdata->ncr_pay = '$'.$request->ncr_pay;
          }

        if (!empty($requestdata)) {
            $requestdata->save();
              
            $name = $request->user_name;
            $email = $request->user_email;
            $this->classRequestSentNotification($name,$email);
          }
          return redirect()->back()->with('message', 'Your Request for New Class/Topic has been Sent Successfully!!');
        }else{
        return redirect()->back()->with('message', 'Please Insert Proper Data!!');
       }
    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function newClassRequests()
  {
   try
    {
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '1') 
      {
        $requestdata = RequestNewClass::orderBy('ncr_id','desc')->paginate(10);
        return view('manage-requests/view-class-requests',compact('requestdata'));
      }else{
           return view('auth.login')->with('message', 'You are not authorized to access!');
        }
    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }  
  }

  public function classRequestStatus($status,$id)
  {
   try
    {
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '1') 
      {
       if ($status == 1) {
        $requestdata = RequestNewClass::find($id);
        $requestdata->ncr_status = 1;
        $requestdata->save();
        
        $name = RequestNewClass::where('ncr_id',$id)->value('ncr_user_name');
        $email = RequestNewClass::where('ncr_id',$id)->value('ncr_user_email');
        $this->requestClassAcceptNotification($name,$email);

          return redirect()->back()->with('message', 'Request Accepted Successfully!!');
        }elseif($status == 2){
        $requestdata = RequestNewClass::find($id);
        $requestdata->ncr_status = 2;
        $requestdata->save();

        $name = RequestNewClass::where('ncr_id',$id)->value('ncr_user_name');
        $email = RequestNewClass::where('ncr_id',$id)->value('ncr_user_email');
        $this->requestClassDeclinedNotification($name,$email);

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

//Sending Notifications
  public function requestClassDeclinedNotification($name,$email){
      $mailData = [
            'body' => 'Your Request for New Class/Topic Has Been Declined!',
            'thanks' => 'Thank you,'.' '.$name,
            'mailText' => 'Check out Classes',
            'mailUrl' => url('/all-classes'),
            'mail_id' => 007
        ];
        
        Notification::route('mail' , $email)->notify(new RequestStatus($mailData)); 
  }

  public function requestClassAcceptNotification($name,$email){
      $mailData = [
            'body' => 'Your Request for New Class/Topic Has Been Approved, You can now Enroll for the class that you have requested',
            'thanks' => 'Thank you,'.' '.$name,
            'mailText' => 'Check out Classes',
            'mailUrl' => url('/all-classes'),
            'mail_id' => 007
        ];
        
        Notification::route('mail' , $email)->notify(new RequestStatus($mailData)); 
  }   

  public function requestScheduleDeclinedNotification($name,$email,$s_id){
      $mailData = [
            'body' => 'Your Request for New Schedule Has Been Declined!',
            'thanks' => 'Thank you,'.' '.$name,
            'mailText' => 'Check out Classes',
            'mailUrl' => url('/all-classes'),
            'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Schedule Request";
          $addNotification->not_to =  $s_id;
          $addNotification->tn_user_type =  3;
          $addNotification->not_url =  '/user-requests';
          $addNotification->not_details = 'Your Request for New Schedule Has Been Declined!';
          $addNotification->save();
        
        Notification::route('mail' , $email)->notify(new RequestStatus($mailData)); 
  }

  public function requestScheduleAcceptNotification($name,$email,$s_id){
      $mailData = [
            'body' => 'Your Request for new Schedule Has Been Accepted!',
            'thanks' => 'Thank you,'.' '.$name,
            'mailText' => 'Check out Classes',
            'mailUrl' => url('/all-classes'),
            'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Schedule Request";
          $addNotification->not_to =  $s_id;
          $addNotification->tn_user_type =  3;
          $addNotification->not_url =  '/user-requests';
          $addNotification->not_details = 'Your Request for new Schedule Has Been Accepted!';
          $addNotification->save();
        
        Notification::route('mail' , $email)->notify(new RequestStatus($mailData)); 
  }

  public function scheduleRequestSentNotification($name,$email){
      $mailData = [
            'body' => 'Your request for new schedule has been sent successfully, you can check your request status on your dashboard also!',
            'thanks' => 'Thank you,'.' '.$name,
            'mailText' => 'Check Request Status',
            'mailUrl' => url('/user-requests'),
            'mail_id' => 007
        ];
        
        Notification::route('mail' , $email)->notify(new RequestStatus($mailData)); 
  } 

  public function classRequestSentNotification($name,$email){
      $mailData = [
            'body' => 'Your Request for New Class/Topic has been sent successfully, We will get back to you soon!',
            'thanks' => 'Thank you,'.' '.$name,
            'mailText' => 'Explore Classes',
            'mailUrl' => url('/all-classes'),
            'mail_id' => 007
        ];
        
        Notification::route('mail' , $email)->notify(new RequestStatus($mailData)); 
  }       


}