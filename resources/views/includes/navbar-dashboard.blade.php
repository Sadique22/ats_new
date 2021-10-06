@php
use App\Models\UserAdditionalinfo;
use App\Models\UserRole;
use App\Models\CreditPoints;
use App\Models\ManageNotifications;
$user_id = Auth::id();
$UserRole = UserRole::GetUserRole($user_id);
$userdata = DB::table('user_additional_info')->get();
$notification_student = (new ManageNotifications)->getStudentNotifications($user_id);
$notification_teacher = (new ManageNotifications)->getTeacherNotifications($user_id);
$notification_admin= (new ManageNotifications)->getAdminNotifications($user_id);
@endphp
  <div class="container-scroller">
    <nav class="navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div class="navbar-brand-wrapper d-flex justify-content-center">
        <div class="navbar-brand-inner-wrapper d-flex justify-content-between align-items-center w-100">  <a class="navbar-brand brand-logo" href="{{url('/')}}"><img src="{{asset('assets/img/logo-black.png')}}"  alt="logo"/></a>
          <a class="navbar-brand brand-logo-mini" href="{{url('/')}}"><img src="{{asset('assets/img/icon.png')}}"  alt="logo"/></a>
          <button class="navbar-toggler navbar-toggler align-self-center" type="button" data-toggle="minimize">
            <span class="mdi mdi-sort-variant"></span>
          </button>
        </div>  
      </div>

    <div class="navbar-menu-wrapper d-flex align-items-center justify-content-end">
        <ul class="navbar-nav mr-lg-4 mt-2 w-100">
          <li class="nav-item w-50">
            <form role="form" class="input-group md-form form-sm" type="get" action="/search-dashboard" data-toggle="validator" onsubmit="return checkSearchInput()">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Search Class" aria-label="search" name="data" aria-describedby="basic-addon2" id="search_text">
                  <div class="input-group-append">
                    <button class="btn btn-primary" id="btn-search" type="submit"><i class="fa fa-search text-grey"  aria-hidden="true"></i></button>
                  </div>
                </div>
            </form>
          </li>
          @if($UserRole == '1')
          <li class="nav-item">
           <button type="button"class="btn btn-primary" data-toggle="modal" data-target="#adminsearch" id="search_teacher"> Search Teacher </button>
          </li>
          <li class="nav-item">
            <button type="button"class="btn btn-primary" data-toggle="modal" data-target="#expertise" id="search_expertise_btn"> Search by Expertise </button>
          </li>
          @endif
        </ul>

        <ul class="navbar-nav navbar-nav-right dashboard-navbar">
          <li class="nav-item nav-profile dropdown">
            <a class="nav-link dropdown-toggle d-flex" href="#" data-toggle="dropdown" id="profileDropdown">
              <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
              <span class="nav-profile-name mt-2">{{ Auth::user()->name }}</span>
            </a>

            <div class="dropdown-menu dropdown-menu-right navbar-dropdown profile_setting_dropdown" aria-labelledby="profileDropdown">
              {{--  @if($UserRole !=1)  
                    @if(! UserAdditionalinfo::where('user_id', $user_id)->exists())
                    <a class="dropdown-item" href="{{ route('user.additionalinfo') }}">
                      <i class="mdi mdi-account-check text-primary"></i>
                      Add Information
                    </a>
                    @else
                    <a class="dropdown-item" href="{{ route('edit.additionalinfo') }}">
                      <i class="mdi mdi-account-check text-primary"></i>
                      Update Information
                    </a>
                    @endif
                  @endif  --}}
               <a class="dropdown-item" href="{{ route('profile.show') }}">
                <i class="mdi mdi-settings text-primary"></i>
                My Profile
              </a>

              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a class="dropdown-item" onclick="event.preventDefault();this.closest('form').submit();">
                  <i class="mdi mdi-logout text-primary"></i>
                  Logout
                </a>
              </form>
            </div>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link count-indicator dropdown-toggle d-flex align-items-center justify-content-center notification-dropdown" id="notificationDropdown" href="#" data-toggle="dropdown">
              <i class="mdi mdi-bell mx-4"></i>
              <span class="count"></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right navbar-dropdown" id="notification_dropdown" aria-labelledby="notificationDropdown">
              <p class="mb-0 font-weight-normal float-left dropdown-header">Notifications</p>
                @if($UserRole == 3 || $UserRole == 4)
                  @if(count($notification_student) > 0)
                    <a href="/all-notifications">
                      <p class="mb-0 mt-1 bold float-right badge badge-pill badge-primary dropdown-header">View All</p>
                    </a>
                    @foreach($notification_student as $notification)
                      <?php
                        $url = base64_encode($notification->not_url);
                      ?>
                      <a class="dropdown-item" href="/notification-seen/{{ $notification->tn_id }}/{{ $url }}">
                        <div class="item-thumbnail">
                          <div class="item-icon bg-info">
                            <i class="mdi mdi-information mx-0"></i>
                          </div>
                        </div>
                        <div class="item-content">
                          <h6 class="font-weight-normal">{{$notification->not_details}}</h6>
                           <?php
                            $start_datetime = $notification->created_at;
                            $s_date = date('F d, Y', strtotime($start_datetime));
                            $s_time = date('h:i A', strtotime($start_datetime));
                           ?>
                          <p class="font-weight-light small-text mb-0 text-muted">
                            {{ $s_date }} ({{ $s_time }})
                          </p>
                        </div>
                      </a>
                    @endforeach
                  @else
                    <a class="dropdown-item">
                      <div class="item-content">
                        <h6 class="font-weight-normal">No New Notifications yet!</h6>
                      </div>
                    </a>
                  @endif 
                @elseif($UserRole == 2)
                  @if(count($notification_teacher) > 0)
                    <a href="/all-notifications">
                      <p class="mb-0 mt-1 bold float-right badge badge-pill badge-primary dropdown-header">View All</p>
                    </a>
                    @foreach($notification_teacher as $notification)
                      <?php
                        $url = base64_encode($notification->not_url);
                      ?>
                      <a class="dropdown-item" href="/notification-seen/{{ $notification->tn_id }}/{{ $url }}">
                      <div class="item-thumbnail">
                        <div class="item-icon bg-info">
                          <i class="mdi mdi-information mx-0"></i>
                        </div>
                      </div>
                      <div class="item-content">
                        <h6 class="font-weight-normal">{{$notification->not_details}}</h6>
                          <?php
                            $start_datetime = $notification->created_at;
                            $s_date = date('F d, Y', strtotime($start_datetime));
                            $s_time = date('h:i A', strtotime($start_datetime));
                          ?>
                          <p class="font-weight-light small-text mb-0 text-muted">
                            {{ $s_date }} ({{ $s_time }})
                          </p>
                      </div>
                    </a>
                    @endforeach 
                  @else
                    <a class="dropdown-item">
                      <div class="item-content">
                        <h6 class="font-weight-normal">No New Notifications yet!</h6>
                      </div>
                    </a>
                  @endif 
                @elseif($UserRole == 1)
                  @if(count($notification_admin) > 0)
                    <a href="/all-notifications">
                      <p class="mb-0 mt-1 bold float-right badge badge-pill badge-primary dropdown-header">View All</p>
                    </a>
                    @foreach($notification_admin as $notification)
                      <?php
                        $url = base64_encode($notification->not_url);
                      ?>
                      <a class="dropdown-item" href="/notification-seen/{{ $notification->tn_id }}/{{ $url }}">
                      <div class="item-thumbnail">
                        <div class="item-icon bg-info">
                          <i class="mdi mdi-information mx-0"></i>
                        </div>
                      </div>
                      <div class="item-content">
                        <h6 class="font-weight-normal">{{$notification->not_details}}</h6>
                          <?php
                            $start_datetime = $notification->created_at;
                            $s_date = date('F d, Y', strtotime($start_datetime));
                            $s_time = date('h:i A', strtotime($start_datetime));
                          ?>
                          <p class="font-weight-light small-text mb-0 text-muted">
                            {{ $s_date }} ({{ $s_time }})
                          </p>
                      </div>
                    </a>
                    @endforeach 
                  @else
                    <a class="dropdown-item">
                      <div class="item-content">
                        <h6 class="font-weight-normal">No New Notifications yet!</h6>
                      </div>
                    </a>
                  @endif     
                @endif
            </div>
          </li>
        </ul>
        
        @if($UserRole != 1)
         <span class="text-white credit" data-toggle="tooltip" data-placement="bottom" title="Credit Points : @if(Auth::user()->credit_points == '') 0 @else{{Auth::user()->credit_points}}@endif"><i class="fa fa-gift" aria-hidden="true">{{Auth::user()->credit_points}}</i></span>
        @endif

       {{-- @if($UserRole == 2)
          @if(Auth::user()->credit_points >= 100 && Auth::user()->credit_points <= 499)
            <a class="navbar-brand brand-logo ml-3" href="/dashboard" data-toggle="tooltip" data-placement="bottom" title="Gold Badge"><img src="{{asset('assets/img/badges/gold.png')}}" height="60" width="60"  alt="logo"/></a>
          @elseif(Auth::user()->credit_points >=500)
            <a class="navbar-brand brand-logo ml-3" href="/dashboard" data-toggle="tooltip" data-placement="bottom" title="Platinum Badge"><img src="{{asset('assets/img/badges/platinum.png')}}" height="60" width="60"  alt="logo"/></a>
          @else
           <a class="navbar-brand brand-logo ml-3" href="/dashboard" data-toggle="tooltip" data-placement="bottom" title="Silver Badge"><img src="{{asset('assets/img/badges/silver.png')}}" height="60" width="60"  alt="logo"/></a>
          @endif
        @endif --}}
        
         <button class="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span class="mdi mdi-menu"></span>
        </button>
      </div>
    </nav>
{{--Search Teacher Modal--}}    
    <div class="modal fade" id="adminsearch" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form type="get" action="/admin-search" data-toggle="validator" id="search_teacher_modal">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Search Teacher: Enter Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <input type="text" name="user_name" placeholder="Search by User Name" class="form-control">
                </div>
                <div class="form-group">
                  <input type="text" name="user_qualification" placeholder="Search by Qualification" class="form-control">
                </div>
                <div class="form-group">
                  <input type="text" name="user_occupation" placeholder="Search by Occupation" class="form-control">
                </div>
                <label class="text-dark">Search By Star Rating:</label>
                  <div class="star-rating-teacher">
                    <input id="star-five" type="radio" name="rating" value="5" />
                    <label for="star-five" title="5 stars">
                      <i class="active fa fa-star" aria-hidden="true"></i>
                    </label>
                    <input id="star-four" type="radio" name="rating" value="4" />
                    <label for="star-four" title="4 stars">
                      <i class="active fa fa-star" aria-hidden="true"></i>
                    </label>
                    <input id="star-three" type="radio" name="rating" value="3" />
                    <label for="star-three" title="3 stars">
                      <i class="active fa fa-star" aria-hidden="true"></i>
                    </label>
                    <input id="star-two" type="radio" name="rating" value="2" />
                    <label for="star-two" title="2 stars">
                      <i class="active fa fa-star" aria-hidden="true"></i>
                    </label>
                    <input id="star-one" type="radio" name="rating" value="1" />
                    <label for="star-one" title="1 star">
                      <i class="active fa fa-star" aria-hidden="true"></i>
                    </label>
                  </div>
              </div>
                  <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <input type="submit" name="submit" value="Search" class="btn btn-info">
                  </div>
          </form>
        </div>
      </div>
    </div>
{{--Search Teacher By Expertise Modal--}}    
    <div class="modal fade" id="expertise" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <form type="get" action="/search-expertise" data-toggle="validator" onsubmit="return checkSearchInputExpertise()" id="search_expertise_modal">
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Search Teacher By Expertise</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <input type="text" id="search_expertise" name="expertise" placeholder="Search by Expertise" class="form-control">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <input type="submit" name="submit" value="Search" class="btn btn-info">
              </div>
          </form>
        </div>
      </div>
    </div> 

<script type="text/javascript">
//Search Form Reset
  $(document).ready(function(){
    $('#search_teacher').click(function() {
       $('#search_teacher_modal').trigger("reset");
    });
  });
//Search Expertise Form Reset  
  $(document).ready(function(){
    $('#search_expertise_btn').click(function() {
       $('#search_expertise_modal').trigger("reset");
    });
  });  
</script>
