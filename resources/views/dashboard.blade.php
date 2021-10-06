<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="mr-md-3 mr-xl-5">
                    <h3>Welcome, {{$name = Auth::user()->name}}</h3> 
                    {{--<p class="mb-md-0">Your analytics dashboard.</p>--}}
                  </div>
                </div>
              </div>
            </div>
          </div>  
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
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-2 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  @if($UserRole == 2)
                  <p class="mb-0 pb-1 pt-1 text-white font-weight-medium">Design your Class and Manage all your data from here</p>
                  @elseif($UserRole == 3)
                  <p class="mb-0 text-white font-weight-medium">Manage all your data from here</p>
                  @elseif($UserRole == 4)
                  <p class="mb-0 text-white font-weight-medium">Manage all your data from here</p>
                  @else
                  <p class="mb-0 text-white font-weight-medium">View and Manage Website</p>
                  @endif
                  <div class="d-flex">
                    @if(isset($switch_role))
{{-- Switch Role: Student --}}
                      @if($UserRole == 3 && count($switch_role) <= 0)
                        <a href="/switch-role-request" class="btn btn-light"> Want to Start your own class? <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="You can able to design your own class and teach, after Admin approval"></i></a>
                      @endif
                      @if(count($switch_role) > 0 && $switch_role[0]->tsr_status == '1' && $switch_role[0]->tsr_user_type == '3')
                        <a href="/switch-role" class="btn btn-light">Switch to 
                          @if(Auth::user()->user_type == 2) Student @else Teacher @endif
                        </a>
                      @elseif(count($switch_role) > 0 && $switch_role[0]->tsr_status == '0' && $switch_role[0]->tsr_user_type == '3')
                        <h6 class="text-warning bold pt-1 pb-1">(Approval for Teacher Role Pending)</h6>
                      @elseif(count($switch_role) > 0 && $switch_role[0]->tsr_status == '2' && $switch_role[0]->tsr_user_type == '3')
                        <h6 class="text-warning bold pt-1 pb-1">(Approval for Teacher Role Declined)</h6>  
                      @endif

{{-- Switch Role: Parent --}}
                      @if($UserRole == 4 && count($switch_role) <= 0)
                        <a href="/switch-role-request" class="btn btn-light"> Want to Start your own class?</a>
                      @endif
                      @if(count($switch_role) > 0 && $switch_role[0]->tsr_status == '1' && $switch_role[0]->tsr_user_type == '4')
                        <a href="/switch-role-parent" class="btn btn-light">Switch to 
                            @if(Auth::user()->user_type == 2) Parent @else Teacher @endif
                        </a>
                      @elseif(count($switch_role) > 0 && $switch_role[0]->tsr_status == '0' && $switch_role[0]->tsr_user_type == '4')
                        <h6 class="text-warning bold pt-1 pb-1">(Approval for Teacher Role Pending)</h6>
                      @elseif(count($switch_role) > 0 && $switch_role[0]->tsr_status == '2' && $switch_role[0]->tsr_user_type == '4')
                        <h6 class="text-warning bold pt-1 pb-1">(Approval for Teacher Role Declined)</h6>  
                      @endif
{{-- Switch Role: Teacher --}}
                      @if($UserRole == 2 && count($switch_role) <= 0)
                        <a href="/switch-role-teacher" class="btn btn-light"> Switch to Student <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="You can able to switch role to Student in order to Enroll any class, Later on student dashboard, you can also switch to Teacher as well."></i></a>
                      @endif
                      @if(count($switch_role) > 0 && $switch_role[0]->tsr_status == '1' && $switch_role[0]->tsr_user_type == '2')
                        <a href="/switch-role-teacher" class="btn btn-light">Switch to 
                            @if($UserRole == 2) Student <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Switch to student dashboard to view your Enrolled Classes"></i>@else Teacher @endif
                        </a>
                      @endif
                    @endif  
                  </div>
                </div>
              </div>
            </div>
          </div>
       
{{-- Teacher View --}}  

       {{-- @if(Auth::user()->user_type == 2)
            @if(Auth::user()->credit_points >= 100 && Auth::user()->credit_points <= 499)
              <a class="navbar-brand brand-logo " href="#" data-toggle="tooltip" data-placement="bottom" title="Gold Badge"><img src="{{asset('assets/img/badges/gold.png')}}" onclick="zoomin()" id="image-zoom" GFG="200" height="200" width="200"  alt="logo"/></a>
            @elseif(Auth::user()->credit_points >=500)
              <a class="navbar-brand brand-logo " href="#" data-toggle="tooltip" data-placement="bottom" title="Platinum Badge"><img src="{{asset('assets/img/badges/platinum.png')}}" onclick="zoomin()" id="image-zoom" GFG="200" height="200" width="200"  alt="logo"/></a>
            @else
             <a class="navbar-brand brand-logo " href="#" data-toggle="tooltip" data-placement="bottom" title="Silver Badge"><img src="{{asset('assets/img/badges/silver.png')}}" onclick="zoomin()" id="image-zoom" GFG="200" height="200" width="200"  alt="logo"/></a>
            @endif
            <div>
              <button type="button" class="zoomout-button" onclick="zoomout()"> 
                Zoom-Out 
              </button> 
            </div>
          @endif --}} 

           @if($UserRole == 2)
            @if(count($app_class) > 0)
              <div class="row">
                  <div class="col-lg-6">
                    <h6 class="text-primary bold">Approved Classes</h6>
                  </div>
                  <div class="col-lg-12">
                      <div class="owl-carousel enrolled_course">
                          @foreach($app_class as $class)
                          <div class="single_course">
                           @if($class->category_image != null && $class->bg_color != null && $class->c_status == 1)
                              <div class="course_head" style="background-color: {{$class->bg_color}};">
                                    <?php $c_id = base64_encode($class->id);
                                           $t_id = base64_encode($class->created_by)
                                    ?>
                                    <a href="class-detail/{{$c_id}}/{{$t_id}}">
                                     <img class="img-fluid" src="{{$class->category_image}}" alt="" />
                                   </a>
                              </div>
                            @else
                              <div class="course_head" style="background-color: #cec8ff;">
                                    <?php $c_id = base64_encode($class->id);
                                           $t_id = base64_encode($class->created_by)
                                    ?>
                                    <a href="class-detail/{{$c_id}}/{{$t_id}}">
                                     <img class="img-fluid" src="{{asset('assets/img/other-category.png')}}" alt="" />
                                    </a>
                              </div>
                            @endif   
                              <div class="course_content">
                                  <h4 class="mt-2">
                                     <a href="class-detail/{{$c_id}}/{{$t_id}}"> {{$class->class_title}} </a>
                                  </h4>
                                  
                                  <div class="author purchase">
                                      <h6 class="d-inline-block">By: {{$class->name}}</h6>
                                  </div>
                                  
                                  <div class="timings d-flex">
                                      <?php
                                        $originalDate = $class->live_date ;
                                        $newDate = date("F d, Y", strtotime($originalDate));
                                      ?>
                                      <span class="mt-2 mr-3"><i class="fa fa-calendar mr-1" aria-hidden="true"></i> {{$newDate}}</span>
                                      <span class="mt-2 mr-3 ml-auto"><i class="fa fa-clock-o mr-1" aria-hidden="true"></i>{{$class->class_duration}} Hours/day</span>
                                  </div>
                                  <div class="star_ratings">
                                    @for($i = 0; $i < 5; $i++)
                                     <span class="ml-1">
                                      <i class="fa fa-star text-warning{{ $class->avg_rating <= $i ? '-o' : '' }}"></i>
                                     </span>
                                    @endfor
                                    <h6 class="level"><i class="fa fa-bar-chart" aria-hidden="true"></i> {{$class->class_level}}</h6>
                                  </div>
                              </div>
                          </div>
                          @endforeach
                      </div>
                  </div>
              </div>
            @else
              <h2 class="text-warning">You have not Created any class yet!</h2>
              <a href="/add-class" class="btn btn-info mx-auto">Create New Class</a> 
            @endif
          @endif 

{{-- Student||Parent View --}}    

          @if($UserRole == 3 || $UserRole == 4)
            @if(count($enr_class) > 0)
              <div class="row">
                  <div class="col-lg-6">
                    <h6 class="text-primary bold">Enrolled Classes</h6>
                  </div>
                  <div class="col-lg-12">
                      <div class="owl-carousel enrolled_course">
                          @foreach($enr_class as $class)
                          <div class="single_course">
                            @if($class->category_image != null && $class->bg_color != null && $class->c_status == 1)
                                <div class="course_head" style="background-color: {{$class->bg_color}};">
                                      <?php $c_id = base64_encode($class->id);
                                             $t_id = base64_encode($class->created_by)
                                      ?>
                                      <a href="class-detail/{{$c_id}}/{{$t_id}}">
                                       <img class="img-fluid" src="{{$class->category_image}}" alt="" />
                                     </a>
                                </div>
                              @else
                                <div class="course_head" style="background-color: #cec8ff;">
                                      <?php $c_id = base64_encode($class->id);
                                             $t_id = base64_encode($class->created_by)
                                      ?>
                                      <a href="class-detail/{{$c_id}}/{{$t_id}}">
                                       <img class="img-fluid" src="{{asset('assets/img/other-category.png')}}" alt="" />
                                      </a>
                                </div>
                              @endif   
                              <div class="course_content">
                                  <h4 class="mt-2">
                                     <a href="class-detail/{{$c_id}}/{{$t_id}}"> {{$class->class_title}} </a>
                                  </h4>
                                  
                                  <div class="author purchase">
                                      <h6 class="d-inline-block">By: {{$class->name}}</h6>
                                  </div>
                                  
                                  <div class="timings d-flex">
                                      <?php
                                        $originalDate = $class->live_date ;
                                        $newDate = date("F d, Y", strtotime($originalDate));
                                      ?>
                                      <span class="mt-2 mr-3"><i class="fa fa-calendar mr-1" aria-hidden="true"></i> {{$newDate}}</span>
                                      <span class="mt-2 mr-3 ml-auto"><i class="fa fa-clock-o mr-1" aria-hidden="true"></i>{{$class->class_duration}} Hours/day</span>
                                  </div>
                                  <div class="star_ratings">
                                    @for($i = 0; $i < 5; $i++)
                                     <span class="ml-1">
                                      <i class="fa fa-star text-warning{{ $class->avg_rating <= $i ? '-o' : '' }}"></i>
                                     </span>
                                    @endfor
                                    <h6 class="level"><i class="fa fa-bar-chart" aria-hidden="true"></i> {{$class->class_level}}</h6>
                                </div>
                              </div>
                          </div>
                          @endforeach
                      </div>
                  </div>
              </div>
            @else
              @if($UserRole == 3)
              <h2 class="text-warning">You have not Enrolled to any class yet!</h2>
              <a href="/all-classes" class="btn btn-info mx-auto">Explore Classes</a>
              @elseif($UserRole == 4)
              <h2 class="text-warning">You have not Enrolled to any class yet!</h2>
              <a href="/all-classes" class="btn btn-info mx-auto">Explore Classes</a> 
              <a href="{{route('manage.children')}}" class="btn btn-info mx-auto">Manage Childrens</a>
              @endif 
            @endif
          @endif 

{{-- Admin View --}} 

          @if(Auth::user()->user_type == 1)
          <div class="row">
            <div class="col-md-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body dashboard-tabs p-0">
                  <ul class="nav nav-tabs px-4" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="true">Users Overview</a>
                    </li>
                    <li class="nav-item">
                       <a class="nav-link" id="sales-tab" data-toggle="tab" href="#sales" role="tab" aria-controls="sales" aria-selected="false">Classes</a>
                    </li>
                  </ul>
                  <div class="tab-content py-0 px-0">
                    <div class="tab-pane fade show active" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <a href="/all-users">
                            <i class="mdi mdi-account-check icon-lg mr-3 text-warning"></i>
                          </a>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted bold"><a href="/all-users"> Students registered</a></small>
                            <h5 class="mr-2 mb-0">{{$students}}</h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <a href="/all-users">
                            <i class="mdi mdi-certificate mr-3 icon-lg text-danger"></i>
                          </a>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted bold"><a href="/all-users"> Teachers registered</a></small>
                            <h5 class="mr-2 mb-0">{{$teachers}}</h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <a href="/all-users">
                            <i class="mdi mdi-account-check icon-lg mr-3 text-warning"></i>
                          </a>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted bold"><a href="/all-users"> Parents registered</a></small>
                            <h5 class="mr-2 mb-0">{{$parents}}</h5>
                          </div>
                        </div>
                         <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <a href="/all-users">
                            <i class="mdi mdi-account-multiple mr-3 icon-lg text-success"></i>
                          </a>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted bold"><a href="/all-users"> Total Registered Users</a></small>
                            <h5 class="mr-2 mb-0">{{$users}}</h5>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="tab-pane fade" id="sales" role="tabpanel" aria-labelledby="sales-tab">
                      <div class="d-flex flex-wrap justify-content-xl-between">
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <a href="/view-classes">
                            <i class="mdi mdi mdi-calendar-heart icon-lg mr-3 text-primary"></i>
                          </a>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted bold"> <a href="/view-classes"> Total Classes </a></small>
                            <h5 class="mr-2 mb-0">{{$classes}}</h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <a href="/view-classes">
                            <i class="mdi mdi-vector-selection icon-lg mr-3 text-primary"></i>
                          </a>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted bold"> <a href="/all-categories"> View Categories </a></small>
                            <h5 class="mr-2 mb-0">{{$category}}</h5>
                          </div>
                        </div>
                        <div class="d-flex border-md-right flex-grow-1 align-items-center justify-content-center p-3 item">
                          <a href="/view-classes">
                            <i class="mdi mdi-table-edit icon-lg mr-3 text-primary"></i>
                          </a>
                          <div class="d-flex flex-column justify-content-around">
                            <small class="mb-1 text-muted bold"> <a href="/add-category"> Add New Category </a></small>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endif
        </div>

  <script type="text/javascript"> 
    function zoomin() { 
      var GFG = document.getElementById("image-zoom"); 
      var currWidth = GFG.clientWidth; 
      GFG.style.width = (currWidth + 150) + "px"; 
    } 
    
    function zoomout() { 
      var GFG = document.getElementById("image-zoom"); 
      var currWidth = GFG.clientWidth; 
      GFG.style.width = (currWidth - 150) + "px"; 
    } 
  </script> 
@endsection
</x-app-layout>
