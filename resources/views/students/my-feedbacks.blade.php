<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
               <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                   @if(count($feedbacks) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                   My Feedbacks
                  </p>
                  @else
                  <h6 class="text-white mt-3">You have not received any Feedback yet!</h6>
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
                  <p class="card-title">All Feedbacks</p>
                  <div class="table-responsive">
                    <table id="example" class="display nowrap" style="width:100%">
                       
                      <thead class="head_color">
                        <tr>
                          @if($UserRole == 2)
                          <th>Student Name</th>
                          @else
                          <th>Teacher Name</th>
                          @endif
                          <th>Feedback</th>
                          <th>Date</th>
                          @if($UserRole == 2)
                          <th>Rating</th>
                          <th>Status</th>
                          @endif
                        </tr>
                      </thead>
                      @foreach($feedbacks as $feedback)
                      <tbody>
                         <tr>
                          <td class="bold">{{$feedback->name}}</td>
                          @if($UserRole == 2)
                          <td class="text-success bold">{{$feedback->teacher_feedback}}</td>
                          @elseif($UserRole == 3 || $UserRole == 4)
                          <td class="text-success bold">{{$feedback->progressive_feedback}}</td>
                          @endif
                          <?php
                          $originalDate = $feedback->created_at ;
                          $newDate = date("F d, Y", strtotime($originalDate));
                          ?>
                          <td>{{$newDate}}</td>
                          @if($UserRole == 2)
                          <td>
                            @for($i = 0; $i < 5; $i++)
                              <span class="ml-1">
                                <i class="fa fa-star text-warning{{ $feedback->rating <= $i ? '-o' : '' }}">
                              </i></span>
                            @endfor
                          </td>
                          <td>
                            @if($feedback->f_status == 1)
                             <a href="/decline-feedback/{{$feedback->f_id}}" onclick="return confirm('Are you sure,you want to Decline the feedback?')" class="btn btn-success">Approved</a>
                            @else
                             <a href="/approve-feedback/{{$feedback->f_id}}" class="btn btn-danger">Declined</a>
                            @endif
                          </td>
                          @endif
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
