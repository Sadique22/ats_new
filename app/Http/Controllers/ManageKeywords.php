<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ClassDetails;
use App\Models\UserRole;
use Session;
use DB;

class ManageKeywords extends Controller
{
  public function allKeywords() 
  {
    try{
        $user_id = Auth::id();
        $UserRole = UserRole::GetUserRole($user_id);
        if($UserRole == '1')
        {
          $keywords = (new ClassDetails)->getKeywords();
          if (!empty($keywords)) {
            return view('keywords/manage-keywords',compact('keywords'));
          }
        }else{
          return view('auth.login')->with('message', 'You are not Authorized to access!');
        }
       }catch (Exception $e) {
        return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
    }


  public function editKeyword($id) 
  {
  try{
      $user_id = Auth::id();
      $UserRole = UserRole::GetUserRole($user_id);
      if ($UserRole == '1'){
      $keywords = (new ClassDetails)->GetKeywordData($id);
      return view('keywords/edit-keyword',compact('keywords'));
      }else {
        return view('auth.login')->with('message', 'You are not authorized to access!');
      } 
    }catch (Exception $e) {
          return response()->json([
            'message' => 'Server Error!'
               ], 500);
      }
  }

  public function updateKeyword(Request $req, $id) 
  {
    try{
        $user_id = Auth::id();
        $UserRole = UserRole::GetUserRole($user_id);
        if ($UserRole == '1'){
        $keyworddetails = ClassDetails::find($id);     
        $keyworddetails->keywords = $req->keywords;
        
        if (!empty($keyworddetails)) {
        $keyworddetails->save();
        return redirect('/all-keywords')->with('message', 'Keywords Updated Successfully!!');
        }else{
        return redirect()->back()->with('message', 'Something Went Wrong,Please Try Again!!');
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


}