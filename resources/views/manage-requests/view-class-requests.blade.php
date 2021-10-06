<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
               <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                   @if(count($requestdata) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                   All New Requested Class/Topic Requests
                  </p>
                  @else
                  <h6 class="text-white mt-3">Not received any request yet!</h6>
                  @endif
                  <div>
                    <button type="button" id="clear_text" class="btn btn-info" data-toggle="modal" data-target="#broadcastmessage"> Broadcast Message to Teachers</button>
                    <a href="/dashboard" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
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
                          <th>Name</th>
                          <th>Email</th>
                          <th>Contact</th>
                          <th>Attend as</th>
                          <th>Members</th>
                          <th>Pay</th>
                          <th>Start at</th>
                          <th>Class Details</th>
                          <th>Attached Message</th>
                          <th>Request Received</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      @foreach($requestdata as $data)
                      <tbody>
                         <tr>
                          <td>{{ $data->ncr_user_name }}</td>
                          <td>{{ $data->ncr_user_email }}</td>
                          <td>{{ $data->ncr_user_contact }}</td>
                          <td>
                            @if($data->ncr_attend_as == 0)
                            <h6>One to One</h6>
                            @else
                            <h6>Group</h6>
                            @endif
                          </td>
                          <td>
                            @if($data->ncr_attend_as == 1)
                            <h6>{{ $data->ncr_group_member }}</h6>
                            @else
                            <h6 class="text-center text-primary bold">*</h6>
                            @endif
                          </td>
                          <td>{{ $data->ncr_pay }}</td>
                          <?php
                          $start_date = date('d-m-Y', strtotime($data->ncr_start_date));
                          ?>
                          <td>{{ $start_date }}</td>
                          <td>{{ $data->ncr_class_detail }}</td>
                          <td>
                            @if(isset($data->ncr_message))
                            <h6>{{ $data->ncr_message }}</h6>
                            @else
                            <h6>No message</h6>
                            @endif
                          </td>
                          <?php
                          $received_date = date('d-m-Y', strtotime($data->created_at));
                          ?>
                          <td>{{ $received_date }}</td>
                          <td>
                            <?php 
                              $decline = 2;
                              $accept = 1;
                            ?>
                            @if($data->ncr_status == 1)
                            <a href="/class-request-status/{{$decline}}/{{$data->ncr_id}}" onclick="return confirm('Are you sure,you want to Decline the Request?')" class="btn btn-success">Approved</a>
                            @elseif($data->ncr_status == 0)
                            <a href="/class-request-status/{{$accept}}/{{$data->ncr_id}}" class="btn btn-info">Approve</a>
                            <a href="/class-request-status/{{$decline}}/{{$data->ncr_id}}" onclick="return confirm('Are you sure,you want to Decline the Request?')" class="btn btn-danger">Decline</a>
                            @else
                            <a href="/class-request-status/{{$accept}}/{{$data->ncr_id}}" class="btn btn-danger">Declined</a>
                            @endif
                          </td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    {{$requestdata->links()}}
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>
{{--Send Message to User--}}      
       <div class="modal fade" id="broadcastmessage" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form method="post" action="/broadcast-message" data-toggle="validator">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Send Message to Teachers</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="form-group"> 
                  <textarea name="message" minlength="5" id="textarea" maxlength="240" placeholder="Enter Message" class="form-control" data-error="Please Enter Message" rows="4" required></textarea>
                   <span id="rchars">0</span> /240
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
