<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\ClassDetails;
use App\Models\Categories;
use App\Models\ScheduleDetails;
use App\Models\User;
use App\Models\StudyMaterials;
use App\Models\LiveclassDetails;
use Validator;
use Session;
use DB;

class ManageClassesAPI extends Controller
{
  public function teacherClass(Request $request) 
  {
    try
    {
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            if (User::getUserType($token) == '2') 
            {
              $id = $token;
              $classes = (new ClassDetails)->getClasses($id);
            if(!empty($classes)) 
            {
              return response()->json([
                'message' => 'Class Data Fetched Successfully!',
                'data' => $classes
                ], 200); 
            }else{
              return json_encode(array("status" => 300, "message" => 'Data Not Found!.'));
            }
            }else{
            return json_encode(array("status" => 300, "message" => 'Not Authorized.'));
            }
          }else{
            return json_encode(array("status" => 300, "message" => 'Not Authenticated.'));
          }
      }else{
        return json_encode(array("status" => 300, "message" => 'Token cannot be empty.'));
      }
    }catch (Exception $e) {
       return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function allClasses()
  {
    try{
      $classes = (new ClassDetails)->getApprovedClasses();
      $categories = (new Categories)->getCategories();

      if (!empty($classes) && !empty($categories)) {
        return response()->json([
            'message' => 'Classes & Categories Fetched Successfully!',
            'class_data' => $classes,
            'categories' => $categories
             ], 200);
      }else{
        return json_encode(array("status" => 300, "message" => 'Data not Found!'));
      }
    }catch (Exception $e) {
      return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function classData(Request $request)
  {
    try{
       $validator = Validator::make($request->all(), [
          'id' => 'required',
          ]);

          if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
          }
            if ($validator == true){

              $id = $request->id;
              if (ClassDetails::where('id', $id)->exists()) {

              $classes = (new ClassDetails)->getSingleClass($id);
              $rating = DB::table('tbl_feedbacks')->where('tbl_feedbacks.class_id',$id)->avg('tbl_feedbacks.rating');
              $overall_rating = ceil($rating);

              }else{
                return json_encode(array("status" => 300, "message" => 'Invalid Class ID!'));
              }
              if (!empty($classes)) {
                return response()->json([
                    'message' => 'Class Details Fetched Successfully!',
                    'data' => $classes,
                    'overall_rating' => $overall_rating
                     ], 200);
              }else{
                return json_encode(array("status" => 300, "message" => 'Class Data not Found!'));
              }
            }else{
                return json_encode(array("status" => 300, "message" => 'Something Went Wrong!'));
                }
    }catch (Exception $e){
      return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }

  public function addClass(Request $request) 
  {
    try{
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            if (User::getUserType($token) == '2') 
            {
              $validator = Validator::make($request->all(), 
              [
              'class_title' => 'required',
              'category' => 'required',
              'max_attendees' =>'required',
              'price' => 'required',
              'live_date' => 'required',
              'class_desc' => 'required',
              'learnings' => 'required',
              'skills_gain' => 'required',
              'image_path' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
              'status' =>'required'
              ],
              [
              'class_title.required' => 'Class Title is Required',
              'category.required' => 'Please Select Category',
              'price.required' => 'Please Enter Price For the Class',
              'max_attendees.required' => 'Please Enter Maximum Attendees to attend the Class',
              'live_date.required' => 'Please Select Live Date',
              'class_desc.required' => 'Please Enter Description for the Class',
              'learnings.required' => 'Please Enter Learnings for the class',
              'skills_gain.required' => 'Please Enter what skills student will gain',
              'status.required' =>'Please Provide Status'
              ]);

              if($validator->fails()){
                  return response()->json($validator->errors()->toJson(), 400);
              }
              if ($validator == true){
                $id = $token;
                
                $classdetails = new ClassDetails;
                $classdetails->class_title = $request->class_title;
                $classdetails->category = $request->category;
                $classdetails->class_desc = $request->class_desc;
                $classdetails->price = $request->price;
                $classdetails->max_attendees = $request->max_attendees;
                $classdetails->live_date = $request->live_date;
                $classdetails->learnings = $request->learnings;
                $classdetails->skills_gain = $request->skills_gain;
                $classdetails->resources = $request->resources;
                $classdetails->prerequisites = $request->prerequisites;
                $classdetails->faq = $request->faq;
                $classdetails->pf_status = isset($request->pf_status) ?  $request->pf_status   : 0;
                $classdetails->assessment_status = isset($request->assessment_status) ?  $request->assessment_status   : 0;
                $classdetails->keywords = isset($request->keywords) ?  $request->keywords   : NULL;
                $classdetails->status = $request->status;

              if(!empty($request->image_path)){
                $imageName = time().'.'.$request->image_path->extension();  
                $request->image_path->move(public_path('assets/img/classes/'), $imageName);
                $classdetails->image_path = 'assets/img/classes/' . $imageName;
              }
              if(!empty($request->video_path)){
                $youtube_url = $request->video_path;
                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $youtube_url, $match);
                $youtube_id = $match[1];
                $classdetails->video_path  = $youtube_id;
              }
                
                $classdetails->created_by = $id;

                if (!empty($classdetails)){
                  $classdetails->save();
                  return response()->json([
                            'message' => 'Class Added Successfully!',
                            'data' => $classdetails
                          ], 201);
                  }else{
                    return json_encode(array("status" => 300, "message" => 'Class Data not Found!'));
                    }
              }else{
                  return json_encode(array("status" => 300, "message" => 'Something Went Wrong!'));
                  }
            }else{
                return json_encode(array("status" => 300, "message" => 'Not Authenticated.'));
                }
          }else{
                return json_encode(array("status" => 300, "message" => 'Not Authorized.'));
                }
      }else{
          return json_encode(array("status" => 300, "message" => 'Token Cannot be Empty.'));
          }
    }catch (Exception $e){
      return response()->json([
        'message' => 'Server Error!'
      ], 500);
    }
  }

  public function updateClass(Request $request) 
  {
    try{
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            if (User::getUserType($token) == '2') 
            {
              $validator = Validator::make($request->all(), [
              'id' =>'required'
              ]);
              $id = $request->id;
              
              if (ClassDetails::where('id', $id)->exists()) {
              $inputs = $request->all();
             
              if (isset($request->video_path)){
                $youtube_url = $request->video_path;
                preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $youtube_url, $match);
                $youtube_id = $match[1];
                $inputs['video_path']  = $youtube_id;
              }
              if ($request->hasfile('image_path')) {
                $imageName = time().'.'.$request->image_path->extension();  
                $request->image_path->move(public_path('assets/img/classes/'), $imageName);
                $inputs['image_path'] = 'assets/img/classes/' . $imageName;
              }
            }else{
              return json_encode(array("status" => 300, "message" => 'Invalid Class ID!'));
            }
                
              if($validator->fails()){
                  return response()->json($validator->errors()->toJson(), 400);
              }
              if ($validator == true){
                $course_details = ClassDetails::where('id', $id)->where('created_by', $token )->update($inputs);
                if (!empty($course_details)){
                  return response()->json([
                            'message' => 'Class Details Updated Successfully!',
                            'data' => $inputs
                          ], 201);
                }else{
                  return json_encode(array("status" => 300, "message" => 'Updation Failed!'));
                  }
              }else{
                  return json_encode(array("status" => 300, "message" => 'Something Went Wrong!'));
                  }
            }else{
                return json_encode(array("status" => 300, "message" => 'Not Authenticated.'));
                }
          }else{
                return json_encode(array("status" => 300, "message" => 'Not Authorized.'));
                }
      }else{
          return json_encode(array("status" => 300, "message" => 'Token Cannot be Empty.'));
          }

    }catch (Exception $e){
      return response()->json([
        'message' => 'Server Error!'
      ], 500);
    }
  }

  public function deleteClass(Request $request) 
  {
    try{
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            if (User::getUserType($token) == '2') 
            {
              $validator = Validator::make($request->all(), [
              'id' => 'required',
              ]);

              if($validator->fails()){
                  return response()->json($validator->errors()->toJson(), 400);
              }
             if ($validator == true) 
             {
                   $id = $request->id;

                   $classdetails = ClassDetails::find($id);

                if (!empty($classdetails) && ClassDetails::where('id', $id)->exists()){
                   $classdetails->delete();
                   return response()->json([
                      'message' => 'Class Deleted Successfully!',
                          ], 201);
               }else{
                return json_encode(array("status" => 300, "message" => 'Invalid ID!')); 
                  }
              }else{
              return json_encode(array("status" => 300, "message" => 'Something Went Wrong!'));
                }
            }else{
              return json_encode(array("status" => 300, "message" => 'Not Authenticated.'));
              }
          }else{
             return json_encode(array("status" => 300, "message" => 'Not Authorized.'));
          }
        }else{
             return json_encode(array("status" => 300, "message" => 'Token Cannot be Empty.'));
            }
      }catch (Exception $e) {
         return response()->json([
           'message' => 'Server Error!'
              ], 500);
      }
  }

  public function allCategories()
  {
    try{
      $categories = (new Categories)->getCategories();

      if (!empty($categories)) {
        return response()->json([
            'message' => 'All Categories Listed Successfully!',
            'data' => $categories
             ], 200);
      }else{
        return json_encode(array("status" => 300, "message" => 'Categories not Found!'));
        }
      }catch (Exception $e) {
         return response()->json([
           'message' => 'Server Error!'
              ], 500);
      }
  }

//Class Links
  public function classLinks(Request $request)
  {
    try{
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            if (User::getUserType($token) == '2' || User::getUserType($token) == '3') 
            {
              $validator = Validator::make($request->all(), [
              'class_id' => 'required',
              ],[
                'class_id.required' => 'Please Enter Class ID'
              ]);

              if($validator->fails()){
                return response()->json($validator->errors()->toJson(), 400);
              }
                if ($validator == true){

                  $id = $request->class_id;
                  if (ClassDetails::where('id', $id)->exists()) {

                  $class_links = (new LiveclassDetails)->getClassLinks($id);

                  }else{
                    return json_encode(array("status" => 300, "message" => 'Invalid Class ID!'));
                  }
                  if (!empty($class_links)) {
                    return response()->json([
                        'message' => 'Class Links Fetched Successfully!',
                        'data' => $class_links,
                         ], 200);
                  }else{
                    return json_encode(array("status" => 300, "message" => 'Class Data not Found!'));
                  }
                }else{
                    return json_encode(array("status" => 300, "message" => 'Something Went Wrong!'));
                  }
              }else{
                   return json_encode(array("status" => 300, "message" => 'Not Authenticated!'));
                }
            }else{
               return json_encode(array("status" => 300, "message" => 'Not Authorized!'));
              }
          }else{
             return json_encode(array("status" => 300, "message" => 'Token Cannot be Empty.'));
            }
    }catch (Exception $e){
      return response()->json([
          'message' => 'Server Error!'
             ], 500);
      }
  }
  
//Study Materials Management
  public function uploadStudyMaterial(Request $request) 
  {
    try{
      if (trim($request->header('Authorization')) != null) 
      {
        $token = User::checkToken($request->header('Authorization'));
          if (!empty($token)) 
          {
            if (User::getUserType($token) == '2') 
            {
              $validator = Validator::make($request->all(), 
              [
              'class_id' => 'required',
              'topic' => 'required',
              'study_material' =>'required|max:10240',
              ],
              [
              'class_id.required' => 'Class ID is Required',
              'topic.required' => 'Please Enter Topic for the Study Material',
              'study_material.required' => 'Please Upload Material',
              ]);

              if($validator->fails()){
                  return response()->json($validator->errors()->toJson(), 400);
              }
              if ($validator == true){
                $id = $token;
                
                $materialdetails = new StudyMaterials;
                $materialdetails->class_id   = $request->class_id;
                $materialdetails->teacher_id = $id;
                $materialdetails->material_topic = $request->topic;

                if($request->file()) {
                $fileName = time().'_'.$request->study_material->getClientOriginalName();
                $filePath = $request->file('study_material')->move(public_path('/assets/uploads/study-materials/'), $fileName);
                $materialdetails->study_material = 'assets/uploads/study-materials/' . $fileName;
               }

                if (!empty($materialdetails)){
                  $materialdetails->save();
                  return response()->json([
                      'message' => 'Study Material Uploaded Successfully and sent to the Enrolled Students!',
                      'data' => $materialdetails
                      ], 201);
                  }else{
                    return json_encode(array("status" => 300, "message" => 'Class Data not Found!'));
                    }
              }else{
                  return json_encode(array("status" => 300, "message" => 'Something Went Wrong!'));
                  }
            }else{
                return json_encode(array("status" => 300, "message" => 'Not Authenticated.'));
                }
          }else{
                return json_encode(array("status" => 300, "message" => 'Not Authorized.'));
                }
      }else{
          return json_encode(array("status" => 300, "message" => 'Token Cannot be Empty.'));
          }
    }catch (Exception $e){
      return response()->json([
        'message' => 'Server Error!'
      ], 500);
    }
  }

  public function viewStudyMaterial(Request $request)
  {
   try{
        if (trim($request->header('Authorization')) != null) 
        {
          $token = User::checkToken($request->header('Authorization'));
            if (!empty($token)) 
            {
              if (User::getUserType($token) == '2') 
              {
                $study_material = (new StudyMaterials)->getTeacherStudyMaterials($token);
                  if (!empty($study_material)) {
                    return response()->json([
                        'message' => 'Study Materials Fetched Successfully!',
                        'data' => $study_material,
                        ], 200);
                    }else{
                      return json_encode(array("status" => 300, "message" => 'Study Material Data not Found!'));
                    }
              }elseif (User::getUserType($token) == '3'){
                $study_material = (new StudyMaterials)->getStudentStudyMaterials($token);
                  if (!empty($study_material)) {
                    return response()->json([
                        'message' => 'Study Materials Fetched Successfully!',
                        'data' => $study_material,
                        ], 200);
                    }else{
                      return json_encode(array("status" => 300, "message" => 'Study Material Data not Found!'));
                    }
              }else{
                  return json_encode(array("status" => 300, "message" => 'Not Authenticated!'));
                  }
            }else{
                return json_encode(array("status" => 300, "message" => 'Not Authorized!'));
                }
        }else{
            return json_encode(array("status" => 300, "message" => 'Token Cannot be Empty.'));
            }
      }catch (Exception $e){
         return response()->json([
            'message' => 'Server Error!'
             ], 500);
        }
  }

  public function activateStudyMaterial(Request $request)
  {
   try{
        if (trim($request->header('Authorization')) != null) 
        {
          $token = User::checkToken($request->header('Authorization'));
            if (!empty($token)) 
            {
              if (User::getUserType($token) == '2') 
              {
                $validator = Validator::make($request->all(), 
              [
              'material_id' => 'required'
              ],
              [
              'material_id.required' => 'Material ID is Required'
              ]);

              if($validator->fails()){
                  return response()->json($validator->errors()->toJson(), 400);
              }
              if ($validator == true){
                $id = $request->material_id;
                $materialdetails = StudyMaterials::find($id);
                $materialdetails->sm_status   = 1;
                  if (!empty($materialdetails)) {
                    $materialdetails->save();
                    return response()->json([
                        'message' => 'Study Material Activated Successfully!',
                        'data' => $materialdetails,
                        ], 200);
                    }else{
                      return json_encode(array("status" => 300, "message" => 'Class Data not Found!'));
                    }
                }else{
                  return json_encode(array("status" => 300, "message" => 'Something Went Wrong!'));
                  }
              }else{
                return json_encode(array("status" => 300, "message" => 'Not Authenticated!'));
                }
            }else{
                return json_encode(array("status" => 300, "message" => 'Not Authorized!'));
                }
        }else{
            return json_encode(array("status" => 300, "message" => 'Token Cannot be Empty.'));
            }
      }catch (Exception $e){
        return response()->json([
          'message' => 'Server Error!'
            ], 500);
        }
  } 

  public function deactivateStudyMaterial(Request $request)
  {
   try{
        if (trim($request->header('Authorization')) != null) 
        {
          $token = User::checkToken($request->header('Authorization'));
            if (!empty($token)) 
            {
              if (User::getUserType($token) == '2') 
              {
                $validator = Validator::make($request->all(), 
              [
              'material_id' => 'required'
              ],
              [
              'material_id.required' => 'Material ID is Required'
              ]);

              if($validator->fails()){
                  return response()->json($validator->errors()->toJson(), 400);
              }
              if ($validator == true){
                $id = $request->material_id;
                $materialdetails = StudyMaterials::find($id);
                $materialdetails->sm_status   = 0;
                  if (!empty($materialdetails)) {
                    $materialdetails->save();
                    return response()->json([
                        'message' => 'Study Material Deactivated Successfully!',
                        'data' => $materialdetails,
                        ], 200);
                    }else{
                      return json_encode(array("status" => 300, "message" => 'Class Data not Found!'));
                    }
                }else{
                  return json_encode(array("status" => 300, "message" => 'Something Went Wrong!'));
                  }
              }else{
                return json_encode(array("status" => 300, "message" => 'Not Authenticated!'));
                }
            }else{
                return json_encode(array("status" => 300, "message" => 'Not Authorized!'));
                }
        }else{
            return json_encode(array("status" => 300, "message" => 'Token Cannot be Empty.'));
            }
      }catch (Exception $e){
        return response()->json([
          'message' => 'Server Error!'
            ], 500);
        }
  }  


}
