<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ClassDetails;
use App\Models\Categories;
use App\Models\UserRole;
use App\Models\User;
use App\Models\FeedbackDetails;
use App\Models\ManageNotifications;
use App\Models\EnrollmentDetails;
use App\Notifications\FeedbackNotification;
use Notification;
use Session;
use DB;

class Feedback extends Controller
{
  public function index(Request $request) 
  {
   try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '2') {
          $validatedData = $request->validate([
              'student_name' => 'required',
              'feedback' => 'required || max:200',
              ],
              [
            'student_name.required' => 'Please Select Student Name',
            'feedback.required' => 'Please Enter Feedback',
             ]);

          if ($validatedData == true) {
            $feedbackData = new FeedbackDetails;
            $feedbackData->student_id = $request->student_name;
            $feedbackData->progressive_feedback = $request->feedback;
            $feedbackData->flag = "Feedback by Teacher";
            $feedbackData->teacher_id = $user_id;

            if (! FeedbackDetails::where('teacher_id', $user_id)->where('student_id', $request->student_name)->where('flag','Feedback by Teacher')->exists() && !empty($feedbackData)) {
            $feedbackData->save();
              $s_id = $request->student_name;
              $s_details = User::get()->where('id',$s_id);
              $t_details = Auth::user()->name;
              $sent_to = User::where('id',$s_id)->value('name');

              $this->receiveFeedbackNotificationStudent($s_details,$t_details,$s_id);
              $this->sentFeedbackNotification($sent_to);

            return redirect()->back()->with('message', 'Feedback Sent Successfully!!');
            }
            else{
            return redirect()->back()->with('fault', 'You have already sent Feedback to the Student!!');
            }
          }
          else{
            return redirect()->back()->with('fault', 'Data Not Valid!!');
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

  public function allFeedbacks()
  {
   try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);

        if ($UserRole == 3 || $UserRole == 4) {
          $feedbacks = (new FeedbackDetails)->getStudentFeedback($user_id);
        }elseif ($UserRole == 2) {
          $feedbacks = (new FeedbackDetails)->getTeacherFeedback($user_id);
        }else{
        return view('auth.login')->with('message', 'You are not Authoirzed to access this page!');
        }
        if (!empty($feedbacks)) {
           return view('students/my-feedbacks',compact('feedbacks','UserRole'));
          }else {
           return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
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
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '3' || $UserRole == '4') {
          $validatedData = $request->validate([
            'teacher_name' => 'required',
            'feedback' => 'required || max:200',
            'rating' => 'required',
            ],
            [
            'teacher_name.required' => 'Please Select Teacher Name',
            'feedback.required' => 'Please Enter Feedback',
            'rating.required' => 'Please Select Star Rating',
             ]);

          if ($validatedData == true) {
            $feedbackData = new FeedbackDetails;
            $feedbackData->teacher_id = $request->teacher_name;
            $feedbackData->teacher_feedback = $request->feedback;
            $feedbackData->rating = $request->rating;
            $feedbackData->flag = "Feedback by Student";
            $feedbackData->f_status = 0;
            $feedbackData->student_id = $user_id;
     
           if (! FeedbackDetails::where('student_id', $user_id)->where('teacher_id', $request->teacher_name)->where('flag','Feedback by Student')->exists() && !empty($feedbackData)) {
            $feedbackData->save();

                $t_id = $request->teacher_name;
                $s_details = User::get()->where('id',$t_id);
                $t_details = Auth::user()->name;
                $sent_to = User::where('id',$t_id)->value('name');
                $this->receiveFeedbackNotificationTeacher($s_details,$t_details,$t_id);
                $this->sentFeedbackNotification($sent_to);

            return redirect()->back()->with('message', 'Feedback Sent to Teacher Successfully!!');
            }else{
            return redirect()->back()->with('fault', 'You have Already sent Feedback to the Teacher!!');
            }
          }else{
            return redirect()->back()->with('fault', 'Data Not Valid!!');
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
   public function sendFeedbackView($id)
  {
   try{
    $user_id = Auth::id();
    $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '3' || $UserRole == '4') {
        return view('students/give-feedback',compact('id'));
      }
   }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function postClassFeedback(Request $request,$id)
  {
   try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '3' || $UserRole == '4') {
          $validatedData = $request->validate([
              'rating'   => 'required',
              'feedback' => 'required || max:200',
              ],
              [
            'rating.required' =>'Please Select rating',
            'feedback.required' => 'Please Enter Feedback',
             ]);

          if ($validatedData == true) {
           
            $feedbackData = new FeedbackDetails;
            $feedbackData->rating = $request->rating;
            $feedbackData->class_feedback = $request->feedback;
            $feedbackData->flag = "Feedback by Student to Class";
            $feedbackData->f_status = 0;
            $feedbackData->student_id = $user_id;
            $feedbackData->class_id = $id;
           
            if (! FeedbackDetails::where('student_id', $user_id)->where('class_id', $id)->exists() && !empty($feedbackData)) {
              $feedbackData->save();
                
                $class_title = ClassDetails::where('id',$id)->value('class_title');
                $created_by = ClassDetails::where('id',$id)->value('created_by');
                $userData = User::get()->where('id',$created_by);
                $name = Auth::user()->name;
                $sent_to = $class_title;
                $this->classReceivedFeedbackNotification($name,$userData,$class_title,$id,$created_by);
                $this->sentFeedbackNotification($sent_to);

                return redirect('/view-classes')->with('message', 'Feedback Sent Successfully!!');
            }else{
                return redirect('/view-classes')->with('fault', 'Your feedback has already been Submitted!!');
            }
          }else{
            return redirect()->back()->with('fault', 'Data Not Valid!!');
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

  public function classFeedbacks($id) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1' || $UserRole == '2'){

       $feedbacks = (new FeedbackDetails)->getClassFeedback($id);
       $classname = ClassDetails::getClassName($id);
       $rating = DB::table('tbl_feedbacks')->where('tbl_feedbacks.class_id',$id)->avg('tbl_feedbacks.rating');
       $overall_rating = ceil($rating);
      return view('users/class-feedbacks',compact('feedbacks','classname','overall_rating'));
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

  public function allUserFeedbacks($id, $u_type) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1'){

       $feedbacks = FeedbackDetails::getUserFeedback($id,$u_type);
       $username = User::getUserName($id);
      return view('users/all-feedbacks',compact('feedbacks','u_type','username'));
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

  public function approveFeedback($id) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1' || $UserRole == '2'){

        $feedbackdetails = FeedbackDetails::find($id);     
        $feedbackdetails->f_status = 1;
        $feedbackdetails->save();
        return redirect()->back()->with('message', 'Feedback Approved Successfully!!');
      }else{
         return view('auth.login')->with('message', 'You are not authorized to access!');
      }
    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function declineFeedback($id) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1' || $UserRole == '2'){

        $feedbackdetails = FeedbackDetails::find($id);     
        $feedbackdetails->f_status = 0;
        $feedbackdetails->save();
        return redirect()->back()->with('message', 'Feedback Declined Successfully!!');
      }else{
        return view('auth.login')->with('message', 'You are not authorized to access!');
      }
    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

//Email Notifications:
  public function receiveFeedbackNotificationTeacher($s_details,$t_details,$t_id){
     $userData = $s_details;

        $mailData = [
            'body' => 'You have received feedback from: ('.$t_details.')',
            'thanks' => 'Thank you',
            'mailText' => 'Check out feedbacks',
            'mailUrl' => url('/my-feedbacks'),
            'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Received Feedback";
          $addNotification->not_to =  $t_id;
          $addNotification->tn_user_type =  2;
          $addNotification->not_url =  '/my-feedbacks';
          $addNotification->not_details = 'You have received feedback from:' .$t_details;
          $addNotification->save();
  
        Notification::send($userData, new FeedbackNotification($mailData));
    }

    public function receiveFeedbackNotificationStudent($s_details,$t_details,$s_id){
     $userData = $s_details;
  
        $mailData = [
            'body' => 'You have received feedback from: ('.$t_details.')',
            'thanks' => 'Thank you',
            'mailText' => 'Check out feedbacks',
            'mailUrl' => url('/my-feedbacks'),
            'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Received Feedback";
          $addNotification->not_to =  $s_id;
          $addNotification->tn_user_type =  2;
          $addNotification->not_url =  '/my-feedbacks';
          $addNotification->not_details = 'You have received feedback from:' .$t_details;
          $addNotification->save();
  
        Notification::send($userData, new FeedbackNotification($mailData));
    }

    public function classReceivedFeedbackNotification($name,$userData,$class_title,$id,$created_by){
  
        $mailData = [
            'body' => $name .' has sent feedback for your class :'.$class_title,
            'thanks' => 'Thank you',
            'mailText' => 'Check out',
            'mailUrl' => url('/class-feedbacks/'.$id),
            'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Class Feedback";
          $addNotification->not_to =  $created_by;
          $addNotification->tn_user_type =  2;
          $addNotification->not_url =  '/class-feedbacks/'.$id;
          $addNotification->not_details = $name .' has sent feedback for your class :'.$class_title;
          $addNotification->save();
  
        Notification::send($userData, new FeedbackNotification($mailData));
    }

    public function sentFeedbackNotification($sent_to){
     $userData = Auth::user();
  
        $mailData = [
            'body' => 'Your feedback has been sent successfully to: ('.$sent_to.')',
            'thanks' => 'Thank you',
            'mailText' => 'Check out feedbacks',
            'mailUrl' => url('/my-feedbacks'),
            'mail_id' => 007
        ];
  
        Notification::send($userData, new FeedbackNotification($mailData));
    }  


}