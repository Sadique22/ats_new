<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
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

class Payment extends Controller
{
  public function handlePayment(Request $request)
  {
    try{
        $product = [];
        $product['items'] = [
            [
                'name'  => $request->class_id,
                'price' => $request->price,
                'desc'  => $request->teacher_id,
                'qty'   => 1
            ]
        ];

        $stu_id = Auth::user()->id;
        $UserRole    =  UserRole::GetUserRole($stu_id);
        
        $product['invoice_id'] = 1;
        if($UserRole == '3' || $UserRole == '2'){
          $product['invoice_description'] = "Class Payment";
        }elseif($UserRole == '4'){
          $product['invoice_description'] = $request->child_id;
        }
        $product['return_url'] = route('success.payment');
        $product['cancel_url'] = route('cancel.payment');
        $product['total'] = $request->price;
        $product['shipping_discount'] = 0;

        // $total = 0;
        // foreach($product['items'] as $item) {
        //     $total += $item['price']*$item['qty'];
        // }
        //$product['total'] = $total;
        //$product['shipping_discount'] = round((10 / 100) * $total, 2);
        
        $provider = new ExpressCheckout; 
        $response = $provider->setExpressCheckout($product);
    
        $response = $provider->setExpressCheckout($product, true); 
        if(isset($response['paypal_link'])){
           return redirect($response['paypal_link']); 
       }else{
          return redirect()->back()->with('message', 'Something went wrong,Please try again later!!');
       }
    }catch (Exception $e) {
      return response()->json([
        'message' => 'Server Error!'
      ], 500);
    }
  }
   
  public function paymentCancel(Request $request)
  {
    try{
        $paypalModule = new ExpressCheckout;
        $response = $paypalModule->getExpressCheckoutDetails($request->token);
    
          $s_id = Auth::id();
          $class_id    =  $response['L_NAME0'];
          $teacher_id  =  $response['L_DESC0'];
          $amount_paid =  $response['L_AMT0'];
          $student_id  =  $s_id;
          $UserRole    =  UserRole::GetUserRole($s_id);
          
          $paymentDetails = new PaymentDetails;
          $paymentDetails->class_id     = $class_id;
          $paymentDetails->paid_by      = $student_id;
          $paymentDetails->amount_paid  = 0; 
          $paymentDetails->payer_id     = "ATS"."_".rand(1,1000);
          $paymentDetails->payer_status = "not-verified"; 
          $paymentDetails->payer_paypal_acount_id = $response['PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID']; 
          $paymentDetails->token     = $response['TOKEN']; 
          $paymentDetails->status    = "failed";
          $paymentDetails->payment_response   = json_encode($response);
          $paymentDetails->payment_method = "paypal";

        if (!empty($paymentDetails)) {
          $paymentDetails->save();
        }else{
            return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
        } 

        if($UserRole == 3) {
          $enrollmentDetails = new EnrollmentDetails;
          $enrollmentDetails->class_id = $class_id;
          $enrollmentDetails->teacher_id = $teacher_id;
          $enrollmentDetails->student_id = $student_id;
          $enrollmentDetails->amount_paid = 0;
          $enrollmentDetails->payment_status = "failed";
          $enrollmentDetails->is_subscribed   = 0;
          $enrollmentDetails->payer_id = $paymentDetails->payer_id;

        if(!empty($enrollmentDetails)){
         $enrollmentDetails->save();

         $class_name = ClassDetails::where('id',$class_id)->value('class_title');
         $student_email = User::where('id',$student_id)->value('email');
         $student_name = User::where('id',$student_id)->value('name');
         $this->enrollmentFailedNotification($student_id,$class_name,$student_email,$student_name);
        
         $custom_message = "Class Enrollment Failed!";
         return response()->view('/payment-cancel', compact('custom_message','UserRole'), 200)->header("Refresh", "2;url=/view-classes");
        }else {
          return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
           }
       }elseif($UserRole == 4) {
          $enrollmentDetails = new EnrollmentDetails;
          $enrollmentDetails->class_id   = $class_id;
          $enrollmentDetails->teacher_id = $teacher_id;
          $enrollmentDetails->student_id = $student_id;
          $enrollmentDetails->child_id   = $response['PAYMENTREQUEST_0_DESC'];
          $enrollmentDetails->amount_paid = 0;
          $enrollmentDetails->payment_status = "failed";
          $enrollmentDetails->is_subscribed   = 0;
          $enrollmentDetails->payer_id = $paymentDetails->payer_id;

        if(!empty($enrollmentDetails)){
         $enrollmentDetails->save();
         $class_name = ClassDetails::where('id',$class_id)->value('class_title');
         $student_email = User::where('id',$student_id)->value('email');
         $student_name = User::where('id',$student_id)->value('name');
         $this->enrollmentFailedNotification($student_id,$class_name,$student_email,$student_name);
         
         $custom_message = "Class Enrollment Failed!";
         
         return response()->view('/payment-cancel', compact('custom_message','UserRole'), 200)->header("Refresh", "2;url=/view-classes");  
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

  
  public function paymentSuccess(Request $request)
  {
    try{
        $paypalModule = new ExpressCheckout;
        $response = $paypalModule->getExpressCheckoutDetails($request->token);
       
        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {

          $s_id = Auth::id();
          $class_id    =  $response['L_NAME0'];
          $teacher_id  =  $response['L_DESC0'];
          $amount_paid =  $response['L_AMT0'];
          $student_id  =  $s_id;
          $UserRole    =  UserRole::GetUserRole($s_id);
          
          $paymentDetails = new PaymentDetails;
          $paymentDetails->class_id     = $class_id;
          $paymentDetails->paid_by      = $student_id;
          $paymentDetails->amount_paid  = $amount_paid; 
          $paymentDetails->payer_id     = $response['PAYERID'];
          $paymentDetails->payer_status = $response['PAYERSTATUS']; 
          $paymentDetails->payer_paypal_acount_id = $response['PAYMENTREQUEST_0_SELLERPAYPALACCOUNTID']; 
          $paymentDetails->token    = $response['TOKEN']; 
          $paymentDetails->status   = $response['ACK'];
          $paymentDetails->payment_response   = json_encode($response);
          $paymentDetails->payment_method = "paypal";

        if (!empty($paymentDetails)) {
          $paymentDetails->save();
        }else{
            return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
        } 

        if($UserRole == 3 || $UserRole == 2) {
          $enrollmentDetails = new EnrollmentDetails;
          $enrollmentDetails->class_id = $class_id;
          $enrollmentDetails->teacher_id = $teacher_id;
          $enrollmentDetails->student_id = $student_id;
          $enrollmentDetails->amount_paid = $amount_paid;
          $enrollmentDetails->payment_status = "success";
          $enrollmentDetails->payer_id = $response['PAYERID'];

        if(!empty($enrollmentDetails)){
         $enrollmentDetails->save();
         $id = Auth::id();
         $credit_point = User::find( $id );
         $credit_point->credit_points += 10;
         $credit_point->save();

         //$classes = (new ClassDetails)->getStudentClass($s_id);
         //$teachers = (new ClassDetails)->getStudentClassTeachers($s_id);
         //return view('manage-classes/view-classes',compact('custom_message','UserRole','classes','teachers'));
         if($UserRole == 2) {
            $custom_message = "You are Enrolled SuccessFully..!(Switch to Student to view your Enrolled Classes)";
          }elseif($UserRole == 3){
            $custom_message = "You are Enrolled SuccessFully!";
          }
          $class_name = ClassDetails::where('id',$class_id)->value('class_title');
          $student_email = User::where('id',$student_id)->value('email');
          $student_name = User::where('id',$student_id)->value('name');
          $this->enrollmentSuccessNotification($student_id,$class_name,$student_email,$student_name); 
          //for dashboard notification : Admin
          $admin_id = UserRole::where('user_type',1)->value('ur_id');
          $admin_email = User::where('id',$admin_id)->value('email');
          $this->enrollmentNotificationAdmin($admin_id,$admin_email,$student_name,$class_name);
          //Teacher notification
          $teacher_email = User::where('id',$teacher_id)->value('email');
          $this->enrollmentNotificationTeacher($teacher_id,$teacher_email,$student_name,$class_name);  
          $checkout_flag = "not-free";
         return response()->view('/payment-success', compact('custom_message','checkout_flag','UserRole'), 200)->header("Refresh", "2;url=/view-classes");
        }else {
          return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
           }
       }elseif($UserRole == 4) {
          $enrollmentDetails = new EnrollmentDetails;
          $enrollmentDetails->class_id   = $class_id;
          $enrollmentDetails->teacher_id = $teacher_id;
          $enrollmentDetails->student_id = $student_id;
          $enrollmentDetails->child_id   = $response['PAYMENTREQUEST_0_DESC'];
          $enrollmentDetails->amount_paid = $amount_paid;
          $enrollmentDetails->payment_status = "success";
          $enrollmentDetails->payer_id = $response['PAYERID'];

        if(!empty($enrollmentDetails)){
         $enrollmentDetails->save();
         $id = Auth::id();
         $credit_point = User::find( $id );
         $credit_point->credit_points += 10;
         $credit_point->save();

         //$classes = (new ClassDetails)->getStudentClass($s_id);
         //$teachers = (new ClassDetails)->getStudentClassTeachers($s_id);
         //$childrens = ChildrenManagement::where('parent_id',$id)->get();
         $custom_message = "You are Enrolled SuccessFully!";

          $class_name = ClassDetails::where('id',$class_id)->value('class_title');
          $student_email = User::where('id',$student_id)->value('email');
          $student_name = User::where('id',$student_id)->value('name');
          $this->enrollmentSuccessNotification($student_id,$class_name,$student_email,$student_name);
          //for dashboard notification : Admin
          $admin_id = UserRole::where('user_type',1)->value('ur_id');
          $admin_email = User::where('id',$admin_id)->value('email');
          $this->enrollmentNotificationAdmin($admin_id,$admin_email,$student_name,$class_name);
          //Teacher notification
          $teacher_email = User::where('id',$teacher_id)->value('email');
          $this->enrollmentNotificationTeacher($teacher_id,$teacher_email,$student_name,$class_name); 
          $checkout_flag = "not-free";
         return response()->view('/payment-success', compact('custom_message','UserRole','checkout_flag'), 200)->header("Refresh", "2;url=/view-classes");  
        }else {
        return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
         }
       }else {
          $custom_message = "Please Login As a Student(If you do not have account then Register as a Student to Enroll Any Class.)";
          return view('auth.login',compact('custom_message'));
        }
      }else{
          return redirect()->back()->with('message', 'Something went wrong,Please try again later!!');
        }
      }catch (Exception $e) {
      return response()->json([
        'message' => 'Server Error!'
      ], 500);
    }
  }

  public function paymentCompleted()
  {
    return view('payment-success');
  }

  public function paymentCancelView()
  {
    return view('payment-cancel');
  }

  public function manageTax()
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);

        if ($UserRole == '1') {
          $tax_data = TaxDetails::get();
        return view('payouts/tax-details',compact('tax_data'));
        }else {
          return view('auth.login')->with('message', 'Please Login First');
        }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function updateTax(Request $request)
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);

        if ($UserRole == '1') {
          $validatedData = $request->validate([
              'ats_tax' => 'required',
              'service_fees' => 'required'
            ],
            [
              'ats_tax.required' => 'Please Enter ATS Tax',
              'service_fees.required' => 'Please Enter Service Fees'
            ]);

          if ($validatedData == true) {
            $id = $request->ttd_id;
            $tax_data = TaxDetails::find($id);
            $tax_data->ats_tax = $request->ats_tax; 
            $tax_data->service_fees = $request->service_fees;  

            if (!empty($tax_data)) {
              $tax_data->save();
              return redirect()->back()->with('message', 'Tax Details Updated Successfully!!');
            }else{
              return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
            }  
          }else{
            return redirect()->back()->with('message', 'Data Not Valid!!');
          }
        }else {
          return view('auth.login')->with('message', 'You are not authorized to access this page!');
        }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

//Notificatons:
    public function enrollmentFailedNotification($student_id,$class_name,$student_email,$student_name){

      $mailData = [
          'body' => 'Hello '.$student_name.''.', Class Enrollment for Class : '.$class_name.''.' has been failed',
          'thanks' => 'Thank you',
          'mailText' => 'Check out Details',
          'mailUrl' => url('/view-classes'),
          'mail_id' => 007
      ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Enrollment Failed";
          $addNotification->not_to =  $student_id;
          $addNotification->tn_user_type =  3;
          $addNotification->not_url =  '/view-classes';
          $addNotification->not_details = 'Class Enrollment for Class :'.$class_name.''.' has been failed';
          $addNotification->save();
        
        Notification::route('mail' , $student_email)->notify(new EnrollmentNotification($mailData)); 
    }

    public function enrollmentSuccessNotification($student_id,$class_name,$student_email,$student_name){

      $mailData = [
          'body' => 'Hello '.$student_name.''.', You have Successfully Enrolled for Class : '.$class_name,
          'thanks' => 'Thank you',
          'mailText' => 'Check out Details',
          'mailUrl' => url('/view-classes'),
          'mail_id' => 007
      ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Enrollment Success";
          $addNotification->not_to =  $student_id;
          $addNotification->tn_user_type =  3;
          $addNotification->not_url =  '/view-classes';
          $addNotification->not_details = 'Class Enrollment for Class :'.$class_name.''.' has been Successfully completed';
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