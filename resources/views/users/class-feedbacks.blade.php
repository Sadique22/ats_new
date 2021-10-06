<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  @if(count($feedbacks) > 0)
                  <p class="mb-0 text-white font-weight-medium">All Class Feedbacks <i class="fa fa-comments" aria-hidden="true"></i></p>
                  @else
                  <h6 class="text-white mt-2">Their is no Feedbacks Received for this Class Yet! <i class="fa fa-comments" aria-hidden="true"></i></h6>
                  @endif
                 <a href="/view-classes" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
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
                      <div class="d-flex justify-content-between flex-wrap">
                        <p class="card-title">All Received Class Feedback: <span class="text-danger">"{{$classname}}"</span></p>
                        <h6> <span class="text-danger bold">Overall Rating of the Class: </span>
                          @for($i = 0; $i < 5; $i++)
                            <span class="ml-1">
                              <i class="fa fa-star text-warning{{ $overall_rating <= $i ? '-o' : '' }}"></i>
                            </span>
                          @endfor
                        </h6>
                      </div>
                      <div class="table-responsive">
                          <table id="users" class="display nowrap" style="width:100%">
                          <thead class="head_color">
                            <tr>
                              <th>Student Name <i class="fa fa-user" aria-hidden="true"></i></th>
                              <th>Received Feedback <i class="fa fa-comments" aria-hidden="true"></i></th>
                              <th>Ratings <i class="fa fa-star" aria-hidden="true"></i></th>
                              <th>Date <i class="fa fa-calendar" aria-hidden="true"></i></th>
                              <th>Status</th>
                            </tr>
                          </thead>
                          <tbody>
                           @foreach($feedbacks as $feedback)
                            <tr>
                              <td>{{$feedback->name}}</td>
                              <td class="text-warning bold"> {{$feedback->class_feedback}} </td>
                              <?php
                              $originalDate = $feedback->created_at ;
                              $newDate = date("F d, Y", strtotime($originalDate));
                              ?>
                              <td> 
                                @for($i = 0; $i < 5; $i++)
                                  <span class="ml-1">
                                    <i class="fa fa-star text-warning{{ $feedback->rating <= $i ? '-o' : '' }}">
                                  </i></span>
                                @endfor
                              </td>
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
