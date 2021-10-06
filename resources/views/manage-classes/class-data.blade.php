@extends('layouts.main')
@section('content')
@foreach($classes as $class)
  <div class="class-details-section">
      <div class="container classes_containe">
        <div class="row">
          <div class="col-lg-6 mb-4 mb-lg-0">
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
                 <h6 class="text-white mt-4">Instructor : {{$class->name}}</h6>
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
              <form action="/checkout-user"  method="get">
                @if($access_location->countryName == "India" && $access_location->countryCode == "IN")
                  <?php 
                    $price_inr = base64_encode($class->price_inr);
                  ?>
                  <input type="hidden" name="calculate" value="{{$price_inr}}">
                @else
                  <?php 
                    $price_usd = base64_encode($class->price_usd);
                  ?>
                  <input type="hidden" name="calculate" value="{{$price_usd}}">
                @endif
                  <input type="hidden" name="class_id" value="{{$class->id}}">
                  <?php $url =  url()->current(); ?>
                  <input type="hidden" name="current_url" value="{{$url}}">
                  <input type="hidden" name="teacher_id" value="{{$class->created_by}}">
                    @if($flag == "not_enrolled")
                      @if($attendees_limit == "can_attend")
                        <button class="btn btn-light mr-2">Enroll for <span class="text-danger">
                          @if($access_location->countryName == "India" && $access_location->countryCode == "IN")
                          ₹{{$class->price_inr}}
                          @else
                          ${{$class->price_usd}}
                          @endif
                        </span></button>
                      @else
                        <h6 class="text-warning mt-2 mr-3">Max Attendees Limit Reached!</h6>
                      @endif
                    @else
                      <h6 class="text-warning mt-2 mr-3">Already Enrolled!</h6>
                    @endif
              </form>
                  <button type="button" class="btn btn-light mr-2" data-toggle="modal" data-target=".bd-example-modal-lg">Schedule<i class="fa fa-clock-o ml-1" aria-hidden="true"></i></button>
                  <button type="button" class="btn btn-light" data-toggle="modal" data-target="#exampleModal"><i class="fa fa-share-alt mr-2" aria-hidden="true"></i>Share</button>
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
  @if(isset($class->user_bio))
    <div class="alert alert-info d-flex classes_container">
      @if(isset($class->profile_photo_path))
        <img src="/storage/{{$class->profile_photo_path}}" class="rounded-circle" title="{{$class->name}}" alt="{{$class->name}}" width="50" height="50">
      @else
         <img src="{{asset ('assets/img/user.png')}}" class="rounded-circle" alt="{{$class->name}}" title="{{$class->name}}" width="50" height="50"> 
      @endif 
        <h6 class="mt-3 ml-3">{{$class->user_bio}}</h6>       
    </div>
  @endif
  <div class="container">
    <div class="row">
      <div class="col-12">
        @if(session()->has('message'))
            <div class="alert alert-success">
              <div class="container text-center">
                {{ session()->get('message') }}
              </div>
            </div>
        @endif
        @if(session()->has('fault'))
            <div class="alert alert-danger">
              <div class="container text-center">
                {{ session()->get('fault') }}
              </div>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
              <ul>
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </div>
        @endif
      </div>
    </div>
  </div>

 {{-- <div class="container">
    <div class="row">
      <div class="col-6">
        <?php $current_date = date("Y-m-d"); ?>
          @if($class->live_date > $current_date)
            <div data-date="<?php echo $class->live_date; ?>" id="count-down" >
            </div>
          @else
            <div data-date="<?php echo $class->live_date; ?>" id="end-countdown" >
            </div>
            <div class="alert alert-success">
              <div class="container">
                <h6 class="text-center mt-2"><i class="fa fa-info-circle text-primary mr-2" aria-hidden="true"></i>This Class is Already Live.</h6>       
              </div>
            </div>
          @endif
      </div>
    </div>
  </div> --}}

  <div class="popular_courses section_gap_maroon">
    <div class="container classes_container">
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

  <div class="container classes_container mt-4">
    <div class="row">
      <div class="col-lg-6">
        <div class="text-center">
          <h3 class="text-center"><strong>Class Reviews</strong><br /><i class="fa fa-angle-double-down">&nbsp;</i></h3>
        </div>
        @if(count($feedbacks) > 0 && isset($feedbacks))
        <div class="row reviews-scroll" id="style-8">
          @foreach($feedbacks as $feedback)
          <div class="col-lg-6">
            <div class="feedback-item">
              <h2 class="author-review"><i class="fa fa-user-circle">&nbsp;</i>{{$feedback->name}}</h2>
          
            <div class="feedback-content">
            <i class="fa fa-quote-left"></i>
            <div class="quote">
            <p class="feedback-user"><strong>{{$feedback->class_feedback}}</strong></p>
            </div>
            <div class="panel-footer ml-2 d-flex align-items-center justify-content-between">
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
        <h5 class="text-center text-warning">There are no Feedbacks for this Class Yet!</h5>
        @endif
      </div>
      <div class="col-lg-6">
        <div class="text-center">
          <h3 class="text-center"><strong>Teacher Reviews</strong><br /><i class="fa fa-angle-double-down">&nbsp;</i></h3>
        </div>
        @if(count($teacherfeedbacks) > 0 && isset($teacherfeedbacks))
        <div class="row reviews-scroll" id="style-8">
          @foreach($teacherfeedbacks as $feedback)
          <div class="col-lg-6">
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
        <h5 class="text-center text-warning">There are no Feedbacks for the Teacher Yet!</h5>
        @endif
      </div>
    </div>  
  </div>

  <div class="container reviews-container">
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
  </div>

{{-- Share Class Modal --}}
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Share this Class: {{$class->class_title}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <button type="button" class="btn btn-info btn-round" data-js="facebook-share"><i class="fa fa-facebook pr-1"></i> Share</button>
          <a href="" target="_blank" class="btn btn-success btn-round whatsapp-share"> <i class="fa fa-whatsapp pr-1"></i> Share </a>
            <?php
              $Url = (@$_SERVER["HTTPS"] == "on") ? "https://" : "http://";
              $Url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            ?>
          <a class="btn btn-info btn-round" href="https://twitter.com/share?url=<?php echo $Url; ?>" target="_blank"><i class="fa fa-twitter pr-1"></i> Share </a>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
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

<script type="text/javascript">
  //Facebook Class Share  
    var facebookShare = document.querySelector('[data-js="facebook-share"]');
      facebookShare.onclick = function(e) {
        e.preventDefault();
        var facebookWindow = window.open('https://www.facebook.com/sharer/sharer.php?u=' + document.URL, 'facebook-popup', 'height=350,width=600');
        if(facebookWindow.focus) { facebookWindow.focus(); }
          return false;
      }

//WhatsApp Share
    $('.whatsapp-share').click(function(){
      var whatsapp ='https://wa.me/?text='+encodeURIComponent(window.location.href);
        $(this).attr('href', whatsapp);
          window.open(this.href, '_blank');  
            });

//Dynamic Countdown    
  $(function () {  
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

    $(function () {  
    //$("#end-countdown").TimeCircles().end().fadeOut();
    $("#end-countdown").TimeCircles().destroy();
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
@endforeach   
@endsection