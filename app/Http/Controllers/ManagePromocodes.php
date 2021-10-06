<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Promocodes;
use App\Models\ScheduleDetails;
use App\Models\ChildrenManagement;
use App\Models\ClassDetails;
use Session;
use DB;

class ManagePromocodes extends Controller
{

  public function viewPromocodes()
  {
   try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
        if ($UserRole == '1') {
		  $promo_data = Promocodes::get();
		  return view('promocodes/manage-promocodes',compact('promo_data'));
		}
	   }catch (Exception $e) {
         return response()->json([
           'message' => 'Server Error!'
              ], 500);
        }
  }

  public function addPromocode(Request $req)
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '1') {
          $validatedData = $req->validate([
              'promo_name' => 'required',
              'promo_code' => 'required',
              'promo_type' =>'required',
              'promo_expiry' => 'required',
              'promo_start' => 'required'
               ],
               [
	           'promo_name.required' => 'Please Enter Promocode Name',
	           'promo_code.required' => 'Please Enter Promocode',
	           'promo_type.required' => 'Please Select Promocode Type',
	           'promo_expiry.required' => 'Please Select Expiry Date/Time',
             'promo_start.required' => 'Please Select Start Date/Time'
	           ]);

          if ($validatedData == true) {        
            $promodetails = new Promocodes;
            $promodetails->promo_name        =    $req->promo_name;
            $promodetails->promo_code        =    $req->promo_code;
            $promodetails->promo_type        =    $req->promo_type;
            $promodetails->promo_offer       =    isset($req->promo_offer) ?  $req->promo_offer   : NULL;
            $promodetails->promo_start       =    $req->promo_start;
            $promodetails->promo_expiry      =    $req->promo_expiry;
            $promodetails->promo_status      =    1;
         
            if (!empty($promodetails)) {
            $promodetails->save();
            return redirect()->back()->with('message', 'Promo Code Created Successfully!!');
            }else{
            return redirect()->back()->with('message', 'No Data Available');
            }
          }else{
          	return redirect()->back()->with('message','Something Went Wrong,Please Try Again!!');
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

  public function promoStatus($status,$id)
  {
    try{
       $user_id = Auth::id();
       $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == '1') {
          if ($status == 1) {
          	$promodetails = Promocodes::find($id);
          	$promodetails->promo_status = 0;
          	$promodetails->save();
            return redirect()->back()->with('fault', 'Promo Code Deactivated!!');
           }else{
          	$promodetails = Promocodes::find($id);
          	$promodetails->promo_status = 1;
          	$promodetails->save();
            return redirect()->back()->with('message', 'Promo Code Activated Successfully!!');
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

//Check Promocode:
  public function checkPromocode(Request $req)
  {
    try{
        $user_id = Auth::id();
        $UserRole = UserRole::GetUserRole($user_id);
         if ($UserRole == 3 || $UserRole == 4 || $UserRole == 2) {
          $validatedData = $req->validate([
              'promo' => 'required'],
               ['promo.required' => 'Please Enter Promocode']);
          if ($validatedData == true) {        
            $promocode  =  $req->promo;
            $class_id = $req->class_id;
            $teacher_id = $req->teacher_id;
            $class_url = $req->class_url;
           
            date_default_timezone_set('Asia/Kolkata'); 
            $current_datetime = date('Y-m-d H:i:s');
              
            if (Promocodes::where('promo_code', $promocode)->exists()) {
              if (Promocodes::where('promo_code', $promocode)->where('promo_expiry', '>=', $current_datetime)->where('promo_status',1)->exists()) {
                if (Promocodes::where('promo_code', $promocode)->where('promo_start', '<=', $current_datetime)->where('promo_status',1)->exists()) {
                  
                $type = Promocodes::where('promo_code',$promocode)->value('promo_type');
                $offer = Promocodes::where('promo_code',$promocode)->value('promo_offer');

                $clientIP = request()->ip();
                //$clientIP = '223.236.37.175'; 
                $access_location = \Location::get($clientIP); 
                if($access_location->countryName == "India" && $access_location->countryCode == "IN"){
                $price = ClassDetails::where('id',$class_id)->value('price_inr');
                }else{
                $price = ClassDetails::where('id',$class_id)->value('price_usd'); 
                }

                if($type == "% discount"){
                 $disc = $price*$offer/100;
                 $calculate = $price - $disc;
                }elseif($type == "Flat discount"){
                  $calculate = $price - $offer;
                }else{
                  $calculate = 0;
                }

                $class_name  = ClassDetails::where('id',$class_id)->value('class_title');
                $live_date  = ClassDetails::where('id',$class_id)->value('live_date');
                $class_desc  = ClassDetails::where('id',$class_id)->value('class_desc');
                $teacher_name = User::where('id',$teacher_id)->value('name');
                $class_schedule = (new ScheduleDetails)->getClassSchedule($class_id);
                $user_name = Auth::user()->name;
                $user_email = Auth::user()->email;
                $user_contact = Auth::user()->contact;
                $message = "Promo code Applied Successfully!";
                $applied = "yes";
                $flag = "promo";
                if ($UserRole == '3' || $UserRole == '2') {
                  return view('checkout',compact('calculate','applied','class_url','access_location','user_contact','UserRole','message','class_name','live_date','class_desc','teacher_name','class_id','class_schedule','teacher_id','user_name','user_email','promocode','price','offer','type','flag'));
                }elseif($UserRole == '4'){
                  $childrens = ChildrenManagement::where('parent_id',$user_id)->get();
                  return view('checkout',compact('calculate','applied','class_url','access_location','UserRole','user_contact','message','class_name','live_date','class_desc','teacher_name','class_id','class_schedule','childrens','teacher_id','user_name','user_email','promocode','price','offer','type','flag'));
                }
              }else{
                return redirect()->back()->with('fault', 'Promo Code Not Valid!!');
              }
            }else{
               return redirect()->back()->with('fault', 'Promo Code Expired!!');
            }
            }else{
              return redirect()->back()->with('fault', 'Invalid Promocode!!');
             }
          }else{
            return redirect()->back()->with('message','Something Went Wrong,Please Try Again!!');
          }
        }else{
          $custom_message = "Please Login First(If you do not have account then Register as a Student to Enroll Any Class.)";
            return view('auth.login',compact('custom_message'));
        }
       }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

}