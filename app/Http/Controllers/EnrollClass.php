<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use App\Models\ClassDetails;
use App\Models\Categories;
use App\Models\UserRole;
use App\Models\User;
use App\Models\ScheduleDetails;
use App\Models\ChildrenManagement;
use App\Models\EnrollmentDetails;
use App\Models\PaymentDetails;
use Session;
use DB;

class EnrollClass extends Controller
{
  public function index(Request $req,$c_id,$t_id) 
  {
  	try{
        $s_id = Auth::id();
        $class_id   = $c_id;
        $teacher_id = $t_id;
        $student_id = $s_id;
        $UserRole   = UserRole::GetUserRole($s_id);
//Inserting Payment Details 
        $paymentDetails = new PaymentDetails;
        $paymentDetails->class_id     = $class_id;
        $paymentDetails->paid_by      = $student_id;
        $paymentDetails->amount_paid  = 0; 
        $paymentDetails->payer_id = "ATS"."_".rand(1,1000);
        $paymentDetails->payer_status = "verified";
        $paymentDetails->payment_method = "free";
        $paymentDetails->status = "Success";

        if (!empty($paymentDetails)) {
        $paymentDetails->save();
        }else{
            return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
        }
//Inserting Enrollment Details 	      
	    if($UserRole == 3 || $UserRole == 2) {
	        $enrollmentDetails = new EnrollmentDetails;
          $enrollmentDetails->class_id = $class_id;
          $enrollmentDetails->teacher_id = $teacher_id;
          $enrollmentDetails->student_id = $student_id;
          $enrollmentDetails->payment_status = "success";
          $enrollmentDetails->amount_paid = 0;
          $enrollmentDetails->is_subscribed = 1;
          $enrollmentDetails->payer_id = $paymentDetails->payer_id;

        if(!empty($enrollmentDetails)){
         $enrollmentDetails->save();
         $id = Auth::id();
         $credit_point = User::find( $id );
         $credit_point->credit_points += 10;
         $credit_point->save();

         $classes = (new ClassDetails)->getStudentClass($s_id);
         $teachers = (new ClassDetails)->getStudentClassTeachers($s_id);
         $custom_message = "You are Enrolled SuccessFully!";
         $checkout_flag = "free";
         return response()->view('/payment-success', compact('custom_message','checkout_flag','UserRole'), 200)->header("Refresh", "2;url=/view-classes");
         //return view('manage-classes/view-classes',compact('custom_message','UserRole','classes','teachers'));	
        }else {
	      return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
	       }
  	   }elseif($UserRole == 4) {
          $enrollmentDetails = new EnrollmentDetails;
          $enrollmentDetails->class_id = $class_id;
          $enrollmentDetails->teacher_id = $teacher_id;
          $enrollmentDetails->student_id = $student_id;
          $enrollmentDetails->child_id = $req->child_id;
          $enrollmentDetails->amount_paid = 0;
          $enrollmentDetails->is_subscribed = 1;
          $enrollmentDetails->payer_id = $paymentDetails->payer_id;
          $enrollmentDetails->payment_status = "success";

        if(!empty($enrollmentDetails)){
         $enrollmentDetails->save();
         $id = Auth::id();
         $credit_point = User::find( $id );
         $credit_point->credit_points += 10;
         $credit_point->save();

         $classes = (new ClassDetails)->getStudentClass($s_id);
         $teachers = (new ClassDetails)->getStudentClassTeachers($s_id);
         $childrens = ChildrenManagement::where('parent_id',$id)->get();
         $custom_message = "You are Enrolled SuccessFully!";
         $checkout_flag = "free";
         return response()->view('/payment-success', compact('custom_message','checkout_flag','UserRole'), 200)->header("Refresh", "2;url=/view-classes");
         //return view('manage-classes/view-classes',compact('custom_message','childrens','UserRole','classes','teachers'));  
        }else {
        return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
         }
       }else {
  	      $custom_message = "Please Login As a Student(If you do not have account then Register as a Student to Enroll Any Class.)";
            return view('auth.login',compact('custom_message'));
  	    }
	    }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
     }
  }

  public function enrolledStudents()
  {
   try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);

        if ($UserRole == 2) {
          $e_classes = (new EnrollmentDetails)->getEnrolledStudents($user_id);
          if (!empty($e_classes)) {
           return view('students/enrolled-student',compact('e_classes','user_id','UserRole'));
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
//For Admin Dashboard
  public function viewTeacherEnrolledStudents($id)
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);

        if ($UserRole == 1) {
          $e_classes = (new EnrollmentDetails)->getEnrolledStudents($id);
          if (!empty($e_classes)) {
           return view('students/enrolled-student',compact('e_classes','user_id','UserRole'));
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

  public function checkoutUser(Request $request){
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
        if ($UserRole == 3 || $UserRole == 2) {
           $teacher_id = $request->teacher_id;
           $class_id   = $request->class_id;
           $calculate  = base64_decode($request->calculate);
           $class_name  = ClassDetails::where('id',$class_id)->value('class_title');
           $live_date  = ClassDetails::where('id',$class_id)->value('live_date');
           $class_desc  = ClassDetails::where('id',$class_id)->value('class_desc');
           $teacher_name = User::where('id',$teacher_id)->value('name');
           $user_name = Auth::user()->name;
           $user_email = Auth::user()->email;
           $user_contact = Auth::user()->contact;
           $class_schedule = (new ScheduleDetails)->getClassSchedule($class_id);
           $class_url = $request->current_url;
           $clientIP = request()->ip();
           //$clientIP = '223.236.37.175';
           $access_location = \Location::get($clientIP); 

           $flag = "checkout";
           return view('checkout',compact('teacher_id','class_url','access_location','user_contact','class_id','live_date','class_desc','teacher_name','calculate','class_name','user_name','class_schedule','user_email','flag','UserRole'));
         }elseif ($UserRole == 4) {
           $teacher_id = $request->teacher_id;
           $class_id   = $request->class_id;
           $calculate  = base64_decode($request->calculate);
           $class_name  = ClassDetails::where('id',$class_id)->value('class_title');
           $live_date  = ClassDetails::where('id',$class_id)->value('live_date');
           $class_desc  = ClassDetails::where('id',$class_id)->value('class_desc');
           $teacher_name = User::where('id',$teacher_id)->value('name');
           $user_name = Auth::user()->name;
           $childrens = ChildrenManagement::where('parent_id',$user_id)->get();
           $class_schedule = (new ScheduleDetails)->getClassSchedule($class_id);
           $user_email = Auth::user()->email;
           $user_contact = Auth::user()->contact;
           $class_url = $request->current_url;
           
           $clientIP = request()->ip();
           //$clientIP = '223.236.37.175';
           $access_location = \Location::get($clientIP); 
           
           $flag = "checkout";
           return view('checkout',compact('teacher_id','class_url','access_location','class_id','user_email','user_contact','childrens','live_date','class_desc','teacher_name','calculate','user_name','class_name','class_schedule','flag','UserRole'));
         }
         else{
             $custom_message = "Please Login As a Student(If you do not have account then Register as a Student to Enroll Any Class.)";
            return view('auth.login',compact('custom_message'));
         }
      }catch (Exception $e) {
         return response()->json([
           'message' => 'Server Error!'
              ], 500);
      }
  }
  
}