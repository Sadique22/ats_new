<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManageClassesAPI;
use App\Http\Controllers\ManageScheduleAPI;
use App\Http\Controllers\ManageFeedbackAPI;
use App\Http\Controllers\ManageMessagesAPI;
use App\Http\Controllers\SearchFilterAPI;
use App\Http\Controllers\EnrollClassAPI;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);
    Route::post('/update-userprofile', [EnrollClassAPI::class, 'updateUserProfile']);
    /** Teachers **/
    Route::get('/teacher-class', [ManageClassesAPI::class, 'teacherClass']);
    /** Classes **/
    Route::get('/all-classes', [ManageClassesAPI::class, 'allClasses']); 
    Route::get('/class-data', [ManageClassesAPI::class, 'classData']);
    Route::post('/add-class', [ManageClassesAPI::class, 'addClass']); 
    Route::post('/update-class',[ManageClassesAPI::class, 'updateClass']);
    Route::post('/delete-class',[ManageClassesAPI::class, 'deleteClass']);
    /** Schedule **/
    Route::post('/add-schedule', [ManageScheduleAPI::class, 'postSchedule']);
    Route::get('/delete-schedule', [ManageScheduleAPI::class, 'deleteSchedule']);
    Route::get('/class-schedule', [ManageScheduleAPI::class, 'getClassSchedule']);
    Route::post('/update-schedule', [ManageScheduleAPI::class, 'updateClassSchedule']);
    /** Manage Study Materials **/
    Route::post('/upload-material', [ManageClassesAPI::class, 'uploadStudyMaterial']);
    Route::get('/view-studymaterial', [ManageClassesAPI::class, 'viewStudyMaterial']);
    Route::post('/activate-studymaterial', [ManageClassesAPI::class, 'activateStudyMaterial']);
    Route::post('/deactivate-studymaterial', [ManageClassesAPI::class, 'deactivateStudyMaterial']);
    /** Categories **/
    Route::get('/all-categories', [ManageClassesAPI::class, 'allCategories']);
    /** Manage Feedbacks **/ 
    Route::get('/teacher-feedbacks', [ManageFeedbackAPI::class, 'teacherFeedbacks']);
    Route::get('/student-feedbacks', [ManageFeedbackAPI::class, 'studentFeedbacks']);
    Route::get('/class-feedbacks', [ManageFeedbackAPI::class, 'classFeedbacks']);
    Route::post('/send-teacherfeedback', [ManageFeedbackAPI::class, 'postTeacherFeedback']);
    Route::post('/send-studentfeedback', [ManageFeedbackAPI::class, 'postStudentFeedback']);
    Route::post('/send-classfeedback', [ManageFeedbackAPI::class, 'postClassFeedback']);
    /** Manage Messages **/
    Route::post('/send-message', [ManageMessagesAPI::class, 'sendMessage']);
    Route::get('/received-message', [ManageMessagesAPI::class, 'receivedMessage']);
     /** Enrollment **/ 
    Route::get('/enrolled-students', [EnrollClassAPI::class, 'enrolledStudents']);
    Route::get('/class-enrolledstudents', [EnrollClassAPI::class, 'classEnrolledStudents']);
    /** Search Filter **/ 
    Route::get('/search/{data}', [SearchFilterAPI::class, 'search']);
    Route::get('/category-search/{id}', [SearchFilterAPI::class, 'categorySearch']);
    Route::get('/advance-search', [SearchFilterAPI::class, 'advanceSearch']);
     /** Class Links **/ 
    Route::get('/view-classlinks', [ManageClassesAPI::class, 'classLinks']);

});
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
