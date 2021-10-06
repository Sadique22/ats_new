<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                  @if(count($user_notifications) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                   All Notifications
                  </p>
                  @else
                  <h6 class="text-white mt-3">No Notifications Received Yet!</h6>
                  @endif
                  <div>
                     <button class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</button>
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
          @elseif(session()->has('fault'))
            <div class="alert alert-danger" id="show_message">
              <div class="container">
                {{ session()->get('fault') }}
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
                  <div class="d-flex">
                    <a class="btn btn-sm btn-danger ml-auto clear_button" href="/clear-all-notifications" onclick="return confirm('Are you sure,you want to Clear all Notifications?')">Clear All</a>
                   </div>
                  <div class="table-responsive">
                    <table id="users" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                           <th> Notification For </th>
                           <th> Notification </th>
                           <th> Date </th>
                           <th> <i class="fa fa-eye" aria-hidden="true"></i> </th>
                           <th> Clear </th>
                        </tr>
                      </thead>
                        @foreach($user_notifications as $notification)
                      <tbody>
                        <tr>
                          <td class="bold">
                            <a href="{{ $notification->not_url }}">{{ $notification->not_for }}</a>
                          </td>
                          <td class="bold">
                            <a href="{{ $notification->not_url }}">{{ $notification->not_details }}</a>
                          </td>
                          <?php
                            $start_datetime = $notification->created_at;
                            $s_date = date('F d, Y', strtotime($start_datetime));
                            $s_time = date('h:i A', strtotime($start_datetime));
                          ?>
                          <td class="bold">{{ $s_date }} ({{ $s_time }})</td>
                          <td class="text-center">
                            @if($notification->not_seen == 0)
                            <i class="fa fa-eye-slash text-danger" aria-hidden="true"></i>
                            @else
                            <i class="fa fa-eye text-success" aria-hidden="true"></i>
                            @endif
                          </td>
                          <td class="text-center">
                            <a href="/delete-notification/{{$notification->tn_id}}" class="text-danger bold"><i class="fa fa-times fa-lg" aria-hidden="true"></i></a>
                          </td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    {{$user_notifications->links()}}
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>  
<style type="text/css">
  .clear_button{
    padding:5px 10px !important;
    border-radius: 8px;
  }
</style>       
@endsection
</x-app-layout>
