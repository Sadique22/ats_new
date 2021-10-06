<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ClassDetails;
use App\Models\Categories;
use App\Models\FeedbackDetails;
use App\Models\EnrollmentDetails;
use App\Models\LiveclassDetails;
use App\Models\KeywordDetails;
use App\Models\ScheduleDetails;
use App\Models\StudyMaterials;
use App\Models\ClassRecordings;
use App\Models\ChildrenManagement;
use App\Models\TaxDetails;
use App\Models\Promocodes;
use App\Models\ManageNotifications;
use App\Models\User;
use App\Models\UserRole;
use Stevebauman\Location\Facades\Location;
use App\Notifications\ClassNotification;
use Carbon\Carbon;
use Notification;
use Session;
use DB;
use PDF;

class ManageClasses extends Controller
{
  public function index(Request $request) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);

        if ($UserRole == '2') {
        $classes = (new ClassDetails)->getClasses($user_id);
        return view('manage-classes/view-classes',compact('classes','UserRole'));
        }
        elseif($UserRole == '1')
        {
          $classes = (new ClassDetails)->getAllClasses();
          $approvedClasses = (new ClassDetails)->getApprovedClasses();
          return view('manage-classes/view-classes',compact('classes','UserRole','approvedClasses'));
        }
        elseif($UserRole == '3')
        {
          $classes = (new ClassDetails)->getStudentClass($user_id);
          $teachers = (new ClassDetails)->getStudentClassTeachers($user_id);
          return view('manage-classes/view-classes',compact('classes','teachers','UserRole'));
        }elseif($UserRole == '4')
        {
          $classes = (new ClassDetails)->getStudentClass($user_id);
          $teachers = (new ClassDetails)->getStudentClassTeachers($user_id);
          $childrens = ChildrenManagement::where('parent_id',$user_id)->get();
          return view('manage-classes/view-classes',compact('classes','childrens','teachers','UserRole'));
        }
        else {
          return view('auth.login')->with('message', 'Please Login First');
        }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function addClassView() 
  {
    try{
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '2') {
        $categories = (new Categories)->getCategories();
        $ats_tax = TaxDetails::value('ats_tax');
        $service_fees = TaxDetails::value('service_fees');
        return view('manage-classes/add-class',compact('categories','ats_tax','service_fees'));
        }else {
          return view('auth.login')->with('message', 'Please Login First');
        }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function insertData(Request $req) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '2') {
          $validatedData = $req->validate([
              'class_title' => 'required|max:50',
              'category' => 'required',
              'max_attendees' =>'required',
              'price_inr' => 'required',
              'price_usd' => 'required',
              'live_date' => 'required',
              'class_level' => 'required',
              'class_duration' => 'required',
              'class_desc' => 'required | max:800',
              'learnings' => 'required | max:800',
              'skills_gain' => 'required|max:500',
              'image_path' => 'image|mimes:jpeg,png,jpg|max:2048',
              ],
              [
            'class_title.required' => 'Class Title is Required',
            'category.required' => 'Please Select Category',
            'price_inr.required' => 'Please Enter Indian Price For the Class',
            'price_usd.required' => 'Please Enter USD Price For the Class',
            'class_duration.required' => 'Please Enter Class Duration',
            'max_attendees.required' => 'Please Enter Maximum Attendees to attend the Class',
            'live_date.required' => 'Please Select Live Date',
            'class_level.required' => 'Please Select Class Level',
            'class_desc.required' => 'Please Enter Description for the Class',
            'learnings.required' => 'Please Enter Learnings for the class',
            'skills_gain.required' => 'Please Enter what skills student will gain'
             ]);
//New Category
            if (isset($req->other_category_name)) {
             $new_category = $req->other_category_name;
             $categorydetails = new Categories;
             $categorydetails->c_name = $new_category;
             $categorydetails->c_status = 0;
             $categorydetails->save();
             $new_category_id = $categorydetails->c_id;
            }

          if ($validatedData == true) {
            $id = Auth::id();
        
            $classdetails = new ClassDetails;
            $classdetails->class_title = $req->class_title;
            if (isset($new_category)) {
             $classdetails->category = $new_category_id;
            }else{
             $classdetails->category = $req->category;
            }
            $classdetails->class_desc = $req->class_desc;
            $classdetails->class_duration = $req->class_duration;
            $classdetails->max_attendees = $req->max_attendees;
            $classdetails->live_date = $req->live_date;
            $classdetails->class_level = $req->class_level;
            $classdetails->learnings = $req->learnings;
            $classdetails->skills_gain = $req->skills_gain;
            $classdetails->resources = $req->resources;
            $classdetails->prerequisites = $req->prerequisites;
            $classdetails->faq = $req->faq;
            $classdetails->pf_status = isset($req->pf_status) ?  $req->pf_status   : 0;
            $classdetails->assessment_status = isset($req->assessment_status) ?  $req->assessment_status   : 0;
//Class Price Calculation 
            $ats_tax = TaxDetails::value('ats_tax');
            $service_fees = TaxDetails::value('service_fees');
            $class_price_inr = $req->price_inr;
            $tax_calculate_inr = ($ats_tax + $service_fees)/100;
            $remove_comma_inr = number_format ($class_price_inr - ($class_price_inr * $tax_calculate_inr),2); 
            $final_price_inr = str_replace(",", "", $remove_comma_inr);
            $classdetails->price_inr = $final_price_inr;

            $class_price_usd = $req->price_usd;
            $tax_calculate_usd = ($ats_tax + $service_fees)/100;
            $remove_comma_usd = number_format ($class_price_usd - ($class_price_usd * $tax_calculate_usd),2); 
            $final_price_usd = str_replace(",", "", $remove_comma_usd);
            $classdetails->price_usd = $final_price_usd;

//Image Upload
            if(!empty($req->image_path)){
            $imageName = time().'.'.$req->image_path->extension();  
            $req->image_path->move(public_path('assets/img/classes/'), $imageName);
            $classdetails->image_path = 'assets/img/classes/' . $imageName;
            }
//Video Upload                 
            if(!empty($req->video_path)){
              $youtube_url = $req->video_path;
              preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $youtube_url, $match);
              $youtube_id = $match[1];
             $classdetails->video_path    = $youtube_id;
            }
//Keywords
            if (!empty($req->keywords)) {
            $classdetails->keywords = $req->keywords;
            }

            $classdetails->created_by = $id;

            switch ($req->input('action')) {
             case 'submit':
              if (!empty($classdetails)) {
              $classdetails->save();
              $this->addNewClassNotification($id);
//Admin notification
              $admin_id = UserRole::where('user_type',1)->value('ur_id');
              $admin_email = User::where('id',$admin_id)->value('email');
              $teacher_name = Auth::user()->name;
              $class_name = $req->class_title;
              $this->addNewClassNotificationAdmin($admin_id,$admin_email,$teacher_name,$class_name);
//Posting Schedule
              if (!empty($req->schedule) && !empty($req->schedule_date && !empty($req->schedule_time))) {
                $schedule = $req->schedule;
                $schedule_date = $req->schedule_date;
                $schedule_time = $req->schedule_time;
                $class_id = $classdetails->id;
                $this->postSchedule($class_id,$schedule,$schedule_date,$schedule_time,$id);
              }
//Posting Credit Points
              $credit_point = User::find($id);
              $credit_point->credit_points += 10;
              $credit_point->save();
//Posting Schedule End Date
              $id = $classdetails->id;
              $schedule_data = ScheduleDetails::where('class_id',$id)->orderBy('s_id','desc')->value('schedule_date');
              $schedule_end_date = ClassDetails::find($id);
              $schedule_end_date->class_end_date = $schedule_data;
              $schedule_end_date->save();

              return redirect()->back()->with('message', 'Class Created Successfully and Sent for the Approval!!');

              }else{
              return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
              }
              break;
              case 'save':
              $classdetails->status = 3;
              if (!empty($classdetails)) {
              $classdetails->save();
              $this->saveClassNotification();
              if (!empty($req->schedule) && !empty($req->schedule_date && !empty($req->schedule_time))) {
                $schedule = $req->schedule;
                $schedule_date = $req->schedule_date;
                $schedule_time = $req->schedule_time;
                $class_id = $classdetails->id;
                $this->postSchedule($class_id,$schedule,$schedule_date,$schedule_time,$id);
              }
//Posting Schedule End Date
              $id = $classdetails->id;
              $schedule_data = ScheduleDetails::where('class_id',$id)->orderBy('s_id','desc')->value('schedule_date');
              $schedule_end_date = ClassDetails::find($id);
              $schedule_end_date->class_end_date = $schedule_data;
              $schedule_end_date->save();

              return redirect()->back()->with('message', 'Class Saved Successfully!!');
              }else{
              return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
              }
            break;
           }
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

  public function postSchedule($class_id,$schedule,$schedule_date,$schedule_time,$id)
  {
    $ScheduleDetails = new ScheduleDetails;
    $cnt = count($schedule);
    $i=0;
    for ($i=0; $i < $cnt; $i++) { 
     DB::table('tbl_schedule')->insert(
                 array(
               'teacher_id'     =>  $id,
               'class_id'       =>  $class_id,
               'schedule_desc'  =>  $schedule[$i],
               'schedule_date'  =>  $schedule_date[$i],
               'schedule_time'  =>  $schedule_time[$i]
             )
         );
      }
  }

 public function editClass($id) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1' || $UserRole == '2') {
       $classes = (new ClassDetails)->ClassesData($id);
       $ats_tax = TaxDetails::value('ats_tax');
       $service_fees = TaxDetails::value('service_fees');
       $categories = (new Categories)->getCategories();
       return view('manage-classes/edit-class',compact('classes','ats_tax','service_fees','categories'));
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

  public function updateClass(Request $req, $id) 
  {
    try{
        $user_id = Auth::id();
        $UserRole = UserRole::GetUserRole($user_id);
        if ($UserRole == '1' || $UserRole == '2') {
         $validatedData = $req->validate([
              'class_title' => 'required',
              'category' => 'required',
              'max_attendees' =>'required',
              'price_inr' => 'required',
              'price_usd' => 'required',
              'class_duration' => 'required',
              'live_date' => 'required',
              'class_level' => 'required',
              'class_desc' => 'required | max:800',
              'learnings' => 'required | max:800',
              'skills_gain' => 'required | max:500',
              'image_path' => 'image|mimes:jpeg,png,jpg|max:2048',
              ],
              [
            'class_title.required' => 'Class Title is Required',
            'category.required' => 'Please Select Category',
            'price_inr.required' => 'Please Enter Indian Price For the Class',
            'price_usd.required' => 'Please Enter USD Price For the Class',
            'class_duration.required' => 'Please Enter Class Duration',
            'max_attendees.required' => 'Please Enter Maximum Attendees to attend the Class',
            'live_date.required' => 'Please Select Live Date',
            'class_level.required' => 'Please Select Class Level',
            'class_desc.required' => 'Please Enter Description for the Class',
            'learnings.required' => 'Please Enter Learnings for the class',
            'skills_gain.required' => 'Please Enter what skills student will gain'
             ]);
//New Category
            if (isset($req->other_category_name)) {
             $new_category = $req->other_category_name;
             $categorydetails = new Categories;
             $categorydetails->c_name = $new_category;
             $categorydetails->c_status = 0;
             $categorydetails->save();
             $new_category_id = $categorydetails->c_id;
            }

        if ($validatedData == true) {  
          $classdetails = ClassDetails::find($id);
          if (isset($new_category)) {
            $classdetails->category = $new_category_id;
          }else{
            $classdetails->category = $req->category;
          }
          $classdetails->class_title = $req->class_title;
          $classdetails->class_desc = $req->class_desc;
          $classdetails->class_duration = $req->class_duration;
          $classdetails->max_attendees = $req->max_attendees;
          $classdetails->live_date = $req->live_date;
          $classdetails->class_level = $req->class_level;
          $classdetails->learnings = $req->learnings;
          $classdetails->skills_gain = $req->skills_gain;
          $classdetails->resources = $req->resources;
          $classdetails->prerequisites = $req->prerequisites;
          $classdetails->faq = $req->faq;
          $classdetails->pf_status = isset($req->pf_status) ? $req->pf_status   : 0;
          $classdetails->assessment_status = isset($req->assessment_status) ?  $req->assessment_status   : 0;
//Fetch Tax Details
          $ats_tax = TaxDetails::value('ats_tax');
          $service_fees = TaxDetails::value('service_fees');
//checking requested price INR
          $check_price_inr =  ClassDetails::where('id',$id)->value('price_inr');
          if ($req->price_inr == $check_price_inr) {
           $classdetails->price_inr = $check_price_inr;
          }else{
//Price Calculations INR
            $class_price_inr = $req->price_inr;
            $tax_calculate_inr = ($ats_tax + $service_fees)/100;
            $remove_comma_inr = number_format($class_price_inr - ($class_price_inr * $tax_calculate_inr),2); 
            $final_price_inr = str_replace(",", "", $remove_comma_inr);
            $classdetails->price_inr = $final_price_inr;
          }
//checking requested price USD
            $check_price_usd = ClassDetails::where('id',$id)->value('price_usd');
            if ($req->price_usd == $check_price_usd) {
             $classdetails->price_usd = $check_price_usd;
            }else{
//Price Calculations USD              
            $class_price_usd = $req->price_usd;
            $tax_calculate_usd = ($ats_tax + $service_fees)/100;
            $remove_comma_usd = number_format($class_price_usd - ($class_price_usd * $tax_calculate_usd),2); 
            $final_price_usd = str_replace(",", "", $remove_comma_usd);
            $classdetails->price_usd = $final_price_usd;
          }
//Image Upload
          if(!empty($req->image_path)){
            $imageName = time().'.'.$req->image_path->extension();  
            $req->image_path->move(public_path('assets/img/classes/'), $imageName);
            $classdetails->image_path = 'assets/img/classes/' . $imageName;
          }
//Video Upload
          if(!empty($req->video_path)){
            $youtube_url = $req->video_path;
             preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $youtube_url, $match);
            $youtube_id = $match[1];
            $classdetails->video_path    = $youtube_id;
          }
//Keywords          
          if (!empty($req->keywords)) {
            $classdetails->keywords = $req->keywords;
          }
          //$classdetails = ClassDetails::where('id', $id)->update($classdetails);
          }
          switch ($req->input('action')) 
          {
            case 'update':
            if (!empty($classdetails)) {
            $classdetails->save();
            $this->updateClassNotification($classdetails);

//Admin notification
            $admin_id = UserRole::where('user_type',1)->value('ur_id');
            $admin_email = User::where('id',$admin_id)->value('email');
            $teacher_name = Auth::user()->name;
            $class_name = $req->class_title;
            $this->updateClassNotificationAdmin($admin_id,$admin_email,$teacher_name,$class_name);

            return redirect()->back()->with('message', 'Class Details Updated Successfully!!');
            }else{
            return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
            }
            break;

            case 'save':
            $classdetails->status = 3;
            if (!empty($classdetails)) {
            $classdetails->save();
            $this->saveClassNotification();
            return redirect()->back()->with('message', 'Class Details Saved Successfully!!');
            }else{
            return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
            }
            break;

            case'approval':
            $classdetails->status = 0;
            if (!empty($classdetails)) {
            $classdetails->save();

            $id = Auth::id();
            $this->addNewClassNotification($id);

            return redirect()->back()->with('message', 'Class Sent for the Approval Successfully!!');
            }else{
            return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
            }
            break;
          
           return view('manage-classes/edit-class',compact('classes'));
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

  public function deleteClass($id) 
  {
    try{
        $user_id = Auth::id();
        $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '1' || $UserRole == '2') {
     
      $classdetails = ClassDetails::find($id);
      $classdetails->delete();
      return redirect()->back()->with('message', 'Class Deleted Successfully!!');
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

  public function approveClass($id) 
  {
    try{
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '1') {
     
      $classdetails = ClassDetails::find($id);
      $t_id = $classdetails['created_by'];
      $userData = User::get()->where('id',$t_id);
      $classdetails->status = 1;
      $classdetails->save();
      
      $this->approveClassNotification($userData,$classdetails,$t_id);
      $t_name = User::where('id',$t_id)->value('name');
      $all_reg_students = (new User)->getAllRegStudents();
      $this->addNewClassNotificationStudents($all_reg_students,$t_name,$classdetails);

      return redirect()->back()->with('message', 'Class Approved Successfully!!');
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

  public function declineClass($id) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1') {
     
        $classdetails = ClassDetails::find($id);
        $t_id = $classdetails['created_by'];
        $userData = User::get()->where('id',$t_id);
        $classdetails->status = 2;
        $classdetails->save();
        $this->declineClassNotification($userData,$classdetails,$t_id);
        //return response()->json(['status'=>1, 'msg'=>'Class Declined!']);
        return redirect()->back()->with('message', 'You have Declined the Class!!');
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

//View Class Data: Dashboard
  public function viewClassDetails($id) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);

        if ($UserRole == '1' || $UserRole == '3' || $UserRole == '4') {
        $feedbacks = (new FeedbackDetails)->getClassFeedback($id);
        $classes = (new ClassDetails)->getClassData($id);
        $schedules = (new ScheduleDetails)->getClassSchedule($id);
        $teacher_id = ClassDetails::where('id',$id)->value('created_by');
        $teacherfeedbacks = (new FeedbackDetails)->getApprovedTeacherFeedback($teacher_id);
        $rating = DB::table('tbl_feedbacks')->where('tbl_feedbacks.class_id',$id)->avg('tbl_feedbacks.rating');
        $count_rating = FeedbackDetails::where('class_id',$id)->where('f_status',1)->count('rating');
        $overall_rating = ceil($rating);
        return view('manage-classes/view-classdetails-dash',compact('classes','schedules','UserRole','feedbacks','overall_rating','count_rating','teacherfeedbacks'));
        }
        elseif ($UserRole == '2') {
        $feedbacks = (new FeedbackDetails)->getClassFeedback($id);
        $classes = (new ClassDetails)->getClassData($id);
        $schedules = (new ScheduleDetails)->getClassSchedule($id);
        $e_classes = (new EnrollmentDetails)->getClassEnrolledStudents($id);
        $teacher_id = ClassDetails::where('id',$id)->value('created_by');
        $teacherfeedbacks = (new FeedbackDetails)->getApprovedTeacherFeedback($teacher_id);
        $rating = DB::table('tbl_feedbacks')->where('tbl_feedbacks.class_id',$id)->avg('tbl_feedbacks.rating');
        $count_rating = FeedbackDetails::where('class_id',$id)->where('f_status',1)->count('rating');
        $overall_rating = ceil($rating);
        return view('manage-classes/view-classdetails-dash',compact('classes','schedules','UserRole','e_classes','feedbacks','overall_rating','count_rating','teacherfeedbacks'));
        }
        else {
          return view('auth.login')->with('message', 'Please Login First');
        }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }


//All Published Classes-Frontend
  public function ourClasses()
  {
    try{
       $classes = (new ClassDetails)->getApprovedClasses();
       $featured_class = (new ClassDetails)->getFeaturedClasses();
       $teachers = (new ClassDetails)->getTeachers();
       $categories = (new Categories)->getCategories();
       $clientIP = request()->ip();
       //$clientIP = '72.229.28.185';
       $access_location = \Location::get($clientIP);
       return view('manage-classes/classes',compact('classes','access_location','featured_class','categories','teachers'));
      }catch (Exception $e) {
         return response()->json([
           'message' => 'Server Error!'
              ], 500);
        }
  }

  public function classData($c_id,$t_id)
  {
    try{
         $user_id = Auth::id();
         $UserRole = UserRole::GetUserRole($user_id);

         $class_id = base64_decode($c_id);
         $teacher_id = base64_decode($t_id);
         
         $classes = (new ClassDetails)->getSingleClass($class_id);
         //$class_price = ClassDetails::where('id',$class_id)->value('price');
         $schedules = (new ScheduleDetails)->getClassSchedule($class_id);
         $feedbacks = (new FeedbackDetails)->getApprovedClassFeedback($class_id);
         $teacherfeedbacks = (new FeedbackDetails)->getApprovedTeacherFeedback($teacher_id);
         $rating = DB::table('tbl_feedbacks')->where('tbl_feedbacks.class_id',$class_id)->where('tbl_feedbacks.f_status',1)->avg('tbl_feedbacks.rating');
         $overall_rating = ceil($rating);
         $count_rating = FeedbackDetails::where('class_id',$class_id)->where('f_status',1)->count('rating');

         $clientIP = request()->ip();
         //$clientIP = '223.236.37.175';
         $access_location = \Location::get($clientIP); 
         
         if(EnrollmentDetails::where('class_id',$class_id)->where('student_id',$user_id)->where('payment_status','success')->where('is_subscribed',1)->exists()){
          $flag = "enrolled";
          }else{
          $flag = "not_enrolled";
          }

          $max_attendees = ClassDetails::where('id',$class_id)->value('max_attendees');
          $enrolled_students = EnrollmentDetails::where('class_id',$class_id)->count();
          if ($enrolled_students <= $max_attendees ) {
            $attendees_limit = "can_attend";
          }else{
            $attendees_limit = "cannot_attend";
          }

         return view('manage-classes/class-data',compact('classes','flag','attendees_limit','access_location','schedules','feedbacks','teacherfeedbacks','overall_rating','count_rating','UserRole'));
       }catch (Exception $e) {
          return response()->json([
            'message' => 'Server Error!'
              ], 500);
      }
  }

  public function sendClassLinkView($id)
  {
  try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
        if ($UserRole == '1') 
        {
          $links_data = (new liveclassdetails)->getPublishedLinks($id);
          $c_name = ClassDetails::where('id',$id)->value('class_title');
          $links_id = (new liveclassdetails)->pluck('for_schedule');
          $schedule_data = (new ScheduleDetails)->getScheduleDetails($id,$links_id);
          $class_recordings = (new ScheduleDetails)->getClassRecordingData($id,$links_id);
          $class_schedule = (new ScheduleDetails)->getClassSchedule($id);
            return view('manage-classes/manage-class-link',compact('schedule_data','class_schedule','class_recordings','links_data','UserRole','c_name','id'));
        }else{
           return view('auth.login')->with('message', 'You are not authorized to access!');
         }
     }catch (Exception $e) {
          return response()->json([
            'message' => 'Server Error!'
              ], 500);
      }
    }

  public function sendClassLink(Request $req)
  {
  try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
        if ($UserRole == '1') {
          $validatedData = $req->validate([
              'schedule_id' => 'required',
              'class_link' =>  'required'
               ],
               [
              'schedule_id.required'   => 'Please Select Schedule',
              'class_link.required' => 'Please Enter Class Link',
               ]);

          if ($validatedData == true) {
            $id = Auth::id();

            $schedule_date = ScheduleDetails::where('s_id',$req->schedule_id)->value('schedule_date');
            $schedule_time = ScheduleDetails::where('s_id',$req->schedule_id)->value('schedule_time');
            
            $liveclassdetails = new LiveclassDetails;
            $liveclassdetails->class_id   = $req->class_id;
            $liveclassdetails->for_schedule  = $req->schedule_id;
            $liveclassdetails->class_date = $schedule_date;
            $liveclassdetails->class_time = $schedule_time;
            $liveclassdetails->class_link = $req->class_link;
            $liveclassdetails->message    = $req->message;

        if (!empty($liveclassdetails)) {
        $liveclassdetails->save();

            $class_id = $req->class_id;
            $e_classes = (new EnrollmentDetails)->EnrolledStudentsNotification($class_id);
            $class_name = ClassDetails::where('id',$class_id)->value('class_title');
            $this->classLinkNotification($e_classes,$class_name,$class_id);

            $t_id = ClassDetails::where('id',$class_id)->value('created_by');
            $created_by = User::where('id',$t_id)->value('email');
            $this->classLinkNotificationTeacher($class_name,$class_id,$created_by,$t_id);
            
          return redirect()->back()->with('message', 'Live Class Details Sent Successfully!!');
        }else{
          return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
           }
        }
        }else{
           return redirect()->back()->with('message', 'You are not Authorized to access this!!');
          }
      }catch (Exception $e) {
          return response()->json([
            'message' => 'Server Error!'
              ], 500);
      }
    }

  public function viewClassLinks($id)
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '3' || $UserRole == '4' || $UserRole == '2' || $UserRole == '1') {
         $class_links = (new LiveclassDetails)->getClassLinks($id);
         if (!empty($class_links)) {
          return view('manage-classes/class-links',compact('class_links','UserRole','id'));
         }else{
          return redirect()->back()->with('message', 'No data available!!');
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

//Class Recordings
  public function sendClassRecording(Request $req)
  {
  try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
        if ($UserRole == '1') {
          $validatedData = $req->validate([
              'schedule_id' => 'required',
              'recording_link' => 'required'
               ],
               [
              'schedule_id.required'   => 'Please Select Schedule',
              'recording_link.required' => 'Please Enter Recording Link'
               ]);

          if ($validatedData == true) {
            $id = Auth::id();
            
            $recordinglinkdetails = new ClassRecordings;
            $recordinglinkdetails->class_id   = $req->class_id;
            $recordinglinkdetails->rec_for    = $req->schedule_id;
            $recordinglinkdetails->cr_link    = $req->recording_link;
           
          if (!empty($recordinglinkdetails)) {
          $recordinglinkdetails->save();

            $class_id = $req->class_id;
            $e_classes = (new EnrollmentDetails)->EnrolledStudentsNotification($class_id);
            $class_name = ClassDetails::where('id',$class_id)->value('class_title');
            $this->classRecordingNotification($e_classes,$class_name,$class_id);

            $t_id = ClassDetails::where('id',$class_id)->value('created_by');
            $created_by = User::where('id',$t_id)->value('email');
            $this->classRecordingNotificationTeacher($class_name,$class_id,$created_by,$t_id);
            
          return redirect()->back()->with('message', 'Class Recording Sent Successfully!!');
        }else{
          return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
           }
        }
        }else{
           return redirect()->back()->with('message', 'You are not Authorized to access this!!');
          }
      }catch (Exception $e) {
          return response()->json([
            'message' => 'Server Error!'
              ], 500);
      }
    }

  public function viewClassRecordings($id)
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '3' || $UserRole == '4' || $UserRole == '2' || $UserRole == '1') {
         $class_recordings = ClassRecordings::where('class_id',$id)->paginate(10);
         $class_name = ClassDetails::where('id',$id)->value('class_title');
         $t_id = ClassDetails::where('id',$id)->value('created_by');
         if (!empty($class_recordings)) {
          return view('manage-classes/class-recordings',compact('class_recordings','class_name','t_id','id','UserRole'));
         }else{
          return redirect()->back()->with('message', 'No data available!!');
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

//Study Materials Management
  public function viewStudyMaterials()
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '2') {
         $study_material = (new StudyMaterials)->getTeacherStudyMaterials($user_id);
         $approved_classes = (new ClassDetails)->getTeacherApprovedClasses($user_id);
         if (!empty($study_material)) {
          return view('manage-classes/study-materials',compact('study_material','approved_classes','UserRole'));
         }else{
          return redirect()->back()->with('message', 'No data available!!');
          }
         }elseif($UserRole == '3' || $UserRole == '4'){
         $study_material = (new StudyMaterials)->getStudentStudyMaterials($user_id);
         if (!empty($study_material)) {
          return view('manage-classes/study-materials',compact('study_material','UserRole'));
         }else{
          return redirect()->back()->with('message', 'No data available!!');
          }
         }elseif($UserRole == '1'){
         $study_material = (new StudyMaterials)->getAllStudyMaterials($user_id);
         if (!empty($study_material)) {
          return view('manage-classes/study-materials',compact('study_material','UserRole'));
         }else{
          return redirect()->back()->with('message', 'No data available!!');
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

  public function uploadStudyMaterial(Request $request)
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
        if ($UserRole == '2') {
          $validatedData = $request->validate([
              'class_id' => 'required',
              'topic' => 'required',
              'study_material' => 'required|max:10240',
               ],
               [
              'class_id.required'   => 'Class Name is Required',
              'topic.required' => 'Please Enter Topic for Study Material',
              'study_material.required' => 'Please Upload Material',
               ]);

          if ($validatedData == true) {
            $materialdetails = new StudyMaterials;
            $materialdetails->class_id   = $request->class_id;
            $materialdetails->teacher_id = $user_id;
            $materialdetails->material_topic = $request->topic;

           if($request->file()) {
            $fileName = time().'_'.$request->study_material->getClientOriginalName();
            $filePath = $request->file('study_material')->move(public_path('/assets/uploads/study-materials/'), $fileName);
            $materialdetails->study_material = 'assets/uploads/study-materials/' . $fileName;
           }
          
        if (!empty($materialdetails)) {
        $materialdetails->save();

          $e_classes = (new EnrollmentDetails)->EnrolledStudentsNotification($request->class_id);
          $this->studyMaterialNotification($e_classes);
          //for notification : Admin
          $class_name = ClassDetails::where('id',$request->class_id)->value('class_title');
          $teacher_name = Auth::user()->name;
          $admin_id = UserRole::where('user_type',1)->value('ur_id');
          $admin_email = User::where('id',$admin_id)->value('email');

          $this->studyMaterialNotificationAdmin($admin_id,$admin_email,$class_name,$teacher_name);
          return redirect()->back()->with('message', 'Study Material Uploaded and sent to Students Successfully!!');
        }else{
          return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
           }
        }
        }else{
           return redirect()->back()->with('message', 'You are not Authorized to access this!!');
          }
      }catch (Exception $e) {
          return response()->json([
            'message' => 'Server Error!'
              ], 500);
      }
  }

  public function studyMaterialStatus($status,$id)
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '2') {
          if ($status == 1) {
            $materialdetails = StudyMaterials::find($id);
            $materialdetails->sm_status = 0;
            $materialdetails->save();
            return redirect()->back()->with('fault', 'Study Material Deactivated!!');
           }else{
            $materialdetails = StudyMaterials::find($id);
            $materialdetails->sm_status = 1;
            $materialdetails->save();
            return redirect()->back()->with('message', 'Study Material Activated Successfully!!');
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

//Live Class Links
  public function editClassLink($id) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1'){
      $linkdata = LiveclassDetails::where('for_schedule',$id)->get();
      return view('manage-classes/edit-classlink',compact('linkdata'));
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

  public function updateClassLink(Request $req, $id) 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
       if ($UserRole == '1'){

      $validatedData = $req->validate([
        'class_link' => 'required',
         ]);

      if ($validatedData == true) {
             
        $classlink = liveclassdetails::find($id);
        $classlink->class_link = $req->class_link;
       
        if (!empty($classlink)) {
        $classlink->save();
        return redirect()->back()->with('message', 'Class Link Updated Successfully!!');
        }else{
        return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
          }
        }else{
      return redirect()->back()->with('message', 'Please Insert Proper Data!!');
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

//Unsubscribe Class
  public function unsubscribeClass($id,$c_id)
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '3' || $UserRole == '4') {
          
            $unsubscribeClasss = EnrollmentDetails::find($id);
            $unsubscribeClasss->is_subscribed = 0;
            $unsubscribeClasss->save();

            $user_name = User::where('id',$user_id)->value('name');
            $user_email = User::where('id',$user_id)->value('email');
            $class_name = ClassDetails::where('id',$c_id)->value('class_title');
            $this->classUnsubscribeNotification($user_name,$user_email,$class_name,$user_id);
            //for dashboard notification : Admin
            $admin_id = UserRole::where('user_type',1)->value('ur_id');
            $admin_email = User::where('id',$admin_id)->value('email');
            $this->classUnsubscribeNotificationAdmin($admin_id,$admin_email,$user_name,$class_name);

            return redirect()->back()->with('fault', 'Class Unsubscribed...!!(Contact with Admin for further inquiries)');

          }else {
            return view('auth.login')->with('message', 'You are not authorized to access!');
          }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

//Subscribe Class
  public function subscribeClass($id,$c_id,$s_id)
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '1') {
          
            $unsubscribeClasss = EnrollmentDetails::find($id);
            $unsubscribeClasss->is_subscribed = 1;
            $unsubscribeClasss->save();

            $user_name = User::where('id',$s_id)->value('name');
            $user_email = User::where('id',$s_id)->value('email');
            $class_name = ClassDetails::where('id',$c_id)->value('class_title');
            $this->classResubscribedbyAdmin($user_name,$user_email,$class_name,$s_id);

            return redirect()->back()->with('message', 'Class Subscribed Successfully...!!');

          }else {
            return view('auth.login')->with('message', 'You are not authorized to access!');
          }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }  

//Get Unsubscribed Classes : Admin
  public function getUnsubscribedClasses()
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '1') {
            $unsubscribedClasses = (new EnrollmentDetails)->getUnsubscribedClassesDetails();
            return view('manage-classes/unsubscribed-classes',compact('unsubscribedClasses','UserRole'));
          }elseif($UserRole == '3' || $UserRole == '4'){
            $unsubscribedClasses = (new EnrollmentDetails)->getUnsubscribedClassesStudent($user_id);
            return view('manage-classes/unsubscribed-classes',compact('unsubscribedClasses','UserRole'));
          }else {
            return view('auth.login')->with('message', 'You are not authorized to access!');
          }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }   

//Class Featured Status 
  public function classFeaturedStatus($status,$id)
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '1') {
          if ($status == 1) {
            $featuredstatus = ClassDetails::find($id);
            $featuredstatus->is_featured = 0;
            $featuredstatus->save();
            return redirect()->back()->with('fault', 'Class Unfeatured!!');
           }else{
            $featuredstatus = ClassDetails::find($id);
            $featuredstatus->is_featured = 1;
            $featuredstatus->save();
            return redirect()->back()->with('message', 'Class added to Featured Classes Successfully!!');
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

//Completed Classes:
public function completedClasses() 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);

        if ($UserRole == '2') {
        $classes = (new ClassDetails)->getCompletedClassesTeacher($user_id);
        return view('manage-classes/view-classes',compact('classes','UserRole'));
        }
        elseif($UserRole == '1')
        {
          $classes = (new ClassDetails)->getCompletedClassesAdmin();
          $approvedClasses = (new ClassDetails)->getApprovedClasses();
          return view('manage-classes/view-classes',compact('classes','UserRole','approvedClasses'));
        }
        elseif($UserRole == '3')
        {
          $classes = (new ClassDetails)->getCompletedClassesStudent($user_id);
          $teachers = (new ClassDetails)->getStudentClassTeachers($user_id);
          return view('manage-classes/view-classes',compact('classes','teachers','UserRole'));
        }
        elseif($UserRole == '4')
        {
          $classes = (new ClassDetails)->getCompletedClassesStudent($user_id);
          $teachers = (new ClassDetails)->getStudentClassTeachers($user_id);
          $childrens = ChildrenManagement::where('parent_id',$user_id)->get();
          return view('manage-classes/view-classes',compact('classes','childrens','teachers','UserRole'));
        }
        else {
          return view('auth.login')->with('message', 'Please Login First');
        }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

//Upcoming Classes:
public function upcomingClasses() 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);

        if ($UserRole == '2') {
        $classes = (new ClassDetails)->getUpcomingClassesTeacher($user_id);
        return view('manage-classes/view-classes',compact('classes','UserRole'));
        }
        elseif($UserRole == '1')
        {
          $classes = (new ClassDetails)->getAdminUpcomingClasses();
          $approvedClasses = (new ClassDetails)->getApprovedClasses();
          return view('manage-classes/view-classes',compact('classes','UserRole','approvedClasses'));
        }
        elseif($UserRole == '3')
        {
          $classes = (new ClassDetails)->getStudentUpcomingClasses($user_id);
          $teachers = (new ClassDetails)->getStudentClassTeachers($user_id);
          return view('manage-classes/view-classes',compact('classes','teachers','UserRole'));
        }
        elseif($UserRole == '4')
        {
          $classes = (new ClassDetails)->getStudentUpcomingClasses($user_id);
          $teachers = (new ClassDetails)->getStudentClassTeachers($user_id);
          $childrens = ChildrenManagement::where('parent_id',$user_id)->get();
          return view('manage-classes/view-classes',compact('classes','childrens','teachers','UserRole'));
        }
        else {
          return view('auth.login')->with('message', 'Please Login First');
        }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

//Approved Classes:
  public function approvedClasses() 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);

        if ($UserRole == '2') {
        $classes = (new ClassDetails)->getApprovedClassesTeacher($user_id);
        return view('manage-classes/view-classes',compact('classes','UserRole'));
        }
        elseif($UserRole == '1'){
        $classes = (new ClassDetails)->getApprovedClassesAdmin();
        $approvedClasses = (new ClassDetails)->getApprovedClasses();
        return view('manage-classes/view-classes',compact('classes','approvedClasses','UserRole'));
        }
        else {
          return view('auth.login')->with('message', 'Please Login First');
        }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

//Declined Classes:
  public function declinedClasses() 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);

        if ($UserRole == '2') {
        $classes = (new ClassDetails)->getDeclinedClassesTeacher($user_id);
        return view('manage-classes/view-classes',compact('classes','UserRole'));
        }
        elseif($UserRole == '1'){
        $classes = (new ClassDetails)->getDeclinedClassesAdmin();
        $approvedClasses = (new ClassDetails)->getApprovedClasses();
        return view('manage-classes/view-classes',compact('classes','approvedClasses','UserRole'));
        }
        else {
          return view('auth.login')->with('message', 'Please Login First');
        }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }
//Saved Classes:
  public function savedClasses() 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);

        if ($UserRole == '2') {
        $classes = (new ClassDetails)->getSavedClassesTeacher($user_id);
        return view('manage-classes/view-classes',compact('classes','UserRole'));
        }
        else {
          return view('auth.login')->with('message', 'Please Login First');
        }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  } 
//Pending Approval Classes: Admin
  public function pendingApprovalClasses() 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);

        if($UserRole == '1') {
        $classes = (new ClassDetails)->getPendingApprovalClassesAdmin($user_id);
        $approvedClasses = (new ClassDetails)->getApprovedClasses();
        return view('manage-classes/view-classes',compact('classes','approvedClasses','UserRole'));
        }elseif($UserRole == '2'){
        $classes = (new ClassDetails)->getPendingClassesTeacher($user_id);
        return view('manage-classes/view-classes',compact('classes','UserRole'));
        }
        else {
          return view('auth.login')->with('message', 'Please Login First');
        }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }
//Featured Classes: Admin
  public function FeaturedClasses() 
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);

        if ($UserRole == '1') {
        $classes = (new ClassDetails)->getFeaturedClassesAdmin($user_id);
        $approvedClasses = (new ClassDetails)->getApprovedClasses();
        return view('manage-classes/view-classes',compact('classes','approvedClasses','UserRole'));
        }
        else {
          return view('auth.login')->with('message', 'Please Login First');
        }
      }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }      

//Email Notifications:
    public function addNewClassNotification($id) {
        $userData = Auth::user();
  
        $mailData = [
            'body' => 'Your class has been created successfully and sent for the approval to admin.',
            'thanks' => 'Thank you',
            'mailText' => 'Check out classes',
            'mailUrl' => url('/view-classes'),
            'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Class Approval";
          $addNotification->not_to =  $id;
          $addNotification->tn_user_type =  2;
          $addNotification->not_url =  '/view-classes';
          $addNotification->not_details = 'Your class has been created successfully and sent for the approval to admin.';
          $addNotification->save();
  
        Notification::send($userData, new ClassNotification($mailData));
    }

    public function addNewClassNotificationStudents($all_reg_students,$t_name,$classdetails){
        $mailData = [
            'body' => 'New class ('.$classdetails->class_title.')'.' Has Been published by '.$t_name,
            'thanks' => 'Thank you',
            'mailText' => 'Check out Classes',
            'mailUrl' => url('/all-classes'),
            'mail_id' => 007
        ];

        foreach($all_reg_students as $userData){
          $addNotification = new ManageNotifications;
          $addNotification->not_for = "New Class";
          $addNotification->not_to = $userData->id;
          $addNotification->tn_user_type = 3;
          $addNotification->not_url = '/all-classes';
          $addNotification->not_details = 'New class ('.$classdetails->class_title.')'.' has been published by '.$t_name;
          $addNotification->save();
        }

        foreach($all_reg_students as $userData){
        Notification::route('mail' , $userData->email)->notify(new ClassNotification($mailData)); 
      }
    } 
 

    public function saveClassNotification(){
      $userData = Auth::user();
  
        $mailData = [
            'body' => 'Your class has been saved successfully, you can click on continue& submit button and after class designing you can send it for the approval to admin',
            'thanks' => 'Thank you',
            'mailText' => 'Check out classes',
            'mailUrl' => url('/view-classes'),
            'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Class Saved";
          $addNotification->not_to =  $userData->id;
          $addNotification->tn_user_type =  2;
          $addNotification->not_url =  '/view-classes';
          $addNotification->not_details = 'Your class has been saved successfully, you can click on continue& submit button and after class designing you can send it for the approval to admin';
          $addNotification->save();
  
        Notification::send($userData, new ClassNotification($mailData));
    }

    public function updateClassNotification($classdetails){
     $userData = Auth::user();
  
        $mailData = [
            'body' => 'Class ('.$classdetails->class_title.')'.' has been updated successfully',
            'thanks' => 'Thank you',
            'mailText' => 'Check out classes',
            'mailUrl' => url('/view-classes'),
            'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Class Update";
          $addNotification->not_to =  $userData->id;
          $addNotification->tn_user_type =  2;
          $addNotification->not_url =  '/view-classes';
          $addNotification->not_details = 'Class ('.$classdetails->class_title.')'.' Has Been Updated Successfully';
          $addNotification->save();

        Notification::send($userData, new ClassNotification($mailData));
    }

    public function declineClassNotification($userData,$classdetails,$t_id){
       $mailData = [
          'body' => 'Class ('.$classdetails->class_title.')'.' has been declined by the admin',
          'thanks' => 'Thank you',
          'mailText' => 'Check out classes',
          'mailUrl' => url('/view-classes'),
          'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Class Declined";
          $addNotification->not_to =  $t_id;
          $addNotification->tn_user_type =  2;
          $addNotification->not_url =  '/view-classes';
          $addNotification->not_details = 'Class ('.$classdetails->class_title.')'.' Has Been Declined By the Admin';
          $addNotification->save();
  
        Notification::send($userData, new ClassNotification($mailData));
    }

    public function approveClassNotification($userData,$classdetails,$t_id){
       $mailData = [
          'body' => 'Class ('.$classdetails->class_title.')'.' Has Been Approved Successfully by Admin',
          'thanks' => 'Thank you',
          'mailText' => 'Check out classes',
          'mailUrl' => url('/view-classes'),
          'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Class Approval";
          $addNotification->not_to =  $t_id;
          $addNotification->tn_user_type =  2;
          $addNotification->not_url =  '/view-classes';
          $addNotification->not_details = 'Class ('.$classdetails->class_title.')'.' Has Been Approved Successfully by Admin';
          $addNotification->save();

        Notification::send($userData, new ClassNotification($mailData));
    }

    public function studyMaterialNotification($e_classes){
      $name = Auth::user()->name;

        $mailData = [
            'body' => 'New study material has been uploaded by '.$name,
            'thanks' => 'Thank you',
            'mailText' => 'Check out Study Materials',
            'mailUrl' => url('/study-materials'),
            'mail_id' => 007
        ];

        foreach($e_classes as $userData){
          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Study Material";
          $addNotification->not_to = $userData->id;
          $addNotification->tn_user_type = 3;
          $addNotification->not_url = '/study-materials';
          $addNotification->not_details = 'New study material Has been uploaded by '.$name;
          $addNotification->save();
        }

        foreach($e_classes as $userData){
        Notification::route('mail' , $userData->email)->notify(new ClassNotification($mailData)); 
      }
    }

    public function studyMaterialNotificationAdmin($admin_id,$admin_email,$class_name,$teacher_name){
    $mailData = [
          'body' => 'Teacher '.$teacher_name.''.', has uploaded study material for class : '.$class_name,
          'thanks' => 'Thank you',
          'mailText' => 'Check out Details',
          'mailUrl' => url('/study-materials'),
          'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Study Material";
          $addNotification->not_to =  $admin_id;
          $addNotification->tn_user_type =  1;
          $addNotification->not_url =  '/study-materials';
          $addNotification->not_details = 'Teacher '.$teacher_name.''.', has uploaded study material for Class : '.$class_name;
          $addNotification->save();
        
        Notification::route('mail' , $admin_email)->notify(new ClassNotification($mailData)); 
    }

    public function classLinkNotification($e_classes,$class_name,$class_id){

        $mailData = [
            'body' => 'Admin has sent live class link for the class: '.$class_name,
            'thanks' => 'Thank you',
            'mailText' => 'Check out Details',
            'mailUrl' => url('/class-links/'.$class_id),
            'mail_id' => 007
        ];

        foreach($e_classes as $userData){
          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Class Link";
          $addNotification->not_to =  $userData->id;
          $addNotification->tn_user_type =  3;
          $addNotification->not_url =  '/class-links/'.$class_id;
          $addNotification->not_details = 'Admin has sent class link for the class: '.$class_name;
          $addNotification->save();
        }

        foreach($e_classes as $userData){
        Notification::route('mail' , $userData->email)->notify(new ClassNotification($mailData)); 
      }
     }

     public function classLinkNotificationTeacher($class_name,$class_id,$created_by,$t_id){

        $mailData = [
            'body' => 'Admin has sent live class link for your class: '.$class_name,
            'thanks' => 'Thank you',
            'mailText' => 'Check out Details',
            'mailUrl' => url('/class-links/'.$class_id),
            'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Class Link";
          $addNotification->not_to =  $t_id;
          $addNotification->tn_user_type =  2;
          $addNotification->not_url =  '/class-links/'.$class_id;
          $addNotification->not_details = 'Admin has sent Class link for the class: '.$class_name;
          $addNotification->save();
        
        Notification::route('mail' , $created_by)->notify(new ClassNotification($mailData)); 
     }

     public function classRecordingNotification($e_classes,$class_name,$class_id){

        $mailData = [
            'body' => 'Admin has sent class recording link for the class: '.$class_name,
            'thanks' => 'Thank you',
            'mailText' => 'Check out Details',
            'mailUrl' => url('/class-recordings/'.$class_id),
            'mail_id' => 007
        ];
        foreach($e_classes as $userData){
          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Class Recording";
          $addNotification->not_to =  $userData->id;
          $addNotification->tn_user_type =  3;
          $addNotification->not_url =  '/class-recordings/'.$class_id;
          $addNotification->not_details = 'Admin has sent class recording link for the class: '.$class_name;
          $addNotification->save();
        }
        foreach($e_classes as $userData){
        Notification::route('mail' , $userData->email)->notify(new ClassNotification($mailData)); 
      }
     }

     public function classRecordingNotificationTeacher($class_name,$class_id,$created_by,$t_id){

        $mailData = [
            'body' => 'Admin has sent class recording link for your class: '.$class_name,
            'thanks' => 'Thank you',
            'mailText' => 'Check out Details',
            'mailUrl' => url('/class-recordings/'.$class_id),
            'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Class Recording";
          $addNotification->not_to =  $t_id;
          $addNotification->tn_user_type =  2;
          $addNotification->not_url =  '/class-recordings/'.$class_id;
          $addNotification->not_details = 'Admin has sent Class Recording link for the class: '.$class_name;
          $addNotification->save();
        
        Notification::route('mail' , $created_by)->notify(new ClassNotification($mailData)); 
     }

     public function classResubscribedbyAdmin($user_name,$user_email,$class_name,$s_id){
       $mailData = [
          'body' => 'Hello '.$user_name.''.',You have been Re-subscribed for the class: ('.$class_name.') by Admin',
          'thanks' => 'Thank you',
          'mailText' => 'Check out classes',
          'mailUrl' => url('/view-classes'),
          'mail_id' => 007
        ];
         
          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Class Resubsciption";
          $addNotification->not_to =  $s_id;
          $addNotification->tn_user_type =  3;
          $addNotification->not_url =  '/view-classes';
          $addNotification->not_details = "You have been Re-subscribed by the Admin for the class: ".$class_name;
          $addNotification->save();

         Notification::route('mail' , $user_email)->notify(new ClassNotification($mailData)); 
    }

    public function classUnsubscribeNotification($user_name,$user_email,$class_name,$user_id){
       $mailData = [
          'body' => 'Hello '.$user_name.''.',You have unsubscribed the class: ('.$class_name.').To Resubscribe, contact to the Admin',
          'thanks' => 'Thank you',
          'mailText' => 'Check out classes',
          'mailUrl' => url('/view-classes'),
          'mail_id' => 007
        ];
         
          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Class Unsubscribe";
          $addNotification->not_to =  $user_id;
          $addNotification->tn_user_type =  3;
          $addNotification->not_url =  '/unsubscribed-classes';
          $addNotification->not_details = 'You have unsubscribed the class: ('.$class_name.').To Resubscribe, contact with the Admin';
          $addNotification->save();

         Notification::route('mail' , $user_email)->notify(new ClassNotification($mailData)); 
    }

    public function classUnsubscribeNotificationAdmin($admin_id,$admin_email,$user_name,$class_name){
    $mailData = [
          'body' => 'Student '.$user_name.''.', has unsubscribed the class : '.$class_name,
          'thanks' => 'Thank you',
          'mailText' => 'Check out Details',
          'mailUrl' => url('/unsubscribed-classes'),
          'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Class Unsubscribe";
          $addNotification->not_to =  $admin_id;
          $addNotification->tn_user_type =  1;
          $addNotification->not_url =  '/unsubscribed-classes';
          $addNotification->not_details = 'Student '.$user_name.''.', has Unsubscribed the Class : '.$class_name;
          $addNotification->save();
        
        Notification::route('mail' , $admin_email)->notify(new ClassNotification($mailData)); 
    }

    public function addNewClassNotificationAdmin($admin_id,$admin_email,$teacher_name,$class_name){
    $mailData = [
          'body' => $teacher_name. ' has sent sent class for approval : '.$class_name,
          'thanks' => 'Thank you',
          'mailText' => 'Check out Details',
          'mailUrl' => url('/view-classes'),
          'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "New Class Approval";
          $addNotification->not_to =  $admin_id;
          $addNotification->tn_user_type =  1;
          $addNotification->not_url =  '/view-classes';
          $addNotification->not_details = $teacher_name. 'has sent sent class for approval : '.$class_name;
          $addNotification->save();
        
        Notification::route('mail' , $admin_email)->notify(new ClassNotification($mailData)); 
    }

    public function updateClassNotificationAdmin($admin_id,$admin_email,$teacher_name,$class_name){
    $mailData = [
          'body' => $teacher_name. ' has updated his class data : '.$class_name,
          'thanks' => 'Thank you',
          'mailText' => 'Check out Details',
          'mailUrl' => url('/view-classes'),
          'mail_id' => 007
        ];

          $addNotification = new ManageNotifications;
          $addNotification->not_for = "Class Update";
          $addNotification->not_to =  $admin_id;
          $addNotification->tn_user_type =  1;
          $addNotification->not_url =  '/view-classes';
          $addNotification->not_details = $teacher_name. ' has updated his class data : '.$class_name;
          $addNotification->save();
        
        Notification::route('mail' , $admin_email)->notify(new ClassNotification($mailData)); 
    }


}
