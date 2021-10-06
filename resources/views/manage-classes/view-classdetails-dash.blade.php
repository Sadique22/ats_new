<x-app-layout>
 @section('content')
 @foreach($classes as $class)
 <div class="main-panel">
    <div class="content-wrapper">
      <div class="row">
        <div class="col-md-12 mb-3">
          <div class="card bg-gradient-primary border-0">
            <div class="card-body py-1 px-4 d-flex justify-content-between flex-wrap d-flex">
                @if($UserRole == 2 && $class->pf_status !=0)
                  @if (count($e_classes) > 0)
                    <button type="button" id="clear_text" class="btn btn-info float-right" data-toggle="modal" data-target="#sendfeedback"> Give Feedback to Enrolled Students</button>
                  @else
                    <h6 class="text-white bold mt-3">"No Student have been Enrolled to your class yet"</h6>
                  @endif
                @else
                    <h6 class="text-white bold mt-3">Class Details : {{$class->class_title}}</h6>
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
      <div class="class-details-section">
        <div class="container classes_container">
          <div class="row">
            <div class="col-lg-6">
              <h2 class="text-white">{{$class->class_title}}</h2>
                <div class="d-flex">
                  <div class="star_ratings mt-3">
                      @for($i = 0; $i < 5; $i++)
                       <span class="ml-1">
                        <i class="fa fa-star text-warning{{ $overall_rating <= $i ? '-o' : '' }}"></i>
                       </span>
                      @endfor
                  </div>
                  <div class="total_count mt-3 ml-3">
                    <p class="text-white">
                      @if($count_rating == 1)
                      {{$count_rating}} Review
                      @else
                      {{$count_rating}} Reviews
                      @endif
                    </p>
                  </div>
                </div>
                <div class="d-flex">
                  <div>
                     <h6 class="text-white mt-4">INSTRUCTOR : {{$class->name}}</h6>
                      <?php
                        $originalDate = $class->live_date ;
                        $newDate = date("F d, Y", strtotime($originalDate));
                      ?>
                      <span class="text-white"><i class="fa fa-calendar mr-1 mt-3" aria-hidden="true"></i>Live Date: {{ $newDate }}</span>
                  </div>
                  <div class="ml-auto col-lg-6 mr-3">
                    <?php $current_date = date("Y-m-d"); ?>
                      @if($class->live_date > $current_date)
                        <div data-date="<?php echo $class->live_date; ?>" id="count-down" >
                        </div>
                      @else
                        <div data-date="<?php echo $class->live_date; ?>" id="end-countdown" >
                        </div>
                        <div class="alert alert-success mt-3">
                          <h6 class="text-center"><i class="fa fa-info-circle text-primary mr-2" aria-hidden="true"></i>This Class is Already Live.</h6>       
                        </div>
                      @endif
                  </div>
                </div>

                <div class="row mt-4 ml-1 enroll">
                  <button type="button" class="btn btn-light" data-toggle="modal" data-target=".bd-example-modal-lg">Schedule<i class="fa fa-clock-o ml-1" aria-hidden="true"></i></button>
                  @if($UserRole == 3 || $UserRole == 4)
                  <button type="button" class="btn btn-light mr-2" id="clear_text" data-toggle="modal" data-target=".newschedule">Request New Schedule<i class="fa fa-clock-o ml-1" aria-hidden="true"></i></button>
                  @endif
                </div>
            </div>
            <div class="col-lg-6 class-details" style="background-color: white;">
              <h4 class="text-dark mb-4 ml-4">What you'll Learn:</h4>
              <span class="learnings-class"> {!! $class->learnings !!} </span>
              <div class="d-flex pt-2 pl-4 pr-4">
                 <h6><i class="fa fa-bar-chart" aria-hidden="true"></i> {{$class->class_level}}</h6>
                 <h6 class="ml-auto"><i class="fa fa-clock-o" aria-hidden="true"></i> {{$class->class_duration}} Hours/Day</h6>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="popular_courses mb-4">
        <div class="container classes_containe">
          <div class="row"> 
            <div class="col-lg-12">
              <h3 class="text-dark">Summary of the Course</h3>
              <span class="my-class"> {!! $class->class_desc !!} </span>
            </div>
          </div>
          <div class="row mt-4">
            <div class="col-lg-6">
              <h5 class="text-dark"> What Skills You will Gain :</h5>
              <span class="my-class"> {!! $class->skills_gain !!} </span>
            </div>
            <div class="col-lg-6">     
              <h5 class="text-dark"> Required Resources : </h5>
              <span class="my-class"> {!! $class->resources !!} </span>
            </div>
          </div>
          <div class="row mt-4">
            @if(isset($class->prerequisites))
              <div class="col-lg-6">
                <h5 class="text-dark"> Prerequisites :</h5>
                <span class="my-class"> {!! $class->prerequisites  !!} </span>
              </div>
            @endif
            @if(isset($class->faq))
              <div class="col-lg-6">
                <h5 class="text-dark"> FAQ :</h5>
                <span class="my-class"> {!! $class->faq  !!} </span>
              </div>
            @endif
          </div>
        </div>
      </div>

      <div class="container reviews-controller mt-4">
        <div class="text-center">
          <h2 class="text--center"><strong>Class Reviews</strong><br /><i class="fa fa-angle-double-down">&nbsp;</i></h2>
        </div>
        @if(count($feedbacks) > 0 && isset($feedbacks))
        <div class="row reviews-scroll" id="style-8">
          @foreach($feedbacks as $feedback)
          <div class="col-lg-4 col-xl-4">
            <div class="feedback-item">
              <h2 class="author-review"><i class="fa fa-user-circle">&nbsp;</i>{{$feedback->name}}</h2>
              <div class="feedback-content">
                <i class="fa fa-quote-left"></i>
                <div class="quote">
                <p class="feedback-user"><strong>{{$feedback->class_feedback}}</strong></p>
                </div>
                <div class="panel-footer ml-2 d-flex">
                  @for($i = 0; $i < 5; $i++)
                    <span class="ml-1">
                      <i class="fa fa-star text-warning{{ $feedback->rating <= $i ? '-o' : '' }}">
                    </i></span>
                  @endfor

                  <?php
                    $originalDate = $feedback->created_at ;
                    $newDate = date("F d, Y", strtotime($originalDate));
                  ?>
                  <p class="ml-auto date-rating">{{$newDate}}</p>
                </div>
            </div>
          </div>
          </div>
          @endforeach
        </div>
        @else
        <h4 class="text-center text-warning">There are no Feedbacks for this Class Yet!</h4>
        @endif
      </div>

     <div class="container reviews-controller mb-4 mt-3">
        <div class="text-center">
          <h2 class="text--center"><strong>Teacher Reviews</strong><br /><i class="fa fa-angle-double-down">&nbsp;</i></h2>
        </div>
        @if(count($teacherfeedbacks) > 0 && isset($teacherfeedbacks))
        <div class="row reviews-scroll" id="style-8">
          @foreach($teacherfeedbacks as $feedback)
          <div class="col-lg-4 col-xl-4">
            <div class="feedback-item">
              <h2 class="author-review"><i class="fa fa-user-circle">&nbsp;</i>{{$feedback->name}}</h2>
          
            <div class="feedback-content">
            <i class="fa fa-quote-left"></i>
            <div class="quote">
            <p class="feedback-user"><strong>{{$feedback->teacher_feedback}}</strong></p>
            </div>
            
            <div class="panel-footer d-flex align-items-center justify-content-between">
               @for($i = 0; $i < 5; $i++)
                <div class=""> 
                <span class="ml-1">
                <i class="fa fa-star text-warning{{ $feedback->rating <= $i ? '-o' : '' }}">
                </i></span>
                </div>
              @endfor
              <?php
                $originalDate = $feedback->created_at ;
                $newDate = date("F d, Y", strtotime($originalDate));
              ?>
              <p class="ml-auto date-rating">{{$newDate}}</p>
            </div>
          </div>
            </div>
          </div>
          @endforeach
        </div>
        @else
        <h4 class="text-center text-warning">There are no Feedbacks for the Teacher Yet!</h4>
        @endif
    </div>
    
    <div class="row mt-4">
        @if(!empty($class->video_path))
          <div class="col-md-6">
              <iframe width="100%" height="315" src="https://www.youtube.com/embed/{{$class->video_path}}">
              </iframe>
          </div>
        @endif
        @if(!empty($class->image_path))
          <div class="col-md-6">
            <img id="blah" src="{{url('/') . '/' . $class->image_path}}" width="100%" height="350" alt="Class Image" data-field="image_path"/>
          </div>
        @endif
    </div>


{{-- Class Schedule Modal --}}
          <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content border border-info">
                <div class="modal-header">
                  <h5 class="modal-title text-primary bold" id="exampleModalLongTitle">Class Schedule</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body">
                  @if(count($schedules)>0)
                 <div class="table-responsive">
                    <table class="table">
                      <thead>
                        <tr>
                          <th class="bold">Schedule Info</th>
                          <th class="bold">Schedule Date</th>
                          <th class="bold">Schedule Time</th>
                        </tr>
                      </thead>
                        @foreach ($schedules as $schedule)
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

{{-- Request New Schedule Modal --}}
        @if($UserRole == 3 || $UserRole == 4)
        <div class="modal fade newschedule" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
              <div class="modal-content border border-info">
                <form method="post" action="/request-new-schedule" data-toggle="validator">
                  @csrf
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Please Enter all the details for the New Schedule</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                      <div class="row">
                        <div class="col-md-12">
                          <div class="form-group">
                            <label>For which Topic you want to Request New Schedule</label>
                            <select class="form-control mb-3 @error('class_topic') is-invalid @enderror" name="class_topic" data-style="select-with-transition" data-error="Please Select Class Topic." required>
                              <option value="">Select Class Topic</option>
                                @foreach ($schedules as $schedule)
                                  <option value="{{ $schedule->schedule_desc }}">{{$schedule->schedule_desc}}</option>
                                @endforeach
                            </select>
                            <div class="help-block with-errors"></div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class="form-group">
                            <label>Willing to attend one-to one or as group?</label>
                              <select class="form-control mb-3 @error('attend_as') is-invalid @enderror" name="attend_as" data-style="select-with-transition" data-error="Please Select." required>
                              <option value="">Select</option>
                                <option value="0">One to One</option>
                                <option value="1">Group</option>
                            </select>
                            <div class="help-block with-errors"></div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <label>Select Date / Time</label>
                          <div class="form-group">
                            <input type="datetime-local" value="{{ old('topic_start_date') }}" class="form-control @error('topic_start_date') is-invalid @enderror mb-3" name="topic_start_date" data-error="Please Select Date / Time." required>
                            <div class="help-block with-errors"></div>
                          </div>
                        </div>
                        <div class="col-md-12">
                        <label>Any Message</label> 
                          <div class="form-group">
                             <textarea id="s_feedback" name="sr_message" maxlength="180" placeholder="Enter Message" class="form-control" data-error="Please Enter Message" required>{{ old('sr_message') }}</textarea>
                             <span id="ychars">0</span> /180
                              <div class="help-block with-errors"></div>
                          </div>
                        </div>
                        @foreach($classes as $class)
                         <input type="hidden" name="class_id" value="{{$class->id}}">
                         <input type="hidden" name="teacher_id" value="{{$class->created_by}}">
                        @endforeach
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                          <input type="submit" name="submit" value="Send" class="btn btn-info">
                      </div>
                    </div>
                </form>
              </div>
            </div>
          </div>
        @endif

{{-- Feedback Modal --}}
          @if($UserRole == 2)
          <div class="modal fade" id="sendfeedback" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <form method="post" action="/progressive-feedback" data-toggle="validator">
                  @csrf
                  <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Please Enter Feedback for the student</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                  </div>
                  <div class="modal-body">
                    <select class="form-control mb-3 @error('category') is-invalid @enderror" name="student_name" data-style="select-with-transition" required="required" data-error="Please select" >
                      <option value="">Select Student</option>
                        @foreach ($e_classes as $class)
                          <option value="{{$class->student_id}}">{{$class->name}}</option>
                        @endforeach
                    </select>
                    <div class="help-block with-errors"></div>
                    <textarea id="s_feedback" rows="4" minlength="0" maxlength="240" name="feedback" class="form-control" required="required" data-error="Please enter feedback" ></textarea>
                     <span id="ychars">0</span> /240
                     <div class="help-block with-errors"></div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                      <input type="submit" name="submit" value="Send" class="btn btn-info">
                  </div>
                </form>
              </div>
            </div>
          </div>
          @endif
    </div>
@endforeach
<script type="text/javascript">
//Dynamic Countdown    
    $.noConflict();
    jQuery( document ).ready(function( $ )  {  
    $("#count-down").TimeCircles({
      "animation": "smooth",
      "bg_width": 1.2,
      "fg_width": 0.1,
      "time": {
          "Days": {
              "text": "Days",
              "color": "#FFCC66",
              "show": true
          },
          "Hours": {
              "text": "Hours",
              "color": "#99CCFF",
              "show": true
          },
          "Minutes": {
              "text": "Minutes",
              "color": "#BBFFBB",
              "show": false
          },
          "Seconds": {
              "text": "Seconds",
              "color": "#FF9999",
              "show": false
          }
      }
    });
 });

  jQuery( document ).ready(function( $ )  {  
    //$("#end-countdown").TimeCircles().end().fadeOut();
    $("#end-countdown").TimeCircles().destroy();
      var WLength = 0;
    $('#s_feedback').keyup(function() {
      var textlengths = WLength + $(this).val().length;
      $('#ychars').text(textlengths);
    });
    $('#clear_text').click(function() {
    document.getElementById("s_feedback").value = '';
    $('#ychars').text(0);
    });
    });
</script>
<style type="text/css">
.learnings-class ul li{
  list-style-type: none !important;
}
.learnings-class ul li::before{ 
  content: "\00BB"; 
  color: #353976;
  font-size: 20px;
  margin-right: 10px;
} 
.learnings-class ol li{
  list-style-type: none !important;
}
.learnings-class ol li::before{ 
  content: "\00BB"; 
  color: #353976;
  font-size: 20px;
  margin-right: 10px;
}
.my-class ul li{
  list-style-type: disc !important;
}
.my-class ol li{
  list-style-type: decimal !important;
} 
</style>
@endsection
</x-app-layout>