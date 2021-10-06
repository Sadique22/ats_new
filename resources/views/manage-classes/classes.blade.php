@extends('layouts.main')
@section('content')
    <section class="course_details_area section_gap classes-body">
        <div class="row">
            <div class="col-lg-3 right-contents">
                <div class="card">
                  <article class="card-group-item">
                      <header class="card-header">
                        <h6 class="title">FILTER BY </h6>
                      </header>
                    <div class="filter-content">
                      <div class="card-body">
                        <form action="/advance-search" type="get">
                           <div class="category-select">
                              <label class="text-dark">Select Category:</label>
                              <select name="category_name" class="search_dropdown ">
                                <option value="">Select</option>
                                 @foreach($categories as $category)
                                  <option value="{{$category->c_id}}">{{$category->c_name}}</option>
                                 @endforeach
                              </select>
                            </div>
                            <div class="teacher-select">
                              <label class="text-dark mt-2">Select Teacher:</label>
                              <select name="teacherName" size="1" class="search_dropdown my_select_dropdown">
                                <option value="">Select</option>
                                 @foreach($teachers as $teacher)
                                  <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                                 @endforeach
                              </select>
                            </div>
                            <label class="text-dark mt-2">Enter Price Range:</label>
                            <div class="d-flex d-inline-block"> 
                             <input type="text" class="form-control price-range mr-1" name="minPrice" placeholder="Min-Price" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" maxlength="8" minlength="0"> 
                             <input type="text" class="form-control price-range" name="maxPrice" placeholder="Max-Price" onkeypress="return (event.charCode !=8 && event.charCode ==0 || (event.charCode >= 48 && event.charCode <= 57))" maxlength="8" minlength="0"> 
                            </div>
                            <hr>
                            <label class="text-dark">Select Star Rating:</label>
                            <div class="star-rating">
                                <input id="star-5" type="radio" name="rating" value="5" />
                                <label for="star-5" title="5 stars">
                                  <i class="active fa fa-star" aria-hidden="true"></i>
                                </label>
                                <input id="star-4" type="radio" name="rating" value="4" />
                                <label for="star-4" title="4 stars">
                                  <i class="active fa fa-star" aria-hidden="true"></i>
                                </label>
                                <input id="star-3" type="radio" name="rating" value="3" />
                                <label for="star-3" title="3 stars">
                                  <i class="active fa fa-star" aria-hidden="true"></i>
                                </label>
                                <input id="star-2" type="radio" name="rating" value="2" />
                                <label for="star-2" title="2 stars">
                                  <i class="active fa fa-star" aria-hidden="true"></i>
                                </label>
                                <input id="star-1" type="radio" name="rating" value="1" />
                                <label for="star-1" title="1 star">
                                  <i class="active fa fa-star" aria-hidden="true"></i>
                                </label>
                              </div>
                              <div class="keyword-search">
                                <input type="text" name="keyword" maxlength="20" class="form-control" placeholder="Search Using Keyword">
                              </div>
                            <button class="primary-btn text-uppercase enroll text-white">SEARCH</button> 
                          </form>
                      </div> 
                    </div>
                  </article> 
                </div> 
            </div>
            <div class="col-lg-6 course_details_left">
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
              {{--<form class="example mb-4" type="get" action="/search">
                <input type="text" placeholder="Search.." name="data">
                <button type="submit"><i class="fa fa-search"></i></button>
              </form>--}}
              @if(count($classes) > 0)
                <div class="row all-classes contents">
                  @foreach($classes as $class)
                    <div class="col-xs-12 col-sm-4 mb-3 pb-2">
                      <div class="single_course">
                        @if($class->category_image != null && $class->bg_color != null && $class->c_status == 1)
                          <div class="course_head" style="background-color: {{$class->bg_color}};">
                                <?php $c_id = base64_encode($class->id);
                                       $t_id = base64_encode($class->created_by)
                                ?>
                                <a href="class-detail/{{$c_id}}/{{$t_id}}">
                                 <img class="img-fluid" src="{{$class->category_image}}" alt="" />
                               </a>
                          </div>
                        @else
                          <div class="course_head" style="background-color: #cec8ff;">
                                <?php $c_id = base64_encode($class->id);
                                       $t_id = base64_encode($class->created_by)
                                ?>
                                <a href="class-detail/{{$c_id}}/{{$t_id}}">
                                 <img class="img-fluid" src="{{asset('assets/img/other-category.png')}}" alt="" />
                                </a>
                          </div>
                        @endif   
                        <div class="course_content">
                            <h4 class="mt-2">
                               <a href="class-detail/{{$c_id}}/{{$t_id}}"> {{$class->class_title}} </a>
                            </h4>
                            
                            <div class="author purchase">
                                <?php
                                  $t_id = base64_encode($class->created_by);
                                ?>
                                <a href="teacher-details/{{$t_id}}"> <h6 class="d-inline-block"> By: {{$class->name}} </h6></a>
                            </div>
                            
                            <div class="timings">
                                <?php
                                  $originalDate = $class->live_date ;
                                  $newDate = date("F d, Y", strtotime($originalDate));
                                ?>
                                <span class="mt-1 mr-1"><i class="fa fa-calendar mr-1" aria-hidden="true"></i> {{$newDate}}</span>
                                <span class="mt-2 mr-3"><i class="fa fa-clock-o mr-1" aria-hidden="true"></i>{{$class->class_duration}} Hours/day</span>
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
                    </div>
                  @endforeach
                </div>
              @else
                <h2 class="text-center text-warning">Sorry,there are no classes yet!</h2>
              @endif
            </div>
            <div class="col-lg-3 right-contents">
              @if(count($featured_class) > 0)
                <div class="card">
                  <article class="card-group-item">
                      <header class="card-header">
                        <h6 class="title">Top Classes </h6>
                      </header>
                      <div class="filter-content">
                        <div class="card-body top-class-card" id="style-8">
                          @foreach($featured_class as $class)
                            <div>
                              <?php $c_id = base64_encode($class->id);
                                   $t_id = base64_encode($class->created_by)
                              ?>
                              @if($class->category_image != null && $class->bg_color != null && $class->c_status == 1)
                               <a href="class-detail/{{$c_id}}/{{$t_id}}">
                                <img class="img-fluid" src="{{$class->category_image}}" alt="" style="background-color: {{$class->bg_color}}" /></a>
                              @else  
                                <a href="class-detail/{{$c_id}}/{{$t_id}}">
                                  <img class="img-fluid" src="{{asset('assets/img/other-category.png')}}" style="background-color: #cec8ff;" alt=""/>
                                </a> 
                              @endif  
                                  <?php $string1 = $class->class_title ;
                                      $string2 = substr($string1, 0, 16);
                                  ?>
                               <a href="class-detail/{{$c_id}}/{{$t_id}}">{{$string2}}...</a>
                            </div>
                           @endforeach 
                        </div> 
                      </div>
                  </article> 
                </div>
                @else
                <h2 class="text-center text-warning">No Classes!</h2>
                @endif
                <button type="button" class="primary-btn text-uppercase enroll rounded-0 text-white"  data-toggle="modal" data-target="#newclass-request" id="req_button">Request New Class/Topic</button>
            </div>
        </div>
    </section>
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