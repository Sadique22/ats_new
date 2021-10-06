<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
               <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                   @if(count($scheduledata) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                   All New Requested Schedule Requests
                  </p>
                  @else
                  <h6 class="text-white mt-3">Not received any request yet!</h6>
                  @endif
                  <div>
                    {{--<button type="button" class="btn btn-info" data-toggle="modal" data-target="#sendmessage"> Send Message to User</button>--}}
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
                  <p class="card-title">All Request Details</p>
                  <div class="table-responsive">
                    <table id="example" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                          <th>Class</th>
                          <th>Sent By</th>
                          <th>Class Topic</th>
                          <th>Guide Name</th>
                          <th>Attend as</th>
                          <th>Requested Time/Date</th>
                          <th>Attached Message</th>
                          <th>Request Received</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      @foreach($scheduledata as $data)
                      <tbody>
                         <tr>
                          <td class="text-primary bold">
                            <?php 
                              $c_id = base64_encode($data->class_id);
                              $t_id = base64_encode($data->teacher_id)
                            ?>
                            <a href="class-detail/{{$c_id}}/{{$t_id}}" target="_blank"> {{$data->class_title}}</a>
                          </td>
                          <td class="text-primary bold"><a href="/user-details/{{$data->student_id}}" target="_blank"> {{ $data->student }}</a></td>
                          <td>{{ $data->class_topic }}</td>
                          <td>{{ $data->teacher }}</td>
                          <td>
                            @if($data->attend_as == 0)
                            <h6>One to One</h6>
                            @else
                            <h6>Group</h6>
                            @endif
                          </td>
                          <?php
                          $schedule_datetime = $data->topic_start_date; 
                          $s_date = date('d-m-Y', strtotime($schedule_datetime));
                          $s_time = date('h:i A', strtotime($schedule_datetime));
                          ?>
                          <td>{{$s_date}} || {{$s_time}}</td>
                          <td>{{ $data->sr_message }}</td>
                          <?php
                          $schedule_datetime = $data->created_at; 
                          $date = date('d-m-Y', strtotime($schedule_datetime));
                          ?>
                          <td>{{ $date }}</td>
                          <td>
                            <?php 
                              $decline = 2;
                              $accept = 1;
                            ?>
                            @if($data->sr_status == 1)
                             <a href="/request-status/{{$decline}}/{{$data->sr_id}}/{{$data->student_id}}" onclick="return confirm('Are you sure,you want to Decline the Request?')" class="btn btn-success">Approved</a>
                            @elseif($data->sr_status == 0)
                             <a href="/request-status/{{$accept}}/{{$data->sr_id}}/{{$data->student_id}}" class="btn btn-info">Approve</a>
                             <a href="/request-status/{{$decline}}/{{$data->sr_id}}/{{$data->student_id}}" onclick="return confirm('Are you sure,you want to Decline the Request?')" class="btn btn-danger">Decline</a>
                            @else
                             <a href="/request-status/{{$accept}}/{{$data->sr_id}}/{{$data->student_id}}" class="btn btn-danger">Declined</a>
                            @endif
                          </td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    {{$scheduledata->links()}}
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
{{--Send Message to User--}}      
       <div class="modal fade" id="sendmessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form method="post" action="/send-message-admin" data-toggle="validator" id="my_form">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send Message to User</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="form-group"> 
                  <textarea name="message" minlength="5" maxlength="240" placeholder="Enter Message" class="form-control" data-error="Please Enter Message" required></textarea>
                    <div class="help-block with-errors"></div>
                </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                  <input type="submit" name="submit" value="Send" class="btn btn-info">
              </div>
            </form>
          </div>
        </div>
      </div>    
@endsection
</x-app-layout>
