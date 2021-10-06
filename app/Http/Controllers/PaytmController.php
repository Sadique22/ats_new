<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use Srmklive\PayPal\Services\ExpressCheckout;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\AdaptivePayments;
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
use Session;
use DB;


class PaytmController extends Controller
{

    public function pay(Request $request)
    {
        $stu_id = Auth::user()->id;
        $UserRole    =  UserRole::GetUserRole($stu_id);
        $class_id    =  $request->class_id;
        $teacher_id  =  $request->teacher_id;
        $amount      =  $request->price; 
        $student_id  =  $stu_id;
//Inserting Data 
        $paymentDetails = new PaymentDetails;
        $paymentDetails->class_id     = $class_id;
        $paymentDetails->paid_by      = $student_id;
        $paymentDetails->amount_paid  = $amount; 
        $paymentDetails->payment_method = "paytm";
        $paymentDetails->payer_id = "ATS"."_".rand(1,1000);

        if (!empty($paymentDetails)) {
        $paymentDetails->save();
        }else{
            return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
        }
//Inserting Data for Enrollment Details
        if ($UserRole == '2' || $UserRole == '3') {
        $enrollmentDetails = new EnrollmentDetails;
        $enrollmentDetails->class_id    = $class_id;
        $enrollmentDetails->teacher_id  = $teacher_id;
        $enrollmentDetails->student_id  = $student_id;
        $enrollmentDetails->payer_id    = $paymentDetails->payer_id;
        }elseif($UserRole == '4'){
        $enrollmentDetails = new EnrollmentDetails;
        $enrollmentDetails->class_id    = $class_id;
        $enrollmentDetails->teacher_id  = $teacher_id;
        $enrollmentDetails->student_id  = $student_id;
        $enrollmentDetails->child_id    = $request->child_id;
        $enrollmentDetails->payer_id    = $paymentDetails->payer_id;
        }

        if(!empty($enrollmentDetails)){
        $enrollmentDetails->save();
        }
        
        $payment = PaytmWallet::with('receive');

        $payment->prepare([
            'order' => $paymentDetails->payer_id, 
            'user'  =>  $stu_id,
            'mobile_number' => $request->user_contact,
            'email' => $request->user_email, 
            'amount' => $amount, 
            'callback_url' => route('status') 
        ]);
        return $payment->receive();
    }

    public function paymentCallback()
    {
        $transaction = PaytmWallet::with('receive');
        $response = $transaction->response();
        $order_id = $transaction->getOrderId();
        $transaction->getTransactionId();
        $amount = $response['TXNAMOUNT'];

        if ($transaction->isSuccessful()) {
            PaymentDetails::where('payer_id', $order_id)->update(['status' => 'Success', 'token' => $transaction->getTransactionId(), 'payer_status' => 'verified', 'payment_response' => json_encode($response)]);

            EnrollmentDetails::where('payer_id', $order_id)->update(['payment_status' => 'success', 'amount_paid' => $amount, 'is_subscribed' => 1]);

            $id = Auth::id();
            $UserRole    =  UserRole::GetUserRole($id);
            $credit_point = User::find($id);
            $credit_point->credit_points += 10;
            $credit_point->save();
            
            $class_id = EnrollmentDetails::where('payer_id',$order_id)->value('class_id');

            $class_name = ClassDetails::where('id',$class_id)->value('class_title');
            $student_email = User::where('id',$id)->value('email');
            $student_name = User::where('id',$id)->value('name');
            $this->enrollmentSuccessNotification($id,$class_name,$student_email,$student_name);
            //for dashboard notification : Admin
            $admin_id = UserRole::where('user_type',1)->value('ur_id');
            $admin_email = User::where('id',$admin_id)->value('email');
            $this->enrollmentNotificationAdmin($admin_id,$admin_email,$student_name,$class_name);
            //Teacher notification
            $teacher_id = EnrollmentDetails::where('payer_id',$order_id)->value('teacher_id');
            $teacher_email = User::where('id',$teacher_id)->value('email');
            $this->enrollmentNotificationTeacher($teacher_id,$teacher_email,$student_name,$class_name);   
            $checkout_flag = "not-free";
            return response()->view('/payment-success', compact('UserRole','checkout_flag'), 200)->header("Refresh", "2;url=/view-classes");

        }elseif ($transaction->isFailed()) {
            PaymentDetails::where('payer_id', $order_id)->update(['status' => 'failed', 'token' => $transaction->getTransactionId(), 'payer_status' => 'not-verified', 'payment_response' => json_encode($response), 'amount_paid' => 0]);

            EnrollmentDetails::where('payer_id', $order_id)->update(['payment_status' => 'failed', 'amount_paid' => 0, 'is_subscribed' => 0]);

            $id = Auth::id();
            $UserRole    =  UserRole::GetUserRole($id);
            $class_id = EnrollmentDetails::where('payer_id',$order_id)->value('class_id');

            $class_name = ClassDetails::where('id',$class_id)->value('class_title');
            $student_email = User::where('id',$id)->value('email');
            $student_name = User::where('id',$id)->value('name');
            $this->enrollmentFailedNotification($id,$class_name,$student_email,$student_name);

            return response()->view('/payment-cancel', compact('UserRole'), 200)->header("Refresh", "2;url=/view-classes");  
            
        }elseif ($transaction->isOpen()) {
            PaymentDetails::where('payer_id', $order_id)->update(['status' => 'processing', 'token' => $transaction->getTransactionId(), 'payer_status' => 'not-verified', 'payment_response' => json_encode($response)]);

            EnrollmentDetails::where('payer_id', $order_id)->update(['payment_status' => 'processing', 'amount_paid' => 0, 'is_subscribed' => 0]);

            $id = Auth::id();
            $UserRole    =  UserRole::GetUserRole($id);
            $class_id = EnrollmentDetails::where('payer_id',$order_id)->value('class_id');

            $class_name = ClassDetails::where('id',$class_id)->value('class_title');
            $student_email = User::where('id',$id)->value('email');
            $student_name = User::where('id',$id)->value('name');
            $this->enrollmentFailedNotification($id,$class_name,$student_email,$student_name);

            return response()->view('/payment-cancel', compact('UserRole'), 200)->header("Refresh", "2;url=/view-classes");  
        }
    }

//Notifications:
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