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
                   Requested New Schedule Status
                  </p>
                  @else
                  <h6 class="text-white mt-3">You have not sent any Request yet!</h6>
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
                  <p class="card-title">All Requests</p>
                  <div class="table-responsive">
                    <table id="example" class="display nowrap" style="width:100%">
                      <thead class="head_color">
                        <tr>
                          <th>Class</th>
                          <th>Class Topic</th>
                          <th>Guide Name</th>
                          <th>Requested Time/Date</th>
                          <th>Attached Message</th>
                          <th>Request Sent</th>
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
                          <td>{{ $data->class_topic }}</td>
                          <td>{{ $data->name }}</td>
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
                            @if($data->sr_status == 1)
                             <h6 class="bold text-success">Approved</h6>
                            @elseif($data->sr_status == 0)
                             <h6 class="bold text-warning">Pending</h6>
                            @else
                            <h6 class="bold text-danger">Declined</h6>
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
@endsection
</x-app-layout>
