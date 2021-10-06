<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
               <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                   @if(count($all_requests) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                   All Switch Role Requests of Students
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
                          <th>Sent By</th>
                          <th>Date</th>
                          <th>Status</th>
                        </tr>
                      </thead>
                      @foreach($all_requests as $data)
                      <tbody>
                         <tr>
                          <td class="text-primary bold"><a href="/user-details/{{$data->user_id}}" target="_blank"> {{ $data->name }}</a></td>
                          <?php
                          $date = $data->created_at; 
                          $s_date = date('d-m-Y', strtotime($date));
                          ?>
                          <td>{{$s_date}}</td>
                          <td>
                            <?php 
                              $decline = 2;
                              $accept = 1;
                            ?>
                            @if($data->tsr_status == 1)
                             <a href="/admin-approval/{{$decline}}/{{$data->tsr_id}}/{{$data->user_id}}" onclick="return confirm('Are you sure,you want to Decline the Request?')" class="btn btn-success">Approved</a>
                            @elseif($data->tsr_status == 0)
                             <a href="/admin-approval/{{$accept}}/{{$data->tsr_id}}/{{$data->user_id}}" class="btn btn-info">Approve</a>
                             <a href="/admin-approval/{{$decline}}/{{$data->tsr_id}}/{{$data->user_id}}" onclick="return confirm('Are you sure,you want to Decline the Request?')" class="btn btn-danger">Decline</a>
                            @else
                             <a href="/admin-approval/{{$accept}}/{{$data->tsr_id}}/{{$data->user_id}}" class="btn btn-danger">Declined</a>
                            @endif
                          </td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    {{$all_requests->links()}}
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
