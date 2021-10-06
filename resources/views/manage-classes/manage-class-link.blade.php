<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
               <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                  @if(count($links_data) > 0)
                  <p class="mt-3 text-white font-weight-medium">
                   Class Links
                  </p>
                  @else
                    <h6 class="text-white mt-3">There is no Class Links available for this class yet!</h6>
                  @endif
                  <div class="d-flex">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendclasslink"> Send Class Link </button>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#sendclassrecording"> Send Class Recording Link</button>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target=".class_schedule">View Class Schedule</button>
                    <a href="/class-links/{{$id}}" class="btn btn-info adjust_btn_size">Links/Recordings Data</a>
                    <a href="/view-classes" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
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
            <div class="col-md-12 stretch-card">
              <div class="card">
                <div class="card-body">
                  <p class="card-title">Live Class Links</p>
                  <div class="table-responsive">
                    <table id="example" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                          <th>Name</th>
                          <th>For Schedule</th>
                          <th>Class Date</th>
                          <th>Class Time</th>
                          <th>Link</th>
                          <th>Message</th>
                          <th>Edit</th>
                        </tr>
                      </thead>
                      @foreach($links_data as $schedule)
                      <tbody>
                        <tr>
                         <td class="text-primary bold">{{ $c_name }} </td>
                         <td class="bold">{{ $schedule->schedule_desc }} </td>
                          <?php
                            $date = date("F d, Y", strtotime($schedule->schedule_date))
                          ?>
                         <td class="bold">{{ $date }}</td>
                         <?php
                          $time = date('h:i A', strtotime($schedule->schedule_time))
                         ?>
                         <td class="bold">{{ $time }}</td>
                         <td class="bold">{{ $schedule->class_link }} </td>
                         <td class="bold">{{ $schedule->message }} </td>
                         <td><a href="/edit-classlink/{{$schedule->s_id}}" class="text-primary"> <i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i> </a> </td>
                        </tr>
                      </tbody>
                      @endforeach
                    </table>
                    {{$links_data->links()}}
                  </div>
                </div>
              </div>
            </div>
          </div>
      </div>

@if($UserRole == 1)
      <div class="modal fade" id="sendclasslink" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <form method="post" action="/send-link" data-toggle="validator">
              @csrf
              <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Please Enter Details for the Live Class</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
              </div>
              <div class="modal-body">
                <div class="form-group">
                  <select class="form-control mb-3 @error('schedule_id') is-invalid @enderror" name="schedule_id" data-style="select-with-transition" data-error="Please Select Schedule." required>
                    <option value="">Select Schedule</option>
                      @foreach($schedule_data as $schedule)
                        <option value="{{ $schedule->s_id }}">{{$schedule->schedule_desc}}</option>
                      @endforeach
                  </select>
                  <div class="help-block with-errors"></div>
                </div>
                <div class="form-group"> 
                  <textarea rows="4" minlength="3" name="class_link" placeholder="Please Enter Live Class Link" class="form-control" data-error="Please Enter Live Class Link." required></textarea>
                    <span id="rchars">0</span> /1000
                    <div class="help-block with-errors"></div>
                </div>
                <div class="form-group">
                  <input type="text" maxlength="240" minlength="3" name="message" placeholder="Attach Any Message for the Users..." class="form-control">
                    <div class="help-block with-errors"></div>
                </div>
                <input type="hidden" name="class_id" value="{{$id}}">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                <input type="submit" name="submit" value="Send" class="btn btn-info">
            </div>
          </form>
        </div>
      </div>
    </div>
    
{{--Send Class Recording --}}
    <div class="modal fade" id="sendclassrecording" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <form method="post" action="/send-classrecording" data-toggle="validator">
          @csrf
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Please Enter Recording Link Details</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <select class="form-control mb-3 @error('schedule_id') is-invalid @enderror" name="schedule_id" data-style="select-with-transition" data-error="Please Select Schedule." required>
                <option value="">Select Class Topic</option>
                  @foreach ($class_recordings as $recording)
                    <option value="{{$recording->s_id}}">{{$recording->schedule_desc}}</option>
                  @endforeach
              </select>
              <div class="help-block with-errors"></div>
            </div>
            <div class="form-group"> 
              <textarea name="recording_link" placeholder="Please Enter Recording Link" class="form-control" rows="4" data-error="Please Enter Class Recording Link" required></textarea>
              <div class="help-block with-errors"></div>
            </div>
            <input type="hidden" name="class_id" value="{{$id}}">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
              <input type="submit" name="submit" value="SEND" class="btn btn-info">
          </div>
        </form>
      </div>
    </div>
  </div>

{{-- Class Schedule Modal --}}
  <div class="modal fade class_schedule" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border border-info">
          <div class="modal-header">
            <h5 class="modal-title text-primary bold" id="exampleModalLongTitle">Class Schedule</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            @if(count($class_schedule)>0)
              <div class="table-responsive">
                  <table class="table">
                    <thead>
                      <tr>
                        <th class="bold">Schedule Info</th>
                        <th class="bold">Schedule Date</th>
                        <th class="bold">Schedule Time</th>
                      </tr>
                    </thead>
                      @foreach ($class_schedule as $schedule)
                    <tbody>
                      <tr>
                        <td>{{ $schedule->schedule_desc }}</td>
                          <?php
                            $date = date("F d, Y", strtotime($schedule->schedule_date))
                          ?>
                        <td>{{ $date }}</td>
                        <?php
                          $time = date('h:i A', strtotime($schedule->schedule_time))
                        ?>
                        <td>{{ $time }}</td>
                      </tr>
                    </tbody>
                      @endforeach
                  </table>
              </div>
            @else
              <h6 class="text-danger bold">Schedule for this Class not Updated!</h6> 
            @endif
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
  </div>  
@endif          
@endsection
</x-app-layout>
