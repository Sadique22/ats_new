<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ClassDetails;
use App\Models\UserRole;
use App\Models\User;
use App\Models\PaymentDetails;
use App\Models\ChildrenManagement;
use App\Models\EnrollmentDetails;
use Session;
use DB;

class ManagePayouts extends Controller
{
	public function viewUserPayouts()
	{
	  try{
	       $user_id = Auth::id();
	       $UserRole = UserRole::GetUserRole($user_id);

	        if($UserRole == '3' || $UserRole == '4')
	        {
	        $payouts = (new PaymentDetails)->getUserPayouts($user_id);
		      return view('payouts/user-payouts',compact('payouts','UserRole'));
	        }else {
	          return view('auth.login')->with('message', 'Please Login First');
	        }
	      }catch (Exception $e) {
	          return response()->json([
	            'message' => 'Server Error!'
	        ], 500);
	    }
	}
//Admin
	public function userPayoutDetails($id)
	{
	  try{
	       $user_id = Auth::id();
	       $UserRole = UserRole::GetUserRole($user_id);

	        if($UserRole == '1')
	        {
	        $payouts = (new PaymentDetails)->getUserPayouts($id);
		      return view('payouts/payout-details',compact('payouts','UserRole'));
	        }else {
	          return view('auth.login')->with('message', 'Please Login First');
	        }
	      }catch (Exception $e) {
	          return response()->json([
	            'message' => 'Server Error!'
	        ], 500);
	    }
	}
}