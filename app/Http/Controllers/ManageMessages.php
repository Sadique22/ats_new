<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ClassDetails;
use App\Models\User;
use App\Models\UserRole;
use App\Models\MessageDetails;
use App\Models\ManageNotifications;
use App\Notifications\MessageNotification;
use Notification;
use Session;
use DB;

class ManageMessages extends Controller
{
  public function sendMessage(Request $request)
  {
   try{
       $sent_by = Auth::id();
       $UserRole = UserRole::GetUserRole($sent_by);
         if ($UserRole == '3' || $UserRole == '4' || $UserRole == '2') {
          $validatedData = $request->validate([
            'sent_to' => 'required',
            'message' => 'required | max:240',
            ],
            [
            'sent_to.required' => 'Please Select',
            'message.required' => 'Please Enter Message',
             ]);

          if ($validatedData == true && $UserRole == '3') {
            $messageData = new MessageDetails;
            $messageData->sent_to = $request->sent_to;
            $messageData->sent_by = $sent_by;
            $messageData->message = $request->message;
            $messageData->flag = "Student to Teacher";
            }
          elseif ($validatedData == true && $UserRole == '4') {
            $messageData = new MessageDetails;
            $messageData->sent_to = $request->sent_to;
            $messageData->sent_by = $sent_by;
            $messageData->message = $request->message;
            $messageData->flag = "Parent to Teacher";
            }
          elseif($validatedData == true && $UserRole == '2'){
            $messageData = new MessageDetails;
            $messageData->sent_to = $request->sent_to;
            $messageData->sent_by = $sent_by;
            $messageData->message = $request->message;
            $messageData->flag = "Teacher to Student";
            }
          else{
            return redirect()->back()->with('fault', 'Data Not Valid!!');
            }

           if (!empty($messageData)) {
            $messageData->save();

            $sent_to = User::where('id',$request->sent_to)->value('name');
            $this->sentMessageNotification($sent_to);
            
            $name = Auth::user()->name;
            $userData = User::get()->where('id',$request->sent_to);
            $u_id = User::where('id',$request->sent_to)->value('id');
            $this->receiveMessageNotificationforTS($userData,$name,$UserRole,$u_id);
            //return response()->json(['status'=>1, 'msg'=>'Message Sent Successfully to the Student!']);
            return redirect()->back()->with('message', 'Message Sent Successfully!!');
            }else{
              return redirect()->back()->with('fault', 'Something Went Wrong!!');
              }
         }else {
            return view('auth.login')->with('message', 'You are not authorized to access!');
          }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function receivedMessage()
  {
   try{
       $sent_to = Auth::id();
       $UserRole = UserRole::GetUserRole($sent_to);
         if ($UserRole == '3' || $UserRole == '4'|| $UserRole == '2' || $UserRole == '1') {
           
            $messageData = (new MessageDetails)->GetUserMessages($sent_to);
           if (!empty($messageData)) {
            return view('messages/received-message',compact('messageData','UserRole'));
            }else{
            return redirect()->back()->with('fault', 'No Data Available!!');
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

  public function sentMessage()
  {
   try{
       $sent_by = Auth::id();
       $UserRole = UserRole::GetUserRole($sent_by);
         if ($UserRole == '3' || $UserRole == '4'|| $UserRole == '2' || $UserRole == '1') {
           
            $messageData = (new MessageDetails)->GetUserSentMessages($sent_by);
           if (!empty($messageData)) {
            return view('messages/sent-message',compact('messageData','UserRole'));
            }else{
            return redirect()->back()->with('fault', 'No Data Available!!');
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

  public function allMessagesDetails()
  {
   try{
        $sent_to = Auth::id();
        $UserRole = UserRole::GetUserRole($sent_to);
        if ($UserRole == '1') {
          $messageData = (new MessageDetails)->GetAllMessagesInfo();
          if (!empty($messageData)) {
            return view('messages/all-messages',compact('messageData'));
          }else{
            return redirect()->back()->with('fault', 'No Data Available!!');
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

  public function SendMessageToAdmin(Request $request)
  {
    try{
       $sent_by = Auth::id();
       $UserRole = UserRole::GetUserRole($sent_by);
         if ($UserRole == '3' || $UserRole == '4' || $UserRole == '2') {
          $validatedData = $request->validate([
            'message' => 'required | max:240',
            ],
            [
            'message.required' => 'Please Enter Message',
             ]);

          if($validatedData == true){
            $messageData = new MessageDetails;
            $admin_id = UserRole::where('user_type','1')->value('ur_id');
            $messageData->sent_to = $admin_id;
            $messageData->sent_by = $sent_by;
            $messageData->message = $request->message;
            $messageData->flag = "User to Admin";
           }else{
            return redirect()->back()->with('fault', 'Data Not Valid!!');
            }

           if (!empty($messageData)) {
            $messageData->save();

            $sent_to = User::where('id',$admin_id)->value('name');
            $this->sentMessageNotification($sent_to);
            
            $name = Auth::user()->name;
            $userData = User::get()->where('id',$admin_id);
            $this->receiveMessageNotification($userData,$name);
            //for dashboard notification : Admin
            $admin_id = UserRole::where('user_type',1)->value('ur_id');
            $this->receiveMessageNotificationAdmin($admin_id,$name);

            return redirect()->back()->with('message', 'Message Sent Successfully to Admin!!');
            }else{
              return redirect()->back()->with('fault', 'Something Went Wrong!!');
              }
         }else {
            return view('auth.login')->with('message', 'You are not authorized to access!');
          }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

//Admin to Users
  public function SendMessageByAdmin(Request $request)
  {
    try{
       $sent_by = Auth::id();
       $UserRole = UserRole::GetUserRole($sent_by);
         if ($UserRole == '1') {
          $validatedData = $request->validate([
            'message' => 'required | max:240',
            ],
            [
            'message.required' => 'Please Enter Message',
             ]);

          if($validatedData == true){
            $messageData = new MessageDetails;
            $message = $request->message;
           
            $sent_to = $request->sent_to;
            $cnt = count($sent_to);
                $i=0;
                for ($i=0; $i < $cnt; $i++) { 
                 DB::table('tbl_messages')->insert(
                             array(
                           'sent_to' =>  $sent_to[$i],
                           'sent_by' =>  $sent_by,
                           'flag'    =>  'Admin to User',
                           'message' =>  $message,
                         )
                     );
               }
            return redirect()->back()->with('message', 'Message Sent Successfully to Selected Users!!');
           }else{
            return redirect()->back()->with('fault', 'Data Not Valid!!');
            }
         }else {
            return view('auth.login')->with('message', 'You are not authorized to access!');
          }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function SendMessagetoUser(Request $request)
  {
   try{
       $sent_by = Auth::id();
       $UserRole = UserRole::GetUserRole($sent_by);
         if ($UserRole == '1') {
          $validatedData = $request->validate([
            'sent_to' => 'required',
            'message' => 'required | max:240',
            ],
            [
            'message.required' => 'Please Enter Message',
             ]);

          if ($validatedData == true) {
            $messageData = new MessageDetails;
            $messageData->sent_to = $request->sent_to;
            $messageData->sent_by = $sent_by;
            $messageData->message = $request->message;
            $messageData->flag = "Admin to User";
            }else{
            return redirect()->back()->with('fault', 'Data Not Valid!!');
          }

           if (!empty($messageData)) {
            $messageData->save();

            $name = Auth::user()->name;
            $userData = User::get()->where('id',$request->sent_to);
            $this->receiveMessageNotification($userData,$name);

            return redirect()->back()->with('message', 'Message Sent Successfully!!');
            }else{
              return redirect()->back()->with('fault', 'Something Went Wrong!!');
              }
         }else {
            return view('auth.login')->with('message', 'You are not authorized to access!');
          }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }    
//Send Broadcast Message to Teachers
  public function SendBroadcastMessage(Request $request)
  {
    try{
       $sent_by = Auth::id();
       $UserRole = UserRole::GetUserRole($sent_by);
         if ($UserRole == '1') {
          $validatedData = $request->validate([
            'message' => 'required | max:240',
            ],
            [
            'message.required' => 'Please Enter Broadcast Message',
             ]);

          if($validatedData == true){
            $messageData = new MessageDetails;
            $message = $request->message;
           
            $teachers_id = UserRole::where('user_type',2)->pluck('ur_id');
            $cnt = count($teachers_id);
                $i=0;
                for ($i=0; $i < $cnt; $i++) { 
                 DB::table('tbl_messages')->insert(
                             array(
                           'sent_to' =>  $teachers_id[$i],
                           'sent_by' =>  $sent_by,
                           'flag'    =>  'Broadcast Message',
                           'message' =>  $message,
                         )
                     );
               }
            
            return redirect()->back()->with('message', 'Broadcast Message Sent Successfully!!');
            }else{
            return redirect()->back()->with('fault', 'Data Not Valid!!');
            }  
         }else {
            return view('auth.login')->with('message', 'You are not authorized to access!');
          }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }


//Email Notifications:
  public function receiveMessageNotification($userData,$name){
   
        $mailData = [
            'body' => 'You have received message from: ('.$name.')',
            'thanks' => 'Thank you',
            'mailText' => 'Check out messages',
            'mailUrl' => url('/received-message'),
            'mail_id' => 007
        ];
  
        Notification::send($userData, new MessageNotification($mailData));
    }

  public function sentMessageNotification($sent_to){
     $userData = Auth::user();
  
        $mailData = [
            'body' => 'You message has been sent successfully to: ('.$sent_to.')',
            'thanks' => 'Thank you',
            'mailText' => 'Check out messages',
            'mailUrl' => url('/received-messages'),
            'mail_id' => 007
        ];
  
        Notification::send($userData, new MessageNotification($mailData));
    } 

    public function receiveMessageNotificationforTS($userData,$name,$UserRole,$u_id){
   
        $mailData = [
            'body' => 'You have received message from: ('.$name.')',
            'thanks' => 'Thank you',
            'mailText' => 'Check out messages',
            'mailUrl' => url('/received-messages'),
            'mail_id' => 007
        ];
        if ($UserRole == 2) {
          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Received Message";
          $addNotification->not_to =  $u_id;
          $addNotification->tn_user_type =  3;
          $addNotification->not_url =  '/received-messages';
          $addNotification->not_details = 'You have received message from:' .$name;
          $addNotification->save();
        }elseif($UserRole == 3 || $UserRole == 4){
          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Received Message";
          $addNotification->not_to =  $u_id;
          $addNotification->tn_user_type =  2;
          $addNotification->not_url =  '/received-messages';
          $addNotification->not_details = 'You have received Message from:' .$name;
          $addNotification->save();
        }
  
        Notification::send($userData, new MessageNotification($mailData));
    }

    public function receiveMessageNotificationAdmin($admin_id,$name){

      $addNotification = new ManageNotifications;
      $addNotification->not_for = "Received Message";
      $addNotification->not_to =  $admin_id;
      $addNotification->tn_user_type =  1;
      $addNotification->not_url =  '/received-messages';
      $addNotification->not_details = 'You have received message from:' .$name;
      $addNotification->save();
    }   

}