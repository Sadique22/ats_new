@php
use App\Models\UserRole;
$user_id = Auth::id();
$UserRole = UserRole::GetUserRole($user_id); 
@endphp
  <div class="container-fluid page-body-wrapper">
      <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav fixed">
          <li class="nav-item">
            <a class="nav-link" href="{{route('dashboard')}}">
              <i class="mdi mdi-home menu-icon"></i>
              <span class="menu-title">Dashboard</span>
            </a>
          </li>
          
          @if($UserRole == 1)
          <li class="nav-item">
            <a class="nav-link" href="{{route('view.users')}}">
              <i class="mdi mdi-account-multiple menu-icon"></i>
              <span class="menu-title">All Users</span>
            </a>
          </li>
          @endif
          @if($UserRole == 4)
          <li class="nav-item">
            <a class="nav-link" href="{{route('manage.children')}}">
              <i class="mdi mdi-account-multiple menu-icon"></i>
              <span class="menu-title">Manage Children</span>
            </a>
          </li>
          @endif
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <i class="mdi mdi-responsive menu-icon"></i>
              <span class="menu-title">Classes</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-basic">
              <ul class="nav flex-column sub-menu">
                @if($UserRole == 3 || $UserRole == 4)
                <li class="nav-item"> <a class="nav-link" href="{{route('class.view')}}">Enrolled Classes</a></li>
                @else
                <li class="nav-item"> <a class="nav-link" href="{{route('class.view')}}">View Classes</a></li>
                @endif
                @if($UserRole == 2)
                <li class="nav-item"> <a class="nav-link" href="{{route('class.add')}}">Add New Class</a></li>
                @endif
                @if($UserRole == 1 || $UserRole == 3 || $UserRole == 4)
                <li class="nav-item"> <a class="nav-link" href="{{route('get.unsubscribedClasses')}}">Unsubscribed Classes</a></li>
                @endif
              </ul>
            </div>
          </li>
          @if($UserRole == 1)
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#category" aria-expanded="false" aria-controls="category">
              <i class="mdi mdi-vector-selection menu-icon"></i>
              <span class="menu-title">Categories</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="category">
              <ul class="nav flex-column sub-menu">
                 <li class="nav-item"> <a class="nav-link" href="{{route('category.view')}}">View Categories</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('category.add')}}">Add New Category</a></li>
              </ul>
            </div>
          </li>
           <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#keywords" aria-expanded="false" aria-controls="keywords">
              <i class="mdi mdi-table-edit menu-icon"></i>
              <span class="menu-title">Keywords</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="keywords">
              <ul class="nav flex-column sub-menu">
                 <li class="nav-item"> <a class="nav-link" href="{{route('keywords.view')}}">Manage Keywords</a></li>
              </ul>
            </div>
          </li>
          @endif
          @if($UserRole == 2)
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#students" aria-expanded="false" aria-controls="students">
              <i class="mdi mdi mdi-account-switch menu-icon"></i>
              <span class="menu-title">Students</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="students">
              <ul class="nav flex-column sub-menu">
                 <li class="nav-item"> <a class="nav-link" href="{{route('enroll.student')}}">Enrolled Students</a></li>
              </ul>
            </div>
          </li>
          @endif
          @if($UserRole == 3 || $UserRole == 2 || $UserRole == 4)
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#feedback" aria-expanded="false" aria-controls="feedback">
              <i class="mdi mdi mdi-animation menu-icon"></i>
              <span class="menu-title">Feedbacks</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="feedback">
              <ul class="nav flex-column sub-menu">
                 <li class="nav-item"> <a class="nav-link" href="{{route('feedback.view')}}">My Feedbacks</a></li>
              </ul>
            </div>
          </li>
          @endif
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#message" aria-expanded="false" aria-controls="message">
              <i class="mdi mdi mdi-wechat menu-icon"></i>
              <span class="menu-title">Messages</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="message">
              <ul class="nav flex-column sub-menu">
                 @if($UserRole == 1)
                 <li class="nav-item"> <a class="nav-link" href="{{route('messages.info')}}">All Messages Info</a></li>
                 @endif
                 <li class="nav-item"> <a class="nav-link" href="{{route('message.received')}}">Received Messages</a></li>
                 <li class="nav-item"> <a class="nav-link" href="{{route('message.sent')}}">Sent Messages</a></li>
              </ul>
            </div>
          </li>
          @if($UserRole == 1)
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#promo" aria-expanded="false" aria-controls="promo">
              <i class="mdi mdi mdi-assistant menu-icon"></i>
              <span class="menu-title">Promo Codes</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="promo">
              <ul class="nav flex-column sub-menu">
                 <li class="nav-item"> <a class="nav-link" href="{{route('view.promo')}}">Manage Promocodes</a></li>
              </ul>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#request" aria-expanded="false" aria-controls="promo">
              <i class="mdi mdi-account-convert menu-icon"></i>
              <span class="menu-title">Manage Requests</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="request">
              <ul class="nav flex-column sub-menu">
                 <li class="nav-item"> <a class="nav-link" href="{{route('view.schedulerequest')}}">New Schedule</a></li>
                 <li class="nav-item"> <a class="nav-link" href="{{route('view.classrequest')}}">New Class/Topic</a></li>
                <li class="nav-item"> <a class="nav-link" href="{{route('request.manage')}}">Switch Role Requests</a></li>
              </ul>
            </div>
          </li>
          @endif
          @if($UserRole == 2 || $UserRole == 3 || $UserRole == 4 || $UserRole == 1)
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#study" aria-expanded="false" aria-controls="study">
              <i class="mdi mdi-book-open-page-variant menu-icon"></i>
              <span class="menu-title">Study Materials</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="study">
              <ul class="nav flex-column sub-menu">
                @if($UserRole == 2)
                 <li class="nav-item"> <a class="nav-link" href="{{route('view.materials')}}">Manage Materials</a></li>
                @endif
                @if($UserRole != 2)
                 <li class="nav-item"> <a class="nav-link" href="{{route('view.materials')}}">View Materials</a></li>
                @endif
              </ul>
            </div>
          </li>
          @endif
          @if($UserRole == 3 || $UserRole == 4)
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#req-status" aria-expanded="false" aria-controls="req-status">
              <i class="mdi mdi-account-convert menu-icon"></i>
              <span class="menu-title">Request Status</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="req-status">
              <ul class="nav flex-column sub-menu">
                 <li class="nav-item"> <a class="nav-link" href="{{route('user.requests')}}">View Status</a></li>
              </ul>
            </div>
          </li>
          @endif
          @if($UserRole == 3 || $UserRole == 4)
          <li class="nav-item">
            <a class="nav-link" data-toggle="collapse" href="#payouts" aria-expanded="false" aria-controls="payouts">
              <i class="mdi mdi-cash-multiple menu-icon"></i>
              <span class="menu-title">Payout Details</span>
              <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="payouts">
              <ul class="nav flex-column sub-menu">
                 <li class="nav-item"> <a class="nav-link" href="{{route('user.payouts')}}">View Payouts</a></li>
              </ul>
            </div>
          </li>
          @endif
          @if($UserRole == 1)
          <li class="nav-item">
            <a class="nav-link" href="{{route('view.tax')}}">
              <i class="mdi mdi-cash-multiple menu-icon"></i>
              <span class="menu-title">Tax Management</span>
            </a>
          </li>
          @endif
        </ul>
      </nav>
   