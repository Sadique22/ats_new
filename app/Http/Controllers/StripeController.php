<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EnrollmentNotification;
use App\Models\ClassDetails;
use App\Models\EnrollmentDetails;
use App\Models\ChildrenManagement;
use App\Models\ManageNotifications;
use App\Models\PaymentDetails;
use App\Models\TaxDetails;
use App\Models\User;
use App\Models\UserRole;
use Notification;
use Stripe;
use Session;
use DB;

class StripeController extends Controller
{
    public function handlePost(Request $request)
    {
    try{
        //$clientIP = request()->ip();
        $clientIP = '223.236.37.175'; 
        $data = \Location::get($clientIP);  
        $country = $data->countryName;
        $city  = $data->cityName;
        $postal_code = $data->zipCode;
        $state = $data->regionName;
        $user_name = Auth::user()->name;
        $user_email = Auth::user()->email;
        $class_price = $request->price;
        $class_id = $request->class_id;
        $teacher_id = $request->teacher_id;
        $stripeToken = $request->stripeToken;
        $user_id = Auth::user()->id;
        $UserRole  =  UserRole::GetUserRole($user_id);

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
           $customer = \Stripe\Customer::create(array(
            'name' => $user_name,
            'description' => 'Class Enrollment',
            'email' => $user_email,
            'source' => $stripeToken,
            "address" => ["city" => $city, "country" => $country, "line1" => "ATS_Address", "line2" => "", "postal_code" => $postal_code, "state" => $state]
        ));
        $orderID = strtoupper(str_replace('.','',uniqid('', true))); 
        
        if($data->countryName == "India" && $data->countryCode == "IN"){
            $charge = \Stripe\Charge::create(array( 
                'customer' => $customer->id, 
                'amount'   => $class_price * 100, 
                'currency' => "inr", 
                'description' => 'Class Enrollment', 
                'metadata' => array( 
                'order_id' => $orderID 
                ) 
            )); 
        }else{
            $charge = \Stripe\Charge::create(array( 
                'customer' => $customer->id, 
                'amount'   => $class_price * 100, 
                'currency' => "usd", 
                'description' => 'Class Enrollment', 
                'metadata' => array( 
                'order_id' => $orderID 
                ) 
            )); 
        }

    if ($charge->status == "succeeded" || $charge->paid == true) {
//Inserting Payment Data
        $paymentDetails = new PaymentDetails;
        $paymentDetails->class_id     = $class_id;
        $paymentDetails->paid_by      = $user_id;
        $paymentDetails->amount_paid  = $class_price; 
        $paymentDetails->payment_method = "card";
        $paymentDetails->status = "Success";
        $paymentDetails->payer_status = "verified";
        $paymentDetails->payment_response = json_encode($charge);
        $paymentDetails->payer_id = $charge->id;
        $paymentDetails->token  = $stripeToken; 

        if (!empty($paymentDetails)) {
        $paymentDetails->save();
        }else{
            return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
        }
//Inserting Enrollment Data
        if ($UserRole == '2' || $UserRole == '3') {
        $enrollmentDetails = new EnrollmentDetails;
        $enrollmentDetails->class_id    = $class_id;
        $enrollmentDetails->teacher_id  = $teacher_id;
        $enrollmentDetails->student_id  = $user_id;
        $enrollmentDetails->payment_status  = "success";
        $enrollmentDetails->amount_paid  = $class_price;
        $enrollmentDetails->is_subscribed  = 1;
        $enrollmentDetails->payer_id    = $paymentDetails->payer_id;
        }elseif($UserRole == '4'){
        $enrollmentDetails = new EnrollmentDetails;
        $enrollmentDetails->class_id    = $class_id;
        $enrollmentDetails->teacher_id  = $teacher_id;
        $enrollmentDetails->student_id  = $user_id;
        $enrollmentDetails->child_id    = $request->child_id;
        $enrollmentDetails->payment_status  = "success";
        $enrollmentDetails->amount_paid  = $class_price;
        $enrollmentDetails->is_subscribed  = 1;
        $enrollmentDetails->payer_id    = $paymentDetails->payer_id;
        }

        if(!empty($enrollmentDetails)){
        $enrollmentDetails->save();
        }

        $id = Auth::id();
        $UserRole    =  UserRole::GetUserRole($id);
        $credit_point = User::find($id);
        $credit_point->credit_points += 10;
        $credit_point->save();

        $class_id = EnrollmentDetails::where('payer_id',$paymentDetails->payer_id)->value('class_id');
        $class_name = ClassDetails::where('id',$class_id)->value('class_title');
        $student_email = User::where('id',$id)->value('email');
        $student_name = User::where('id',$id)->value('name');
        $this->enrollmentSuccessNotification($id,$class_name,$student_email,$student_name);
        //for dashboard notification : Admin
        $admin_id = UserRole::where('user_type',1)->value('ur_id');
        $admin_email = User::where('id',$admin_id)->value('email');
        $this->enrollmentNotificationAdmin($admin_id,$admin_email,$student_name,$class_name);
        //Teacher notification
        $teacher_email = User::where('id',$teacher_id)->value('email');
        $this->enrollmentNotificationTeacher($teacher_id,$teacher_email,$student_name,$class_name); 
        $checkout_flag = "not-free";
    return response()->view('/payment-success', compact('UserRole','checkout_flag'), 200)->header("Refresh", "2;url=/view-classes");     
    }
//Payment Failed    
    else{
        $paymentDetails = new PaymentDetails;
        $paymentDetails->class_id     = $class_id;
        $paymentDetails->paid_by      = $user_id;
        $paymentDetails->amount_paid  = 0; 
        $paymentDetails->payment_method = "card";
        $paymentDetails->status = "failed";
        $paymentDetails->payer_status = "not-verified";
        $paymentDetails->payment_response = json_encode($charge);
        $paymentDetails->payer_id = $charge->id;
        $paymentDetails->token  = $stripeToken; 

        if (!empty($paymentDetails)) {
        $paymentDetails->save();
        }else{
            return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
        }
//Inserting Enrollment Data
        if ($UserRole == '2' || $UserRole == '3') {
        $enrollmentDetails = new EnrollmentDetails;
        $enrollmentDetails->class_id    = $class_id;
        $enrollmentDetails->teacher_id  = $teacher_id;
        $enrollmentDetails->student_id  = $user_id;
        $enrollmentDetails->payment_status  = "failed";
        $enrollmentDetails->amount_paid  = 0;
        $enrollmentDetails->is_subscribed  = 0;
        $enrollmentDetails->payer_id    = $paymentDetails->payer_id;
        }elseif($UserRole == '4'){
        $enrollmentDetails = new EnrollmentDetails;
        $enrollmentDetails->class_id    = $class_id;
        $enrollmentDetails->teacher_id  = $teacher_id;
        $enrollmentDetails->student_id  = $user_id;
        $enrollmentDetails->child_id    = $request->child_id;
        $enrollmentDetails->payment_status  = "failed";
        $enrollmentDetails->amount_paid  = 0;
        $enrollmentDetails->is_subscribed  = 0;
        $enrollmentDetails->payer_id    = $paymentDetails->payer_id;
        }

        if(!empty($enrollmentDetails)){
        $enrollmentDetails->save();
        }

        $id = Auth::id();
        $UserRole    =  UserRole::GetUserRole($id);
        $class_id = EnrollmentDetails::where('payer_id',$paymentDetails->payer_id)->value('class_id');

        $class_name = ClassDetails::where('id',$class_id)->value('class_title');
        $student_email = User::where('id',$id)->value('email');
        $student_name = User::where('id',$id)->value('name');
        $this->enrollmentFailedNotification($id,$class_name,$student_email,$student_name);

    return response()->view('/payment-cancel', compact('UserRole'), 200)->header("Refresh", "2;url=/view-classes");
    }
       // Session::flash('success', 'Payment has been successfully processed.');
   }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
    }
  }

//Notifications

public function enrollmentSuccessNotification($id,$class_name,$student_email,$student_name){
    $mailData = [
          'body' => 'Hello '.$student_name.''.', You have Successfully Enrolled for Class : '.$class_name,
          'thanks' => 'Thank you',
          'mailText' => 'Check out Details',
          'mailUrl' => url('/view-classes'),
          'mail_id' => 007
      ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Enrollment Success";
          $addNotification->not_to =  $id;
          $addNotification->tn_user_type =  3;
          $addNotification->not_url =  '/view-classes';
          $addNotification->not_details = 'Class Enrollment for Class :'.$class_name.''.' has been Successfully completed';
          $addNotification->save();
        
        Notification::route('mail' , $student_email)->notify(new EnrollmentNotification($mailData)); 
    }

    public function enrollmentFailedNotification($id,$class_name,$student_email,$student_name){
        $mailData = [
          'body' => 'Hello '.$student_name.''.', Class Enrollment for Class : '.$class_name.''.' has been failed',
          'thanks' => 'Thank you',
          'mailText' => 'Check out Details',
          'mailUrl' => url('/view-classes'),
          'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Enrollment Failed";
          $addNotification->not_to =  $id;
          $addNotification->tn_user_type =  3;
          $addNotification->not_url =  '/view-classes';
          $addNotification->not_details = 'Class Enrollment for Class :'.$class_name.''.' has been failed';
          $addNotification->save();
        
        Notification::route('mail' , $student_email)->notify(new EnrollmentNotification($mailData)); 
    }

    public function enrollmentNotificationAdmin($admin_id,$admin_email,$student_name,$class_name){
    $mailData = [
          'body' => 'Student '.$student_name.''.', has Successfully Enrolled for Class : '.$class_name,
          'thanks' => 'Thank you',
          'mailText' => 'Check out Details',
          'mailUrl' => url('/view-classes'),
          'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "New Student Enrollment";
          $addNotification->not_to =  $admin_id;
          $addNotification->tn_user_type =  1;
          $addNotification->not_url =  '/view-classes';
          $addNotification->not_details = 'Student '.$student_name.''.', has Successfully Enrolled for Class : '.$class_name;
          $addNotification->save();
        
        Notification::route('mail' , $admin_email)->notify(new EnrollmentNotification($mailData)); 
    }

    public function enrollmentNotificationTeacher($teacher_id,$teacher_email,$student_name,$class_name){
    $mailData = [
          'body' => 'Student '.$student_name.''.', has Successfully Enrolled for Class : '.$class_name,
          'thanks' => 'Thank you',
          'mailText' => 'Check out Details',
          'mailUrl' => url('/enrolled-student'),
          'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "New Student Enrollment";
          $addNotification->not_to =  $teacher_id;
          $addNotification->tn_user_type =  2;
          $addNotification->not_url =  '/enrolled-student';
          $addNotification->not_details = 'Student '.$student_name.''.', has Successfully Enrolled for Class : '.$class_name;
          $addNotification->save();
        
        Notification::route('mail' , $teacher_email)->notify(new EnrollmentNotification($mailData)); 
    }

}