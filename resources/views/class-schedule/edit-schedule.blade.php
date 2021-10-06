<x-app-layout>
@section('content')
  <div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  <p class="mb-0 text-white font-weight-medium">Edit Schedule</p>
                  <a href="/view-classes" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
                </div>
              </div>
            </div>
        </div>    
        <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Please Enter Schedule Details</h4>
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

                @foreach($scheduledata as $schedule)
                <form method="POST" action="/update-schedule/{{$schedule->s_id}}" enctype="multipart/form-data" role="form" data-toggle="validator">
                   @csrf
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
	                        <label for="schedule-desc">Schedule Info (What you are going to Teach?)</label>
	                        <input type="text" maxlength="150" minlength="3" value="{{ $schedule->schedule_desc}}" class="form-control @error('schedule_desc') is-invalid @enderror" name="schedule_desc" placeholder="Enter Schedule Info.." data-error="Please Enter Schedule Information." required>
                          <div class="help-block with-errors"></div>
	                      </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="schedule-date">Schedule Date</label>
                          <input type="date" min="{{$live_date}}" value="{{ $schedule->schedule_date}}" class="form-control @error('schedule_date') is-invalid @enderror" name="schedule_date" data-error="Please Enter Date" required>
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="schedule-time">Schedule Time</label>
                          <input type="time" value="{{ $schedule->schedule_time}}" class="form-control @error('schedule_time') is-invalid @enderror" name="schedule_time" data-error="Please Enter Date" required>
                          <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <input type="hidden" name="class_id" value="{{$class_id}}">
                    </div>
                    <button type="submit" class="btn btn-primary mr-2">Update</button>
                    <a href="/view-classes" class="btn btn-light">Cancel</a>
                </form>
                @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
 @endsection
</x-app-layout>