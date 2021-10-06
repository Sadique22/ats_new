@php
use App\Models\Categories;
use App\Models\SwitchRole;
use App\Models\UserRole;
$categories = DB::table('tbl_categories')->where('c_status',1)->orderBy('c_id','desc')->get();
$user_id = Auth::id();
$switch_role = Switchrole::where('user_id',$user_id)->get();
$UserRole = UserRole::GetUserRole($user_id);
@endphp
<div class="navbar-wrap">
    <nav id="navbar_top" class="navbar navbar-expand-lg  navbar-light bg_white">
        <div class="container">

            <a class="navbar-brand logo_h" href="{{ url('/') }}">
              <img src="{{ asset('assets/img/logo-black.png') }}" alt="" height="55px" width="240px" />
            </a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
              <span class="icon-bar"></span> <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                <ul class="navbar-nav navbar_top-inner">
                    <li class="nav-item dropdown">
                      <a class="nav-link dropdown-toggle btn btn-sm text-white btn-round btn-block" href="#" data-toggle="dropdown" id="purple_bg_corner"> Explore </a>
                        <ul class="dropdown-menu">
                         @foreach($categories as $category)
                          <?php $encoded = base64_encode($category->c_id) ?>
                          <li><a class="dropdown-item" href="/category/{{$encoded}}">{{$category->c_name}}</a></li>
                          @endforeach
                        </ul>
                    </li>

                    <li class="nav-item">
                        <form class="input-group md-form form-sm form-2 pl-0" type="get" action="/search" onsubmit="return checkSearchInput()">
                            <div class="input-group">
                              <input type="text" id="search_text" class="form-control" placeholder="Search Class" aria-label="search" name="data" aria-describedby="basic-addon2">
                              <div class="input-group-append">
                                <button class="btn btn-outline-secondary" id="btn-search" type="submit"><i class="fa fa-search text-grey"  aria-hidden="true"></i></button>
                              </div>
                            </div>
                        </form>
                    </li>
                    @if(!Auth::user())
                    <li class="nav-item">
                      <a class="nav-link btn-modal start_own" id="dark_purple" data-toggle="modal" data-target="#myModal" data-tab="own_class">Start your own Class
                        <span class="sr-only">(current)</span>
                      </a>
                    </li>
                    <li class="nav-item">
                      <button type="button" class="btn btn-sm text-white btn-round btn-block btn-modal hide_val_error" data-toggle="modal" data-target="#myModal" data-tab="login" id="purple_corner"><i class="fa fa-user mr-1 pt-1"></i>Login</button>  
                    </li>
                    <li class="nav-item ml-lg-0 mt-lg-0 mt-2">
                       <button type="button" class="btn btn-sm text-white btn-round btn-block btn-modal hide_val_error" data-toggle="modal" data-target="#myModal" data-tab="register" id="purple_bg_corner"><i class="fa fa-user mr-1 pt-1"></i>Register</button>
                    </li>
                    @endif 
                    @if (Auth::user())
                      @if($UserRole == '3' && count($switch_role) <= '0' || $UserRole == '4')
                      <li class="nav-item">
                        <a class="nav-link btn-modal start_own" id="dark_purple" href="/switch-role-request">Start your own Class
                        </a>
                      </li>
                      @endif
                      <li class="nav-item ">
                          <a href="{{ url('dashboard') }}" class="btn btn-sm text-white btn-round btn-block" id="purple_corner">
                              <i class="fa fa-user mr-1 pt-1"></i>Dashboard
                          </a>
                      </li>
                      <li class="nav-item">
                        <form method="POST" action="{{ route('logout') }}">
                          @csrf
                          <button class="btn btn-sm text-white btn-round btn-block" type="submit" id="purple_bg_corner"><i class="fa fa-user mr-1"></i>Logout</button>
                        </form>
                      </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</div>

{{-- Login/Register Modal--}}

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <ul class="nav nav-tabs mt-2 pb-2 pr-2" role="tablist" id="myTab">
        <li role="presentation" class="active">
          <a href="#login" aria-controls="login" role="tab" data-toggle="tab" class="btn btn-sm text-white btn-round mr-2 ml-2 hide_val_error remove_buttons" id="tabs_button_purple"><i class="fa fa-user mr-1 pt-1"></i>Login</a>
        </li>
        <li role="presentation">
          <a href="#register" aria-controls="register" class="btn btn-sm text-white btn-round hide_val_error remove_buttons" role="tab" data-toggle="tab" id="tabs_button_purple"><i class="fa fa-user mr-1 pt-1"></i>Register</a>
        </li>
        <li role="presentation" >
          <a href="#own_class" aria-controls="own_class" class="btn btn-sm text-white btn-round hide_val_error hide_button ml-2 d-none" role="tab" data-toggle="tab" id="tabs_button_purple"><i class="fa fa-user mr-1 pt-1"></i>Start your own class</a>
        </li>
        <button type="button" class="btn btn-dark btn-sm ml-auto" data-dismiss="modal">X</button>
      </ul>
      <x-jet-validation-errors class="text-danger hide-error" />
        @if(session()->has('message'))
          <div class="alert alert-success">
            <div class="container">
              {{ session()->get('message') }}
            </div>
          </div>
        @endif
        @if (session('status'))
          <div class="mb-4 font-medium text-sm text-success">
            {{ session('status') }}
          </div>
        @endif
      <div class="tab-content">
        <div role="tabpanel" class="tab-pane fade in active" id="login">
          <div class="modal-body">
            <h6 class="text-center">Login to Any Time Study <i class="fa fa-book" aria-hidden="true"></i></h6>
           <form method="POST" id="login_form" class="reset_form" action="{{ route('login') }}" role="form" data-toggle="validator">
            @csrf
            <div class="d-flex">
              <span class="text-success ml-1" id="loginSuccess"></span>
              <div class="spinner-border text-success" id="pageloader" role="status" style="width: 1.5rem; height: 1.5rem;"></div> 
            </div>
            <div class="form-group">
                <x-jet-label for="email" value="{{ __('Email') }}" /><span class="required-star">*</span>
                <x-jet-input id="l-email" class="form-control" type="email" name="email" :value="old('email')"  autocomplete="email" data-error="Please enter your registered E-mail address" minlength="2" maxlength="40"/>
                <div class="help-block with-errors"></div>
                <span class="text-danger hide_error" id="loginEmailError"></span>
                <span class="text-danger hide_error" id="loginPasswordError"></span>
            </div>
            <div class="mt-2 form-group">
                <x-jet-label for="password" value="{{ __('Password') }}" /><span class="required-star">*</span>
                <x-jet-input id="l-password" class="form-control" type="password" name="password" required autocomplete="current-password" data-error="Please enter your password" maxlength="30"/>
                <i class="fa fa-eye float-right password_toggle" id="l_togglePassword"></i>
                <div class="help-block with-errors"></div>
            </div>
            <div class="block mt-4 d-flex">
                <button type="submit" class="btn btn-primary">Login</button>
                <label for="remember_me" class="ml-auto mt-2">
                    <input id="remember_me" type="checkbox" class="form-checkbox" name="remember">
                    <span class="ml-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
                </label>
            </div>
          </form>
         </div>

          <div class="modal-footer">
            @if (Route::has('password.request'))
              <p><a href="{{ route('password.request') }}">Forgot password?</a></p>
            @endif
          </div>
        </div>
        
        <div role="tabpanel" class="tab-pane fade" id="register">
          <div class="modal-body">
           <h6 class="text-center"> Register to Any Time Study <i class="fa fa-graduation-cap" aria-hidden="true"></i></h6>
            <form method="POST" class="reset_form" id="registration_form" action="{{ route('register') }}">
              @csrf
              <div class="d-flex">
                <span class="text-success ml-1" id="registerSuccess"></span>
                <div class="spinner-border text-success" id="registerLoader" role="status" style="width: 1.5rem; height: 1.5rem;"></div> 
              </div>
                <div class="form-group">
                    <x-jet-label for="name" value="{{ __('Full Name') }}" /><span class="required-star">*</span>
                    <input id="name" class="form-control" type="text" name="name" :value="old('name')" autofocus autocomplete="name" maxlength="30" onkeypress="return (event.charCode > 64 && 
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode == 32)" />
                    <span class="text-danger hide_error" id="nameError"></span>
                </div>
                <div class="form-group">
                    <x-jet-label for="email" value="{{ __('Email') }}" /><span class="required-star">*</span>
                    <input id="r_email" class="form-control" type="email" name="email" minlength="3" maxlength="40"/>
                   <span class="text-danger hide_error remove_error" id="emailError"></span>
                </div>
                <div class="form-group">
                    <x-jet-label for="contact" value="{{ __('Contact') }}" />
                    <input id="contact" class="form-control" type="text" name="contact" :value="old('contact')" maxlength="12" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"/>
                    <span class="text-danger hide_error" id="contactError"></span>
                </div>
                <div class="form-group hide_form">
                    <x-jet-label for="user_type"  value="{{ __('Register as:') }}" /><span class="required-star">*</span>
                      <select class="form-control" name="user_type"  title="I want to register as" data-error="Please select(register as?).">
                          <option value="">Select</option>
                          <option value="3">Student</option>
                          <option value="2">Teacher</option>
                          <option value="4">Parent</option>
                      </select>  
                      <span class="text-danger hide_error" id="user_typeError"></span>
                </div>
                <div class="form-group">
                    <x-jet-label for="password" value="{{ __('Password') }}" /><span class="required-star">*</span>
                    <input id="r_password" class="form-control" type="password" name="password" maxlength="25" data-error="Please Enter Password"  autocomplete="password"/>
                    <i class="fa fa-eye float-right password_toggle" id="r_togglePassword"></i>
                    <div class="help-block with-errors"></div>
                    <span class="text-danger hide_error" id="passwordError"></span>
                </div>
                  <button type="submit" id="registerButton" class="btn btn-primary">Register</button>
                  <button class="btn btn-primary" id="registerLoadbutton" type="button" disabled>
                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  Loading...
                  </button>
              </form>
            </div>
          </div>

        <div role="tabpanel" class="tab-pane fade" id="own_class">
          <div class="modal-body">
            <h6 class="text-center">Start Your Own Class <i class="fa fa-book" aria-hidden="true"></i></h6>
            <form method="POST" class="reset_form" id="teacher_registration_form" action="{{ route('register') }}">
              @csrf
               <div class="d-flex">
                <span class="text-success ml-1" id="TregisterSuccess"></span>
                <div class="spinner-border text-success" id="TregisterLoader" role="status" style="width: 1.5rem; height: 1.5rem;"></div> 
              </div>
                <div class="form-group">
                    <x-jet-label for="name" value="{{ __(' Full name') }}" /><span class="required-star">*</span>
                    <x-jet-input id="name" class="form-control" type="text" name="name" :value="old('name')" autofocus autocomplete="name" onkeypress="return (event.charCode > 64 && 
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode == 32)" maxlength="30" data-minlength="3" required />
                    <span class="text-danger hide_error" id="TnameError"></span>
                </div>
                <div class="form-group">
                    <x-jet-label for="email" value="{{ __('Email') }}" /><span class="required-star">*</span>
                    <x-jet-input id="email" class="form-control" type="email" name="email" :value="old('email')" minlength="3" maxlength="40"/>
                    <span class="text-danger hide_error" id="TemailError"></span>
                </div>
                <div class="form-group">
                    <x-jet-label for="contact" value="{{ __('Contact') }}" />
                    <x-jet-input id="contact" class="form-control" type="text" name="contact" :value="old('contact')" maxlength="12" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))"/>
                    <span class="text-danger hide_error" id="TcontactError"></span>
                </div>
                <div class="form-group hide_form">
                    <x-jet-label for="user_type"  value="{{ __('Register as:') }}" />
                      <select class="form-control" name="user_type"  title="I want to register as" data-error="Please select(register as?)." required="required">
                          <option value="2">Teacher</option>
                      </select> 
                      <span class="text-danger hide_error" id="Tuser_typeError"></span>
                </div>
                <div class="form-group">
                    <x-jet-label for="password" value="{{ __('Password') }}" /><span class="required-star">*</span>
                    <x-jet-input id="password" class="form-control" type="password" name="password" autocomplete="password" maxlength="25" required/>
                    <i class="fa fa-eye float-right password_toggle" id="togglePassword"></i>
                    <span class="text-danger hide_error" id="TpasswordError"></span>
                </div>
                  <button type="submit" id="TregisterButton" class="btn btn-primary">Register</button>
                  <button class="btn btn-primary" id="TregisterLoadbutton" type="button" disabled>
                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  Loading...
                  </button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@if (count($errors) > 0)
<script type="text/javascript">
  $(document).ready(function() {
    $('#myModal').modal('show');
  });
//Re-open same Modal After Page Reload
  $(document).ready(function(){
    $('a[data-toggle="tab"]').on('show.bs.tab', function(e) {
      localStorage.setItem('activeTab', $(e.target).attr('href'));
  });
  var activeTab = localStorage.getItem('activeTab');
    if(activeTab){
      $('#myTab a[href="' + activeTab + '"]').tab('show');
    }
});
</script>
@endif

<script>
//Hiding Validation Error's 
$(document).ready(function(){
    $('.start_own').click(function() {
      $('.hide_button').show();
      $('.remove_buttons').hide();
    });
    $('.hide_val_error').click(function() {
      $('.hide_button').hide();
      $('.remove_buttons').show();
    });
});
$(document).ready(function(){
    $('.hide_val_error').click(function() {
      $('.hide-error').hide();
    });
    $('.start_own').click(function() {
      $('.hide-error').hide();
    });
});
//Form Reset:
$(document).ready(function(){
    $('.hide_val_error').click(function() {
      $('.reset_form').trigger("reset");
    });
});
//Error Div's
// $(document).ready(function(){
//     $("#registerButton").click(function(){
//       if($('.error').is(":visible")){
//         alert('yes');
//       }
//     });
// });

</script>

<style type="text/css">
.nice-select{
  width: 100%;
  margin-top: 10px;
}
.nice-select .option{
  width: 250px;
  line-height: 40px !important;
}
a.active{
  background-color: #fff !important;
  color: #353976 !important;
  border-color: #353976 !important;
  box-shadow: none !important;
}
.required-star{
  color:red;
}
.has-error label, .has-error input, .has-error textarea{
  color: #7b838a;
  border-color: #7b838a;
}
.list-unstyled li{
  color:#dc3545 !important ;
  font-size: 15px;
}
#pageloader
{
  display: none;
}
#registerLoader
{
  display: none;
}
#TregisterLoader
{
  display: none;
}
#registerLoadbutton
{
  display: none;
}
#TregisterLoadbutton
{
  display: none;
}
.error{
  color: #dc3545;
}
</style>

