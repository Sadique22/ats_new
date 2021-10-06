<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ManageClasses;
use Stevebauman\Location\Facades\Location;
use App\Http\Controllers\ManageUsers;
use App\Http\Controllers\Search;
use App\Http\Controllers\Payment;
use App\Http\Controllers\EnrollClass;
use App\Http\Controllers\AboutUs;
use App\Http\Controllers\Feedback;
use App\Http\Controllers\ManageCategories;
use App\Http\Controllers\ManageSchedule;
use App\Http\Controllers\ManageKeywords;
use App\Http\Controllers\ManageMessages;
use App\Http\Controllers\ManagePromocodes;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ManageCreditPoints;
use App\Http\Controllers\ManageChildren;
use App\Http\Controllers\ManagePayouts;
use App\Http\Controllers\PaytmController;
use App\Http\Controllers\StripeController;
use App\Models\ClassDetails;
use App\Models\Categories;
use App\Models\UserRole;
use App\Models\SwitchRole;
use App\Models\User;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::get('/', function () {
  $classes = (new ClassDetails)->getApprovedClasses();
  $latest_class = (new ClassDetails)->getLatestClasses();
  $featured_class = (new ClassDetails)->getFeaturedClasses();
  $clientIP = request()->ip();
  //$clientIP = '72.229.28.185';
  $access_location = \Location::get($clientIP); 
    return view('index',compact('classes','access_location','featured_class','latest_class'));
});

Route::get('/email/verify', function () {
  if(Auth::user()->email_verified_at == null){
    return view('auth.verify-email');
  }else{
    $students = (new User)->getTotalStudents();
    $teachers = (new User)->getTotalTeachers();
    $parents = (new User) ->getTotalParents();
    $classes = (new User)->getTotalClasses();
    $users = (new User)->getAllRegUsers();
    $category = Categories::count();
    $user_id = Auth::id();
    $UserRole = UserRole::GetUserRole($user_id);
    if ($UserRole == '3' || $UserRole == '4') {
      $switch_role = SwitchRole::where('user_id',$user_id)->get();
      $enr_class = (new ClassDetails)->getStudentClassDash($user_id);
      return view('dashboard',compact('classes','switch_role','students','teachers','users','category','enr_class','UserRole'));
    }elseif($UserRole == '2'){
      $switch_role = SwitchRole::where('user_id',$user_id)->get();
      $app_class = (new ClassDetails)->getApprovedClassesTeacherDash($user_id);
      return view('dashboard',compact('classes','switch_role','students','teachers','users','category','app_class','UserRole'));
    }
    else{
      return view('dashboard',compact('classes','students','parents','teachers','users','category','UserRole'));
    }
  }
})->middleware('auth')->name('verification.notice');

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
  $students = (new User)->getTotalStudents();
  $teachers = (new User)->getTotalTeachers();
  $parents = (new User) ->getTotalParents();
  $classes = (new User)->getTotalClasses();
  $users = (new User)->getAllRegUsers();
  $category = Categories::count();
  $user_id = Auth::id();
  $UserRole = UserRole::GetUserRole($user_id);
  if ($UserRole == '3' || $UserRole == '4') {
    $enr_class = (new ClassDetails)->getStudentClassDash($user_id);
    $switch_role = SwitchRole::where('user_id',$user_id)->get();
    return view('dashboard',compact('classes','switch_role','students','teachers','users','category','enr_class','UserRole'));
  }elseif($UserRole == '2'){
    $switch_role = SwitchRole::where('user_id',$user_id)->get();
    $app_class = (new ClassDetails)->getApprovedClassesTeacherDash($user_id);
    return view('dashboard',compact('classes','switch_role','students','teachers','users','category','app_class','UserRole'));
  }
  else{
    return view('dashboard',compact('classes','students','teachers','parents','users','category','UserRole'));
  }
})->name('dashboard');

/* Class Management*/
Route::get('/view-classes', [ManageClasses::class, 'index'])->name('class.view');
Route::get('/view-details/{id}', [ManageClasses::class, 'viewClassDetails'])->name('class.details');
Route::get('/add-class', [ManageClasses::class, 'addClassView'])->name('class.add');
Route::post('/insertclassdata',[ManageClasses::class, 'insertData'] )->name('class.insert');
Route::get('/edit-class/{id}',[ManageClasses::class, 'editClass'] )->name('class.edit');
Route::post('/update-class/{id}',[ManageClasses::class, 'updateClass'] )->name('class.update');
Route::get('/delete-class/{id}',[ManageClasses::class, 'deleteClass'] )->name('class.delete');
Route::get('/approve-class/{id}',[ManageClasses::class, 'approveClass'] )->name('class.approve');
Route::get('/decline-class/{id}',[ManageClasses::class, 'declineClass'] )->name('class.decline');
Route::get('/manage-links/{id}',[ManageClasses::class, 'sendClassLinkView'] )->name('link.manage');
Route::post('/send-link',[ManageClasses::class, 'sendClassLink'] )->name('link.send');
Route::post('/send-classrecording',[ManageClasses::class, 'sendClassRecording'] )->name('recording.send');
Route::get('/class-links/{id}',[ManageClasses::class, 'viewClassLinks'] )->name('class.links');
Route::get('/class-recordings/{id}',[ManageClasses::class, 'viewClassRecordings'] )->name('class.recording');
Route::get('/edit-classlink/{id}',[ManageClasses::class, 'editClassLink'] )->name('classlink.edit');
Route::post('/update-classlink/{id}',[ManageClasses::class, 'updateClassLink'] )->name('classlink.update');
/* Class Unsubscribe */
Route::get('/class-unsubscribe/{id}/{c_id}',[ManageClasses::class, 'unsubscribeClass'] )->name('class.unsubscribe');
Route::get('/class-subscribe/{id}/{c_id}/{s_id}',[ManageClasses::class, 'subscribeClass'] )->name('class.subscribe');
Route::get('/unsubscribed-classes',[ManageClasses::class, 'getUnsubscribedClasses'] )->name('get.unsubscribedClasses');
/* Class Featured Status */
Route::get('/featured-status/{status}/{id}', [ManageClasses::class, 'classFeaturedStatus'])->name('featured.status');
/* Upcoming Classes */
Route::get('/upcoming-classes', [ManageClasses::class, 'upcomingClasses'])->name('classes.upcoming');
/* Completed Classes */
Route::get('/completed-classes', [ManageClasses::class, 'completedClasses'])->name('classes.completed');
/* Approved Classes */
Route::get('/approved-classes', [ManageClasses::class, 'approvedClasses'])->name('classes.approved');
/* Declined Classes */
Route::get('/declined-classes', [ManageClasses::class, 'declinedClasses'])->name('classes.declined');
/* Saved Classes */
Route::get('/saved-classes', [ManageClasses::class, 'savedClasses'])->name('classes.saved');
/* Pending Approval Classes */
Route::get('/pending-classes', [ManageClasses::class, 'pendingApprovalClasses'])->name('classes.pending');
/* Featured Classes */
Route::get('/featured-classes', [ManageClasses::class, 'FeaturedClasses'])->name('classes.featured');
/* User Notifications */
Route::get('/all-notifications', [ManageUsers::class, 'userNotifications'])->name('user.notifications');
Route::get('/delete-notification/{id}', [ManageUsers::class, 'deleteUserNotifications'])->name('delete.notifications');
Route::get('/clear-all-notifications', [ManageUsers::class, 'clearAllNotifications'])->name('clear.notifications');
Route::get('/notification-seen/{id}/{url}', [ManageUsers::class, 'seenNotifications'])->name('seen.notifications');

/* All Users*/
Route::get('/all-users',[ManageUsers::class, 'index'] )->name('view.users');
Route::get('/delete-user/{id}',[ManageUsers::class, 'deleteUser'] )->name('user.delete');
Route::get('/redirect',[ManageUsers::class, 'redirect'] )->name('redirect');
Route::get('/edit-user/{id}',[ManageUsers::class, 'editUser'] )->name('user.edit');
Route::post('/update-user/{id}',[ManageUsers::class, 'updateUser'] )->name('user.update');
Route::get('/add-info',[ManageUsers::class, 'addAditinalInfo'] )->name('user.additionalinfo');
Route::post('/post-updatedinfo',[ManageUsers::class, 'postAdditionalInfo'] )->name('user.postdata');
Route::get('/edit-userinfo',[ManageUsers::class, 'editadditionalinfo'] )->name('edit.additionalinfo');
Route::post('/update-userinfo',[ManageUsers::class, 'updateadditionalinfo'] )->name('update.additionalinfo');
Route::post('/add-morefields',[ManageUsers::class, 'addMoreFieldsUser'] )->name('add.moreuserfields');
Route::get('/delete-userfield/{uai_id}',[ManageUsers::class, 'deleteUserField'] )->name('delete.userfield');
Route::get('/user-details/{id}',[ManageUsers::class, 'viewUserData'] )->name('user.data');
Route::post('/add-bio',[ManageUsers::class, 'addTeacherBio'] )->name('add.teacherbio');

/* All Verified Teachers*/
Route::get('/all-teachers',[ManageUsers::class, 'GetAllTeachers'] )->name('get.teachers');
Route::get('/teacher-classes/{t_id}',[ManageUsers::class, 'GetTeacherClasses'] )->name('teacher.classes');

/* Switch User Role */
Route::get('/switch-role',[ManageUsers::class, 'switchUserRole'] )->name('switch.role');
Route::get('/switch-role-parent',[ManageUsers::class, 'switchUserRoleParent'] )->name('parent.role');
Route::get('/switch-role-request',[ManageUsers::class, 'switchRoleRequest'] )->name('request.switch');
Route::get('/admin-approval/{status}/{tsr_id}/{s_id}',[ManageUsers::class, 'adminRequestApproval'] )->name('request.approval');
Route::get('/switch-role-requests',[ManageUsers::class, 'manageSwitchRoleRequest'] )->name('request.manage');

/* Switch User Teacher */
Route::get('/switch-role-teacher',[ManageUsers::class, 'switchUserRoleTeacher'] )->name('switch.teacher');

/* Payouts Management */
Route::get('/payouts',[ManagePayouts::class, 'viewUserPayouts'] )->name('user.payouts');
Route::get('/user-payouts/{id}',[ManagePayouts::class, 'userPayoutDetails'] )->name('payout.details');

/* Children Management */
Route::get('/child-manage',[ManageChildren::class, 'index'] )->name('manage.children');
Route::post('/add-children',[ManageChildren::class, 'addNewChildren'] )->name('add.children');
Route::get('/edit-children/{id}',[ManageChildren::class, 'editChildren'] )->name('edit.children');
Route::post('/update-children',[ManageChildren::class, 'updateChildren'] )->name('update.children');
Route::get('/delete-children/{id}',[ManageChildren::class, 'deleteChildren'] )->name('delete.children');
Route::get('/enrolled_classes/{id}',[ManageChildren::class, 'childrenEnrolledClasses'] )->name('classes.children');
Route::get('/parent-childrens/{id}',[ManageChildren::class, 'parentChildrenDetails'] )->name('parent.children');

/* Study Material Management*/
Route::get('/study-materials', [ManageClasses::class, 'viewStudyMaterials'])->name('view.materials');
Route::post('/upload-studymaterial', [ManageClasses::class, 'uploadStudyMaterial'])->name('upload.materials');
Route::get('/material-status/{status}/{id}', [ManageClasses::class, 'studyMaterialStatus'])->name('status.material');

/* Schedule Management*/
Route::get('/class-schedule/{id}',[ManageSchedule::class, 'classSchedule'] )->name('class.schedule');
Route::get('/schedule-edit/{id}/{class_id}',[ManageSchedule::class, 'scheduleEdit'] )->name('schedule.edit');
Route::post('/update-schedule/{id}',[ManageSchedule::class, 'scheduleUpdate'] )->name('schedule.update');
Route::post('/add-schedule',[ManageSchedule::class, 'addSchedule'] )->name('schedule.add');
Route::get('/schedule-delete/{id}',[ManageSchedule::class, 'scheduleDelete'] )->name('schedule.delete');

/* Request New Schedule */
Route::post('/request-new-schedule', [ManageSchedule::class, 'requestNewSchedule'] )->name('schedule.new');
Route::get('/request-status/{status}/{id}/{s_id}', [ManageSchedule::class, 'requestStatus'] )->name('schedule.status');
Route::get('/user-requests', [ManageSchedule::class, 'getUserRequests'] )->name('user.requests');

/* Request New Class/Topic */
Route::post('/request-new-class', [ManageSchedule::class, 'requestNewClass'] )->name('newclas.request');
Route::get('/class-request-status/{status}/{id}', [ManageSchedule::class, 'classRequestStatus'] )->name('classrequest.status');

/* Manage Requests : Admin */
Route::get('/schedule-requests',[ManageSchedule::class, 'newScheduleRequests'] )->name('view.schedulerequest');
Route::get('/class-requests',[ManageSchedule::class, 'newClassRequests'] )->name('view.classrequest');

/* All Published Classes*/
Route::get('/all-classes',[ManageClasses::class, 'ourClasses'] )->name('view.classes');
Route::get('/class-detail/{c_id}/{t_id}',[ManageClasses::class, 'classData'] )->name('class.data');

/* Category Management*/
Route::get('/all-categories', [ManageCategories::class, 'index'])->name('category.view');
Route::get('/add-category', [ManageCategories::class, 'addCategoryView'])->name('category.add');
Route::get('/delete-category/{c_id}',[ManageCategories::class, 'deleteCategory'] )->name('category.delete');
Route::post('/insertcategory',[ManageCategories::class, 'insertData'] )->name('category.insert');
Route::get('/edit-category/{id}',[ManageCategories::class, 'editCategory'] )->name('category.edit');
Route::post('/update-category/{id}',[ManageCategories::class, 'updateCategory'] )->name('category.update');
Route::get('/category-status/{status}/{id}',[ManageCategories::class, 'categoryStatus'] )->name('category.status');

/* Keywords Management*/
Route::get('/all-keywords', [ManageKeywords::class, 'allKeywords'])->name('keywords.view');
Route::get('/edit-keyword/{id}', [ManageKeywords::class, 'editKeyword'])->name('keyword.edit');
Route::post('/update-keyword/{id}', [ManageKeywords::class, 'updateKeyword'])->name('keyword.update');

/* Enrollment */
Route::get('/enroll/{c_id}/{t_id}', [EnrollCLass::class, 'index'])->name('enroll.view');
Route::get('/enrolled-student', [EnrollCLass::class, 'enrolledStudents'])->name('enroll.student');
Route::get('/checkout-user', [EnrollCLass::class, 'checkoutUser'])->name('checkout.user');

/* Enrolled Students: Admin */
Route::get('/user-enrolled-student/{id}', [EnrollCLass::class, 'viewTeacherEnrolledStudents'])->name('enrolled.student');

/* Feedbacks */
Route::post('/progressive-feedback', [Feedback::class, 'index'])->name('feedback.student');
Route::post('/teacher-feedback', [Feedback::class, 'postTeacherFeedback'])->name('feedback.teacher');
Route::get('/my-feedbacks', [Feedback::class, 'allFeedbacks'])->name('feedback.view');
Route::get('/send-feedback/{id}', [Feedback::class, 'sendFeedbackView'])->name('send.view');
Route::post('/post-classfeedback/{id}', [Feedback::class, 'postClassFeedback'])->name('post.classfeedback');
Route::get('/class-feedbacks/{id}',[Feedback::class, 'classFeedbacks'] )->name('class.feedback');
Route::get('/user-feedbacks/{id}/{u_type}',[Feedback::class, 'allUserFeedbacks'] )->name('user.feedback');
Route::get('/approve-feedback/{id}', [Feedback::class, 'approveFeedback'])->name('feedback.approve');
Route::get('/decline-feedback/{id}', [Feedback::class, 'declineFeedback'])->name('feedback.decline');

/* Manage Messages */
Route::post('/send-message', [ManageMessages::class, 'sendMessage'])->name('message.send');
Route::get('/received-messages', [ManageMessages::class, 'receivedMessage'])->name('message.received');
Route::get('/sent-messages', [ManageMessages::class, 'sentMessage'])->name('message.sent');
Route::get('/all-messages', [ManageMessages::class, 'allMessagesDetails'])->name('messages.info');
//Send message to Admin
Route::post('/send-message-admin', [ManageMessages::class, 'SendMessageToAdmin'])->name('admin.send');
//Send message By Admin to Users
Route::post('/admin-send', [ManageMessages::class, 'SendMessageByAdmin'])->name('message.admin');
//Send message By Admin to Single User
Route::post('/message-to-user', [ManageMessages::class, 'SendMessagetoUser'])->name('message.user');
//Broadcast Message to Teachers
Route::post('/broadcast-message', [ManageMessages::class, 'SendBroadcastMessage'])->name('broadcast.message');

/* Search */
Route::get('/search', [Search::class, 'index'])->name('search.view');
Route::get('/advance-search', [Search::class, 'advanceSearh'])->name('search.advance');
Route::get('/category/{id}', [Search::class, 'categorySearch'])->name('search.category');
Route::get('/search-dashboard', [Search::class, 'dashboardSearch'])->name('search.dashboard');
Route::get('/admin-search', [Search::class, 'adminSearch'])->name('search.admin');
Route::get('/search-expertise', [Search::class, 'expertiseSearch'])->name('search.expertise');

/* Manage Promocodes */
Route::get('/promocodes', [ManagePromocodes::class, 'viewPromocodes'] )->name('view.promo');
Route::post('/add-promocode', [ManagePromocodes::class, 'addPromocode'])->name('promo.add');
Route::get('/promo-status/{status}/{id}', [ManagePromocodes::class, 'promoStatus'])->name('status.promo');
Route::get('/check-promocode', [ManagePromocodes::class, 'checkPromocode'])->name('check.promo');

/* Manage Credit Points */
Route::get('/credit-points', [ManageCreditPoints::class, 'viewCreditPoints'] )->name('view.credit');
Route::post('/add-creditpoint', [ManageCreditPoints::class, 'addCreditPoint'])->name('credit.add');
Route::get('/credit-status/{status}/{id}', [ManageCreditPoints::class, 'creditStatus'])->name('status.credit');
Route::get('/credit-delete/{id}', [ManageCreditPoints::class, 'creditDelete'])->name('delete.credit');

/*Paypal Payment Gateway : Class Enrollment */
Route::get('/class-payment',[Payment::class, 'handlePayment'] )->name('make.payment');
Route::get('/cancel-payment',[Payment::class, 'paymentCancel'] )->name('cancel.payment');
Route::get('/success-payment',[Payment::class, 'paymentSuccess'] )->name('success.payment');
Route::get('/payment-completed',[Payment::class, 'paymentCompleted'] )->name('payment.completed');
Route::get('/payment-cancel',[Payment::class, 'paymentCancelView'] )->name('payment.cancel');

/*Paytm Payment Gateway : Class Enrollment */
// Route::get('/initiate',[PaytmController::class, 'initiate'])->name('initiate.payment');
Route::post('/payment',[PaytmController::class, 'pay'])->name('pay.amount');
Route::post('/payment-status',[PaytmController::class, 'paymentCallback'])->name('status');

//Stripe Payment Gateway
Route::post('/payment-proceed', [StripeController::class, 'handlePost'])->name('stripe.payment');

/*Teacher Details Page */
Route::get('/teacher-details/{t_id}',[ManageUsers::class, 'teacherDetails'] )->name('teacher.details');

/*ATS Tax Management*/
Route::get('/manage-tax',[Payment::class, 'manageTax'] )->name('view.tax');
Route::post('/update-tax',[Payment::class, 'updateTax'] )->name('update.tax');

/* Pages*/
Route::get('/about-us', 'App\Http\Controllers\AboutUs@index')->name('about');
Route::get('/contact-us', 'App\Http\Controllers\ContactUs@index')->name('contact');

/* Test*/
Route::get('/get-location', [ManageUsers::class, 'location']);

/* Clear Configuration And Application Cache */
Route::get('/clear', function() {
   Artisan::call('cache:clear');
   Artisan::call('config:clear');
   Artisan::call('config:cache');
   Artisan::call('view:clear');
   return "Cleared!";
});
Route::get('/optimize', function() {
   Artisan::call('optimize');
   return "Optimized-successfully!";
});
Route::get('/optimize-clear', function() {
   Artisan::call('optimize:clear');
   return "Optimized-successfully!";
});
Route::get('/storage', function() {
   Artisan::call('storage:link');
   return "Created!";
});

