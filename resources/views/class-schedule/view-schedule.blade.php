<x-app-layout>
 @section('content')
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  @if(count($schedules) > 0)
                    <p class="mb-0 text-white font-weight-medium">Class Schedule</p>
                  @else
                    <h6 class="mb-0 text-white font-weight-medium">You have not Created Schedule for your Class Yet!</h6>
                  @endif
                  <div>
                    <button type="button" class="btn btn-info" data-toggle="modal" data-target=".bd-example-modal-lg">Add Schedule</button>
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
                  <div class="table-responsive">
                    <table id="users" class="table">
                      <thead class="head_color">
                        <tr>
                          <th>Class Title</th>
                          <th>Schedule Info</th>
                          <th>Schedule Date</th>
                          <th>Schedule Time</th>
                          <th>Manage</th>
                        </tr>
                      </thead>
                       @foreach ($schedules as $schedule)
                      <tbody>
                        <tr>
                          <td>
                            <?php $c_id = base64_encode($schedule->class_id);
                                  $t_id = base64_encode($schedule->teacher_id);
                            ?>
                            <a class="bold text-primary" href="/class-detail/{{$c_id}}/{{$t_id}}" target="_blank"> {{$schedule->class_title}}</a>
                          </td>
                          <td>{{ $schedule->schedule_desc }}</td>
                          <?php
                            $date = date("F d, Y", strtotime($schedule->schedule_date))
                          ?>
                          <td>{{ $date }}</td>
                          <?php
                            $time = date('h:i A', strtotime($schedule->schedule_time))
                          ?>
                          <td>{{ $time }}</td>
                          <td>
                            <a href="/schedule-edit/{{$schedule->s_id}}/{{$schedule->class_id}}" class="btn btn-info">Edit</a>
                            <a href="/schedule-delete/{{$schedule->s_id}}" class="btn btn-danger" onclick="return confirm('Are you sure you want to Delete?')">Delete</a>
                          </td>
                        </tr>
                      </tbody>
                     @endforeach
                    </table>
                    {{$schedules->links()}}
                  </div>
                </div>
              </div>
            </div>
          </div>
{{--Add Schedule Modal--}}

          <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <form method="post" action="/add-schedule" role="form" data-toggle="validator">
                  @csrf
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Please Enter Schedule Information</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="teacher_id" value="{{ $user_id }}">
                    <input type="hidden" name="class_id" value="{{ $id }}">
                      <div class="multi-field-wrapper">
                        <div class="multi-fields">
                          <div class="multi-field d-flex form-group">
                            <input type="text" maxlength="150" minlength="3" value="{{ old('schedule_desc') }}" class="form-control @error('schedule_desc') is-invalid @enderror mr-1 mt-2" name="schedule_desc[]" placeholder="What you are going to Teach?" data-error="Please Enter What are you going to Teach?" required>
                            <input type="date" min="{{$live_date}}" value="{{ old('schedule_date') }}" class="form-control @error('schedule_date') is-invalid @enderror mt-2" name="schedule_date[]" data-error="Please Enter Date" required>
                            <input type="time" value="{{ old('schedule_time') }}" class="form-control @error('schedule_time') is-invalid @enderror mt-2 ml-1" name="schedule_time[]" data-error="Please Enter Time" required>
                            <button type="button" class="remove-field btn btn-danger">Remove</button>
                          </div>
                        </div>
                            <button type="button" class="add-field btn btn-primary">Add field</button>
                      </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                    <input type="submit" name="submit" value="Add Schedule" class="btn btn-info">
                  </div>
                </form>
              </div>
            </div>
          </div> 
      </div>
@endsection
</x-app-layout>
{{-- AM/PM Schedule, i.e.12 Hour Format with AM/PM --}}                         
{{-- date('h:i A', strtotime($schedule->monday_from)) --}}
