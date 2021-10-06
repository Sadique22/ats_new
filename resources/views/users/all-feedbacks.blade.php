<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  @if(count($feedbacks) > 0)
                  <p class="mb-0 text-white font-weight-medium">All Feedbacks <i class="fa fa-comments" aria-hidden="true"></i></p>
                  @else
                  <h6 class="text-white mt-2">User Doesn't Received any Feedback Yet! <i class="fa fa-comments" aria-hidden="true"></i></h6>
                  @endif
                  <button class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</button>
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
                <div class="col-md-12 stretch-card">
                  <div class="card">
                    <div class="card-body">
                      @if($u_type == 2)
                      <p class="card-title">Received Feedbacks of Teacher: <span class="text-danger bold">"{{$username}}"</span> </p>
                      @elseif($u_type == 3)
                      <p class="card-title">Received Feedbacks of Student: <span class="text-danger bold">"{{$username}}"</span></p>
                      @endif
                      <div class="table-responsive">
                          <table id="users" class="display nowrap" style="width:100%">
                          <thead class="head_color">
                            <tr>
                              @if($u_type == 2)
                              <th>Student Name <i class="fa fa-user" aria-hidden="true"></i></th>
                              @elseif($u_type == 3)
                              <th>Teacher Name <i class="fa fa-user" aria-hidden="true"></i></th>
                              @endif
                              <th>Received Feedback <i class="fa fa-comments" aria-hidden="true"></i></th>
                              <th>Date <i class="fa fa-calendar" aria-hidden="true"></i></th>
                              <th>Status</th>
                            </tr>
                          </thead>
                          <tbody>
                           @foreach($feedbacks as $feedback)
                            <tr>
                              <td>{{$feedback->name}}</td>
                              @if($u_type == 2)
                              <td class="text-primary bold"> {{$feedback->teacher_feedback}} </td>
                              @elseif($u_type == 3)
                              <td class="text-warning bold"> {{$feedback->progressive_feedback}} </td>
                              @endif
                              <?php
                              $originalDate = $feedback->created_at ;
                              $newDate = date("F d, Y", strtotime($originalDate));
                              ?>
                              <td>{{$newDate}}</td>
                              <td>
                                @if($feedback->f_status == 1)
                                 <a href="/decline-feedback/{{$feedback->f_id}}" onclick="return confirm('Are you sure,you want to Decline the feedback?')" class="btn btn-success">Approved</a>
                                @else
                                 <a href="/approve-feedback/{{$feedback->f_id}}" class="btn btn-danger">Declined</a>
                                @endif
                              </td>
                            </tr>
                          </tbody>
                         @endforeach
                        </table>
                        {{$feedbacks->links()}}
                      </div>
                    </div>
                  </div>
                </div>
            </div>
        </div>
       
@endsection
</x-app-layout>
