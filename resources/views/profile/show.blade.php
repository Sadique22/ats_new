<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
               <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                  <p class="mt-3 text-white font-weight-medium">
                   Update Profile Information
                  </p>
                  <div>
                  @if(Auth::user()->user_type !=1)  
                    @if(isset (Auth::user()->occupation) || isset (Auth::user()->qualification) || isset(Auth::user()->gender))
                    <a class="btn btn-info bold" href="{{ route('edit.additionalinfo') }}">
                      Update Additional Information
                    </a>
                    @endif
                  @endif  
                  <a href="/dashboard" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @if(Auth::user()->user_type !=1)
           <div class="row">
            <div class="col-md-5">
              <p class="text-primary float-left bold">Profile Completeness </p>
              <div class="progress pl-3">
                @if(isset (Auth::user()->occupation) && isset(Auth::user()->qualification) && isset(Auth::user()->gender)) 
                  <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 100%;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">100%</div>
                @else
                   <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 50%;" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">50%</div>
                @endif
              </div>
            </div>
          </div>
          @endif
          @if(session()->has('message'))
            <div class="alert alert-success" id="show_message">
              <div class="container">
                {{ session()->get('message') }}
              </div>
            </div>
          @endif
          @if ($errors->any())
            <div class="alert alert-danger" id="show_message">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                 @endforeach
              </ul>
            </div>
          @endif
          <div class="row">
            <div class="col-md-12 stretch-card">
              <div class="card">
                <div class="card-body">
                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updateProfileInformation()))
                @livewire('profile.update-profile-information-form')
                    <x-jet-section-border />
                @endif

{{-- Teacher Bio --}} 
        @if(Auth::user()->user_type == 2)
          <div class="row">
              <div class="col-12">
                <h6 class="text-primary bold">
                  Update Bio <i class="fa fa-arrow-down" aria-hidden="true"></i> 
                </h6>
              </div>
              <div class="col-12 grid-margin">
                  <div class="card">
                    <div class="card-body">
                      <form method="POST" action="{{ route('add.teacherbio') }}" onsubmit="return checkUserBio()" enctype="multipart/form-data" role="form" data-toggle="validator" id="my_form">
                        @csrf
                        <div class="row">
                          <div class="col-md-12">
                            <div class="form-group">
                              <textarea id="textarea" maxlength="240" class="form-control @error('user_bio') is-invalid @enderror" name="user_bio" placeholder="Please Enter your Bio">{{Auth::user()->user_bio}}</textarea>
                              <span id="rchars">0</span> /240
                              <span class="text-danger ml-1" id="userBioFailedMessage"></span>
                            </div>
                          </div>
                        </div>
                        <div class="float-right">
                          <x-jet-button>
                              {{ __('Save') }}
                          </x-jet-button>
                        </div> 
                      </form>
                    </div>  
                  </div>
              </div>
          </div>
        @endif    

{{--Additional Information--}}
              @if(!isset(Auth::user()->qualification) && !isset(Auth::user()->occupation) && !isset (Auth::user()->gender))
                @if(Auth::user()->user_type !=1)  
                <div class="row">
                  <div class="col-12">
                   <h6 class="text-primary bold">
                    Update Your Additional Information <i class="fa fa-arrow-down" aria-hidden="true"></i> 
                   </h6>
                  </div>
                  <div class="col-12 grid-margin">
                    <div class="card">
                      <div class="card-body">
                      <form method="POST" action="{{ route('user.postdata') }}" enctype="multipart/form-data" role="form" data-toggle="validator" id="my_form">
                         @csrf
                          <div class="row">
                            @if(! isset(Auth::user()->qualification))
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Qualification</label>
                                      <input type="text" value="{{ old('qualification') }}" id="name" class="form-control @error('qualification') is-invalid @enderror"  name="qualification" placeholder="e.g.: BE/MBA with Computer Science...">
                                  </div>
                              </div>
                              @endif
                              @if(! isset(Auth::user()->occupation))
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Occupation</label>
                                  <input type="text" value="{{ old('occupation') }}" id="name" class="form-control @error('occupation') is-invalid @enderror"  name="occupation" placeholder="Professor/Student">
                                  </div>
                              </div>
                              @endif
                              @if(! isset(Auth::user()->gender))
                              <div class="col-md-6">
                                  <div class="form-group">
                                      <label>Gender</label>
                                      <select class="form-control @error('gender') is-invalid @enderror" name="gender" data-style="select-with-transition" data-error="Select Your Gender">
                                        <option value="">Select</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                      </select>
                                  </div>
                              </div>
                              @endif
                          </div>
{{--Information--}}
                        @if(Auth::user()->user_type == 2)
                            <p class="card-description">
                                Please Enter Your Fields of Expertise<i class="fa fa-arrow-down" aria-hidden="true"></i> 
                            </p>
                            <div class="row">
                              <div class="col-md-8">
                                <div class="form-group">
                                    <div class="multi-field-wrapper">
                                        <div class="multi-fields">
                                            <div class="multi-field d-flex">
                                                <input type="text" value="{{ old('expertise') }}" class="form-control @error('expertise') is-invalid @enderror mr-1 mt-2" name="expertise[]" placeholder="Please Enter your Expertise" data-error="Please Enter Your Fileds of Expertise" >
                                                <button type="button" class="remove-field btn btn-danger">Remove</button>
                                            </div>
                                        </div>
                                        <button type="button" class="add-field btn btn-primary">Add field</button>
                                    </div>
                                </div>
                              </div>
                            </div>
                        @endif

                        @if(Auth::user()->user_type == 3)
                            <p class="card-description">
                            Please Enter Your Fields of Interest<i class="fa fa-arrow-down" aria-hidden="true"></i> 
                            </p>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <div class="multi-field-wrapper">
                                            <div class="multi-fields">
                                                <div class="multi-field d-flex">
                                                    <input type="text" value="{{ old('interests') }}" class="form-control @error('interests') is-invalid @enderror mr-1 mt-2" name="interest[]" placeholder="Please Enter your fields of Interest">
                                                    <button type="button" class="remove-field btn btn-danger">Remove</button>
                                                </div>
                                            </div>
                                            <button type="button" class="add-field btn btn-primary">Add field</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                          <div class="float-right">
                            <x-jet-button>
                                {{ __('Save') }}
                            </x-jet-button>
                          </div>
                            </form>
                          </div>
                        </div>
                      </div>
                  </div>
                  <x-jet-section-border />
                @endif
                @endif
            
                @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::updatePasswords()))
                    <div class="mt-10 sm:mt-0">
                        @livewire('profile.update-password-form')
                    </div>
                    <x-jet-section-border />
                @endif

               {{--<div class="mt-10 sm:mt-0">
                    @livewire('profile.logout-other-browser-sessions-form')
                </div>
                <x-jet-section-border /> --}} 

                <div class="mt-10 sm:mt-0">
                    @livewire('profile.delete-user-form')
                </div>
                </div>
              </div>
            </div>
          </div>
      </div>  
@endsection
<style type="text/css">
.max-w-xl {
    max-width: 100%;
}
.my-class-card{
  margin-top: -90px !important;
}
.card{
   margin-top: 0px !important;
}
.shadow.overflow-hidden.sm\:rounded-md {
  margin-top: -50px !important;
}
.flex.items-center.justify-end.px-4.py-3.bg-gray-50.text-right.sm\:px-6 {
    margin-top: -30px !important;
}
.px-4.py-5.sm\:p-6.bg-white.shadow.sm\:rounded-lg {
    margin-top: -60px;
}
.bg-white.rounded-lg.overflow-hidden.shadow-xl.transform.transition-all.sm\:w-full.sm\:max-w-2xl {
    margin-top: 50px;
}
</style>
<script type="text/javascript">
          function checkUserBio() {
              var a = document.getElementById("textarea").value;
                if (a == null || a == "") {
                   $('#userBioFailedMessage').text("Please Enter Your Bio.!");
                  return false;
                }else{
                  return true;
                }
            } 
          </script>
</x-app-layout>
