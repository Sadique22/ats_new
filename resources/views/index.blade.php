@extends('layouts.main')
@section('content')
<div class="hero_container mt-4 ">
    <div class="header_hero mt-4 mb-4">
    </div>
</div>
      
<div class="container classes_container hero-height">
    <div class="row">
        <div class="col-lg-8">
            <div class="main-promo">
                <div class="hero">
                    <div class="overlay">
                        <div class="text-content">
                            <h6 class="text-white">GET UPDATED WITH OUR CLASSES!</h6>
                            <h2 class="text-white">Let's Learn Something New</h2>
                            <p> Any Time Study is a unique online on demand learning platform which will helps learners to select the classes from the wide range of topics and attend as per their search and attend the live class as per their field of interest. A learner can also request for a new class if it is not available in the existing library. </p>
                            <div class="d-flex justify-content-center">
                                <a href="/all-classes" class="text-white"> <div class="button button-blue big-button">Start Learning Now</div></a>
                                <button type="button" class="primary-btn custom-btn text-uppercase enroll rounded-1 text-white ml-2"  data-toggle="modal" data-target="#newclass-request" id="req_button">Request New Class/Topic</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="featured-list">
                @foreach($latest_class as $class)
                <div class="card" title="{{$class->class_title}}">
                        <?php $c_id = base64_encode($class->id);
                              $t_id = base64_encode($class->created_by)
                        ?>
                        <a href="/class-detail/{{$c_id}}/{{$t_id}}">
                            @if($class->category_image != null && $class->bg_color != null && $class->c_status == 1)
                            <div class="latest_class" >
                              <img class="img-fluid" src="{{$class->category_image}}" alt=""  style="background-color: {{$class->bg_color}};" />
                            </div>
                            @else
                            <div class="latest_class">
                              <img class="img-fluid" src="{{asset('assets/img/other-category.png')}}" alt="" style="background-color: #cec8ff;"  />
                            </div>  
                            @endif  
                            <div class="content">
                                <h6 class="orange" id="bold">Latest</h6>
                                  <?php $string1 = $class->class_title ;
                                        $string2 = substr($string1, 0, 25);
                                  ?>
                                <p class="card-title" title="{{$class->class_title}}"> {{$string2}}..</p>
                                <h6 class="text-dark mt-1" id="bold">By: {{$class->name}}</h6>
                            </div>
                        </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
<div class="my-container">
    @if(session()->has('message'))
    <div class="error_container">
        <div class="alert alert-success" id="show_message">
            <div class="container">
                {{ session()->get('message') }}
            </div>
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
</div>

<div class="popular_courses section_gap intro_area">
    <div class="container classes_container">
        <div class="section">
            <div class="d-flex">
                <div class="maroon-title">
                    <h4 class="text-white">Upcoming Classes</h4>
                </div>
                <div class="ml-auto">
                    {{--<a href="#" class="btn btn-light btn-sm mb-3"><i class="fa fa-calendar mr-1" aria-hidden="true"></i>Calendar</a> 
                    <a href="{{ route('view.classes')}}" class="btn btn-light btn-sm float-right">See All</a> --}}
                </div>
            </div>
        </div>
        @if(count($classes) > 0)
        <div class="row">
            <div class="col-lg-12">
                <div class="owl-carousel active_course">
                    @foreach($classes as $class)
                    <div class="single_course">
                        @if($class->category_image != null && $class->bg_color != null && $class->c_status == 1)
                          <div class="course_head" style="background-color: {{$class->bg_color}};">
                                <?php $c_id = base64_encode($class->id);
                                       $t_id = base64_encode($class->created_by)
                                ?>
                                <a href="/class-detail/{{$c_id}}/{{$t_id}}">
                                 <img class="img-fluid" src="{{$class->category_image}}" alt="" />
                               </a>
                          </div>
                        @else
                          <div class="course_head" style="background-color: #cec8ff;">
                                <?php $c_id = base64_encode($class->id);
                                       $t_id = base64_encode($class->created_by)
                                ?>
                                <a href="/class-detail/{{$c_id}}/{{$t_id}}">
                                 <img class="img-fluid" src="{{asset('assets/img/other-category.png')}}" alt="" />
                                </a>
                          </div>
                        @endif   
                        <div class="course_content">
                            <h4 class="mt-2">
                               <a href="/class-detail/{{$c_id}}/{{$t_id}}"> {{$class->class_title}} </a>
                            </h4>
                            
                           <div class="author purchase">
                                <?php
                                  $t_id = base64_encode($class->created_by);
                                ?>
                                <a href="/teacher-details/{{$t_id}}"> <h6 class="d-inline-block"> By: {{$class->name}} </h6></a>
                            </div>
                            
                            <div class="timings d-flex">
                                <?php
                                  $originalDate = $class->live_date ;
                                  $newDate = date("F d, Y", strtotime($originalDate));
                                ?>
                                <span class="mt-2 mr-3"><i class="fa fa-calendar mr-1" aria-hidden="true"></i> {{$newDate}}</span>
                                <span class="mt-2 mr-3 ml-auto"><i class="fa fa-clock-o mr-1" aria-hidden="true"></i>{{$class->class_duration}} Hours/day</span>
                            </div>

                            <div class="star_ratings">
                                @for($i = 0; $i < 5; $i++)
                                 <span class="ml-1">
                                  <i class="fa fa-star text-warning{{ $class->avg_rating <= $i ? '-o' : '' }}"></i>
                                 </span>
                                @endfor
                                <h6 class="level"><i class="fa fa-bar-chart" aria-hidden="true"></i> {{$class->class_level}}</h6>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <a href="{{ route('view.classes')}}" class="btn btn-light btn-sm float-right">View All</a>
        @else
        <h2 class="text-center text-warning">Sorry,there are no classes yet!</h2>
        @endif
    </div>
</div>

<div class="popular_courses section_gap">
    <div class="container classes_container">
        @if(count($featured_class) > 0)
        <div class="row">
            <div class="col-lg-5">
                <div class="maroon-title">
                    <h4 class="mb-3">Students also like below Classes</h4>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <div class="owl-carousel featured_course">
                    @foreach($featured_class as $class)
                    <div class="single_course">
                        @if($class->category_image != null && $class->bg_color != null && $class->c_status == 1)
                          <div class="course_head" style="background-color: {{$class->bg_color}};">
                                <?php $c_id = base64_encode($class->id);
                                       $t_id = base64_encode($class->created_by)
                                ?>
                                <a href="/class-detail/{{$c_id}}/{{$t_id}}">
                                 <img class="img-fluid" src="{{$class->category_image}}" alt="" />
                               </a>
                          </div>
                        @else
                          <div class="course_head" style="background-color: #cec8ff;">
                                <?php $c_id = base64_encode($class->id);
                                       $t_id = base64_encode($class->created_by)
                                ?>
                                <a href="/class-detail/{{$c_id}}/{{$t_id}}">
                                 <img class="img-fluid" src="{{asset('assets/img/other-category.png')}}" alt="" />
                                </a>
                          </div>
                        @endif   
                        <div class="course_content">
                            <h4 class="mt-2">
                               <a href="/class-detail/{{$c_id}}/{{$t_id}}"> {{$class->class_title}} </a>
                            </h4>
                            
                          <div class="author purchase">
                                <?php
                                  $t_id = base64_encode($class->created_by);
                                ?>
                                <a href="/teacher-details/{{$t_id}}"> <h6 class="d-inline-block"> By: {{$class->name}} </h6></a>
                            </div>
                            
                             <div class="timings d-flex">
                                <?php
                                  $originalDate = $class->live_date ;
                                  $newDate = date("F d, Y", strtotime($originalDate));
                                ?>
                                <span class="mt-2 mr-3"><i class="fa fa-calendar mr-1" aria-hidden="true"></i> {{$newDate}}</span>
                                <span class="mt-2 mr-3 ml-auto"><i class="fa fa-clock-o mr-1" aria-hidden="true"></i>{{$class->class_duration}} Hours/day</span>
                            </div>

                            <div class="star_ratings">
                                @for($i = 0; $i < 5; $i++)
                                 <span class="ml-1">
                                  <i class="fa fa-star text-warning{{ $class->avg_rating <= $i ? '-o' : '' }}"></i>
                                 </span>
                                @endfor
                                <h6 class="level"><i class="fa fa-bar-chart" aria-hidden="true"></i>  {{$class->class_level}}</h6>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        <a href="{{ route('view.classes')}}" class="btn btn-dark btn-sm float-right">View All</a>
        @else
        <h2 class="text-center text-warning">Sorry,there are no classes yet!</h2>
        @endif
    </div>
</div>

<section class="about_area section_gap bg-grey">
    <div class="container classes_container">
        <div class="row h_blog_item">
            <div class="col-lg-6 d-lg-flex d-none">
                <div class="h_blog_img">
                    <img class="img-fluid" src="{{ asset ('assets/img/e-learning.jpg') }}" alt="" />
                </div>
            </div>
            <div class="col-lg-6 ">
                <div class="h_blog_text text-lg-left text-center">
                    <div class="h_blog_text_inner left right">
                        <h4>Welcome to Any Time Study</h4>
                        <p>
                           Unlike other online learning platforms, ATS is an on demand online learning platform which offers live classes where an instructor will help you to learn the topic you chose. The classes are interactive so a learner can ask questions to the instructor and clear their doubts in real time.
                        </p>
                        <p>
                            ATS offers you a wide variety of classes on various topics and we keep adding the new classes based on the feedback received from the learners.
                        </p>
                        <a class="primary-btn my_btn" href="{{ route('view.classes') }}">Learn More <i class="ti-arrow-right ml-1"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{--<div class="container classes_container mt-4 mb-4 pt-4 pb-4">
    <div class="maroon-title text-center">
        <h4 class="text-dark">Success Stories</h4>
    </div>
    <div class="row">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card profile-card profile-card1">
                <div class="card-body text-center">
                    <img class="avatar rounded-circle" src="{{ asset ('assets/img/testimonials/t1.jpg') }}" alt="Bologna">
                    <h4 class="card-title mb-2">Jesse</h4>
                    <p class="card-text">"Robert John Downey Jr.'career has included critical and popular success in his youth, followed by a period of substance abuse and legal difficulties, and a resurgence of commercial success in middle age." </p>
                </div>
                <div class="card-footer bg-grey text-center">
                    <h6>NOW AT</h6>
                    <h6>Comcast | Lead Data Scientist</h6>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card profile-card profile-card2">
                <div class="card-body text-center">
                    <img class="avatar rounded-circle" src="{{ asset ('assets/img/testimonials/t2.jpg') }}" alt="Bologna">
                    <h4 class="card-title mb-2">John</h4>
                    <p class="card-text">"Robert John Downey Jr.'career has included critical and popular success in his youth, followed by a period of substance abuse and legal difficulties, and a resurgence of commercial success in middle age."</p>
                </div>
                <div class="card-footer bg-grey text-center">
                    <h6>NOW AT</h6>
                    <h6>Raytheon | Data Scientist</h6>
                </div>
            </div>
        </div>
        
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card profile-card profile-card3">
                <div class="card-body text-center">
                    <img class="avatar rounded-circle" src="https://s3.eu-central-1.amazonaws.com/bootstrapbaymisc/blog/24_days_bootstrap/robert.jpg" alt="Bologna">
                    <h4 class="card-title mb-2">David</h4>
                    <p class="card-text">"Robert John Downey Jr.'career has included critical and popular success in his youth, followed by a period of substance abuse and legal difficulties, and a resurgence of commercial success in middle age."</p>
                </div>
                <div class="card-footer bg-grey text-center">
                    <h6>NOW AT</h6>
                    <h6>Panera Bread | Data Administrator</h6>
                </div>
            </div>
        </div>
    </div>
</div>--}}
{{-- Request New Class Modal --}}
  <div class="modal fade" id="newclass-request" tabindex="-1" name="Form" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Request New Class / Topic || Please Enter Details:</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-12 order-md-1">
              <form method="post" action="{{ route('newclas.request') }}" data-toggle="validator" id="req_form" onsubmit="return checkData()">
                @csrf
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <div class="form-group">
                      <label for="firstName">Full Name</label>
                      <input type="text" class="form-control @error('user_name') is-invalid @enderror" name="user_name" minlength="3" maxlength="25" placeholder="Enter Full Name" value="{{ old('user_name') }}" data-error="Please Enter Your Name." onkeypress="return (event.charCode > 64 && 
event.charCode < 91) || (event.charCode > 96 && event.charCode < 123) || (event.charCode == 32)" required>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <div class="form-group">
                      <label for="lastName">Contact No.</label>
                      <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="6" maxlength="15" class="form-control @error('user_contact') is-invalid @enderror" name="user_contact" placeholder="Enter Contact Number" value="{{ old('user_contact') }}" data-error="Enter Your Contact number" required>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="username">Email</label>
                      <div class="form-group">
                         <input type="email" maxlength="30" minlength="4" class="form-control @error('user_email') is-invalid @enderror" value="{{ old('user_email') }}" name="user_email" placeholder="Enter Email" data-error="Enter Your Email" required>
                         <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="col-md-6 mb-3">
                    <div class="form-group">
                      <label for="lastName">
                      How much you're willing to pay? @if($access_location->countryName == "India" && $access_location->countryCode == "IN") <span class="text-dark"> [In INR(₹)] </span> @else <span class="text-dark"> [In USD($)] </span>@endif
                      </label>
                      <input type="text" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 46 && event.charCode <= 57))" minlength="1" maxlength="10"  placeholder="willing to pay?" value="{{ old('ncr_pay') }}" class="form-control @error('ncr_pay') is-invalid @enderror" name="ncr_pay" data-error="How much amount you are willing to pay for this class?" required>
                      <div class="help-block with-errors"></div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label>Want to attend as?</label>
                        <select class="form-control mb-3 @error('ncr_attend_as') is-invalid @enderror" name="ncr_attend_as" data-style="select-with-transition" id="ncr_attend_as" data-error="Please Select." required onchange="if (this.value=='1'){this.form['total_member'].style.visibility='visible'}else {this.form['total_member'].style.visibility='hidden'};">
                          <option value="">Select</option>
                          <option value="0">One to One</option>
                          <option value="1">Group</option>
                        </select>
                        <div class="help-block with-errors"></div>
                    </div>
                    <div class="form-group"> 
                        <input type="text" id="total_member" name="total_member" style="visibility: hidden; " placeholder="Please Enter Group Size" class="form-control" data-error="Please Enter Offer." onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" minlength="1" maxlength="2">
                    </div>
                  </div>
                  <div class="col-md-6">
                    <label>When you want to Start?</label>
                      <div class="form-group mt-2">
                        <input type="date" min="<?= date('Y-m-d'); ?>" value="{{ old('ncr_start_date') }}" class="form-control @error('ncr_start_date') is-invalid @enderror mb-3" name="ncr_start_date" data-error="Please Select Date / Time." required>
                          <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <label>Specify Details About the Class / Topic</label> 
                      <div class="form-group">
                        <textarea name="ncr_class_detail" minlength="5" maxlength="400" placeholder="Enter Details" class="form-control @error('ncr_class_detail') is-invalid @enderror" data-error="Please Enter Class / Topic Details" required>{{ old('ncr_class_detail') }}</textarea>
                        <div class="help-block with-errors"></div>
                      </div>
                  </div>
                  <div class="col-md-6">
                    <label>Any Message</label> 
                      <div class="form-group">
                        <textarea name="ncr_message" maxlength="180" placeholder="Enter Message" class="form-control @error('ncr_message') is-invalid @enderror">{{ old('ncr_message') }}</textarea>
                        <div class="help-block with-errors"></div>
                      </div>
                  </div>
                </div>
                <button class="btn btn-primary btn-lg" type="submit">Send Request</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection