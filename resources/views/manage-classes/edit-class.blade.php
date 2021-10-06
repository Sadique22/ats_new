<x-app-layout>
@section('content')
  <div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  <p class="mb-0 text-white font-weight-medium">Update Data</p>
                  <a href="/view-classes" class="btn btn-light bold" id="back-button" onclick="history.go(-1);">Go Back</a>
                </div>
              </div>
            </div>
        </div>    
        <div class="row">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
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

              @foreach($classes as $class)
                <form method="POST" action="/update-class/{{$class->id}}" name="Form" onsubmit="return checkCategory()" enctype="multipart/form-data" role="form" data-toggle="validator" id="my_form">
                   @csrf
                    <p class="card-description">
                      Update Class Information
                    </p>
                   
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Class Title <span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Title For the Class"></i></label>
                        <input type="text" value="{{$class->class_title }}" id="name" class="form-control @error('class_title') is-invalid @enderror" minlength="3" maxlength="50" name="class_title" placeholder=" Class Title" data-error="Please Enter Class Title." required>
                         <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Category <span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select Category For the Class"></i></label>
                           <select class="form-control @error('category') is-invalid @enderror" name="category" data-style="select-with-transition" data-error="Please select Category" id="other_category" onchange="changeFunc();" required>
                            <option value="{{$class->category}}">{{$class->c_name}}</option>
                          @foreach ($categories as $category)
                            <option value="{{$category->c_id}}">{{$category->c_name}}</option>
                          @endforeach
                            <option value="not_listed">Other</option>
                          </select>
                         <input name="other_category_name" placeholder="Add New" class="form-control mt-2" type="text" style="display: none" id="textboxes">
                         <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Difficulty Level <span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select Class Difficulty Level"></i></label>
                           <select class="form-control @error('class_level') is-invalid @enderror" id="c_level" name="class_level" data-style="select-with-transition" data-error="Select Class Difficulty Level" required>
                            <option value="{{$class->class_level}}">{{$class->class_level}}</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Expert">Expert</option>
                          </select>
                         <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Maximun Attendees<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Maximum Attendees for the Class"></i></label>
                        <input type="number" value="{{$class->max_attendees }}" class="form-control @error('max_attendees') is-invalid @enderror" name="max_attendees" placeholder="Maximun Attendees" data-error="Please Enter Maximun Attendees for the class" min="1" required>
                         <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Class Duration<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Duration of the Class Hours Per Day"></i></label>
                        <input type="number" step="0.01" id="c_duration" value="{{$class->class_duration}}" class="form-control @error('class_duration') is-invalid @enderror" name="class_duration"  placeholder="e.g: 1,2 or 3" data-error="Please Enter Class Duration" required>
                        <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Live Date<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select the date on which the Class will get Live"></i></label>
                        <input type="date" id="l_date" min="<?= date('Y-m-d'); ?>" value="{{$class->live_date }}" class="form-control @error('live_date') is-invalid @enderror" name="live_date" placeholder="Enter Price" data-error="Please Select Go live date" required>
                         <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>Class Description <span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Description for the Class"></i></label>
                            <textarea id="desc" placeholder="Description For the class" maxlength="800" minlength="10" class="@error('class_desc') is-invalid @enderror" name="class_desc" data-error="Please Enter Description for the Class" required="" >{{$class->class_desc }}  </textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label>What Student will Learn <span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="What Students will Learn Through this Class"></i></label>
                            <textarea id="learn" class="form-control @error('learnings') is-invalid @enderror"  placeholder="Learnings For the Class" rows="4" maxlength="800" name="learnings" data-error="Please Enter What Student will Learn" >{{ $class->learnings }}</textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>What Skills Student will gain <span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="What Skills Student will gain Through this Class"></i></label>
                            <textarea id="skills" class="form-control @error('skills_gain') is-invalid @enderror" placeholder="Skills Student will gain" name="skills_gain" rows="4" maxlength="500" data-error="Please Enter Skills">{{ $class->skills_gain }}</textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>What Resources are Required <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="What Resources are required for this Class"></i></label>
                            <textarea id="resources" class="form-control @error('resources') is-invalid @enderror"  placeholder="Resources Required" name="resources" maxlength="400" rows="4">{{ $class->resources }}</textarea>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label> Prerequisites <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Class Prerequisites"></i></label>
                            <textarea id="prerequisites" class="form-control @error('prerequisites') is-invalid @enderror"  placeholder="Class Prerequisites" name="prerequisites" rows="4" maxlength="400">{{ $class->prerequisites }}</textarea>
                        </div>
                      </div>
                       <div class="col-md-6">
                        <div class="form-group">
                          <label> FAQ <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter FAQ for the Class"></i></label>
                            <textarea id="faq" class="form-control @error('faq') is-invalid @enderror"  placeholder=" FAQ" name="faq" rows="4" maxlength="400">{{ $class->faq }}</textarea>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          @if(!empty($class->video_path))
                          <iframe width="100%" height="315" src="https://www.youtube.com/embed/{{$class->video_path}}">
                          </iframe>
                          <label class="mt-3">Enter Another Youtube Video URL <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Another Youtube Demo Class Video URL"></i></label>
                          @endif
                          @if(empty($class->video_path))
                           <label>Enter Youtube Demo Class URL <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Youtube Demo Class Video URL"></i></label>
                          @endif
                         <input type="text" value="" class="form-control @error('video_path') is-invalid @enderror" name="video_path" placeholder="Enter Youtube URL" pattern="http://www\.youtube\.com\/(.+)|https://www\.youtube\.com\/(.+)">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          @if(!empty($class->image_path))
                           <img id="cat-img" src="{{url('/') . '/' . $class->image_path}}" width="200" height="200" alt="Class Image"/>
                           <label class="mt-3">Upload Another Image for the Class <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Upload Another Image For the Class"></i></label>
                           @endif
                           @if(empty($class->image_path))
                          <label>Upload Image For the Class <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Upload Image For the Class"></i></label>
                          @endif
                         <input type='file' name="image_path" id="img" onchange="readURL(this),validateImage()" accept="image/*"/>
                         <div class="image-preview">
                          <img id="blah" src="{{asset('assets/img/upload.png')}}" width="150" height="150" alt="your image"/>
                         </div>
                        </div>
                      </div>
            
{{--Preview Of Widget for User--}}
                       <div class="col-lg-3">
                        <label>Preview of the Class Widget</label>
                          <div class="single_course">
                           <div class="course_head" style="background-color:#a3ccf7;" data-toggle="tooltip" data-placement="top" title="Widget Preview of your Designed Class">
                              <img id="cat-img" src="{{asset('assets/img/logo-white.png')}}" width="150" height="150" alt="your image"/>
                            </div>
                            <div class="course_content" data-toggle="tooltip" data-placement="top" title="Widget Preview of your Designed Class">
                              <h4 class="mt-2 bold" id="classname">{{$class->class_title}}
                              </h4>
                              <div class="author purchase">
                                <h6 class="d-inline-block bold">By: {{$name = Auth::user()->name}}</h6>
                              </div>
                              <div class="timings d-flex">
                                <i class="fa fa-calendar mr-1" aria-hidden="true"></i><span class="mr-3 bold" id="live_date">{{$class->live_date}} </span>
                                <i class="fa fa-clock-o mr-1 ml-auto" aria-hidden="true"></i> <span class="mr-3 bold" id="class_duration">{{$class->class_duration}} Hours/Day</span>
                              </div>
                              <div class="star_ratings">
                                 <span class="fa fa-star checked text-warning"></span>
                                 <span class="fa fa-star checked text-warning"></span>
                                 <span class="fa fa-star checked text-warning"></span>
                                 <span class="fa fa-star checked text-warning"></span>
                                 <span class="fa fa-star checked text-warning"></span>
                                 <h6 class="level" id="class_level"> {{$class->class_level}}</h6>
                              </div>
                            </div>
                        </div>
                      </div>
                    </div>

{{--Class Fee Calculation--}}
                  <p class="card-description">
                    Class Fees Information<span class="required-star">*</span></label> <i class="fa fa-arrow-down" aria-hidden="true"></i> 
                  </p>
                    <div class="row">
                      <div class="col-lg-3">
                        <div class="form-group">
                            <label>Class Fees in USD<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Fees for the Class(USD)"></i></label>
                            <input type="number" id="price_usd" onkeyup="getPriceUSD()" step="any" value="{{ $class->price_usd }}" class="form-control @error('price_usd') is-invalid @enderror" name="price_usd" placeholder="Class Fees" data-error="Please Enter Pricing for the Class" min="1" required>
                            <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-lg-3 mb-4">
                        <label class="text-center">Class Price Including Service Tax </label>
                          <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                              <div>
                                <h6 class="my-0 bold">ATS Service Fee : </h6>
                                <h6 class="my-0 mt-2 bold"> Tax :  </h6>
                              </div>
                              <div>
                                <h6 class="my-0 bold "> {{$ats_tax}}% </h6>
                                <h6 class="my-0 mt-2 bold"> {{$service_fees}}% </h6>
                              </div>
                            </li>
                            <li class="list-group-item ">
                              <strong class="text-dark">Total Class Price : </strong>
                              <input id="total_usd" value="{{$class->price_usd }}" name="final_price_usd" class="form-control" disabled>
                            </li>
                          </ul>
                      </div>
                      <div class="col-lg-3">
                        <div class="form-group">
                            <label>Class Fees in INR<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Indian Fees for the Class"></i></label>
                            <input type="number" id="price_inr" onkeyup="getPriceINR()" step="any" value="{{ $class->price_inr }}" class="form-control @error('price_inr') is-invalid @enderror" name="price_inr" placeholder="Class Fees" data-error="Please Enter Pricing for the Class" min="1" required>
                            <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-lg-3 mb-4">
                        <label class="text-center">Class Price Including Service Tax </label>
                          <ul class="list-group mb-3">
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                              <div>
                                <h6 class="my-0 bold">ATS Service Fee : </h6>
                                <h6 class="my-0 mt-2 bold"> Tax :  </h6>
                              </div>
                              <div>
                                <h6 class="my-0 bold "> {{$ats_tax}}% </h6>
                                <h6 class="my-0 mt-2 bold"> {{$service_fees}}% </h6>
                              </div>
                            </li>
                            <li class="list-group-item ">
                              <strong class="text-dark">Total INR Class Price : </strong>
                              <input id="total_inr" value="{{$class->price_inr }}" name="final_price_inr" class="form-control" disabled>
                            </li>
                          </ul>
                      </div>
                    </div>                              
{{--Additional Info--}}                      
                  <p class="card-description">
                    Additional Info<span class="required-star">*</span></label> <i class="fa fa-arrow-down" aria-hidden="true"></i> 
                  </p>                    
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bold text-primary">Do Want to Give Progressive Feedback to Students? <i class="fa fa-info-circle text-info" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="If you want to give Progressive Feedback to the Enrolled Students then select checkbox"></i></label>
                            @if($class->pf_status == 1)
                            <input type="checkbox" name="pf_status" value="1" checked="checked">
                            @else
                            <input type="checkbox" name="pf_status" value="1">
                            @endif
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="exampleTextarea1" class="bold text-primary">Do you provide Assessments to Students? <i class="fa fa-info-circle text-info" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="If you want to provide Assessments to the Enrolled Students then select checkbox"></i></label>
                            @if($class->assessment_status == 1)
                            <input type="checkbox" name="assessment_status" value="1" checked="checked">
                            @else
                            <input type="checkbox" name="assessment_status" value="1"> 
                            @endif
                        </div>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                            <label class="mt-1 mr-2">Edit Keywords <i class="fa fa-info-circle text-info" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Comma separated Keywords, this will help your Designed class to appear in search results easily"></i></label>
                              <input type="text" value="{{ $class->keywords }}" class="form-control @error('keywords') is-invalid @enderror" maxlength="180" name="keywords" placeholder="Enter Keywords" data-role="tagsinput" >
                          </div>
                      </div>
                    </div>

                    @if($class->status == 3)
                    <button type="submit" class="btn btn-warning" name="action" value="approval">Send for the Approval <i class="fa fa-check" aria-hidden="true"></i></button>
                    @elseif($class->status == 1 || $class->status == 0)
                    <button type="submit" class="btn btn-primary" name="action" value="update">Update Data</button>
                    @elseif($class->status == 2)
                    <button type="submit" class="btn btn-warning" name="action" value="approval">Resend for the Approval</button>
                    @endif
                    <button type="submit" data-toggle="tooltip" data-placement="bottom" title="Click to Draft your class and send for approval Later" class="btn btn-success mt-2" name="action" value="save">Save as Draft <i class="fa fa-bookmark" aria-hidden="true"></i></button>
                    <a href="/redirect" class="btn btn-light">Cancel <i class="fa fa-times" aria-hidden="true"></i>
                   </a>
                  </form>
                  @endforeach
                </div>
              </div>
            </div>
        </div>
      </div>

{{-- Tax Details --}}  
<?php
  $tax = ($ats_tax + $service_fees)/100;
?> 
     
<script>
//Class Price Calculation 
  getPriceUSD = function() 
  {
    var numVal1 = Number(document.getElementById("price_usd").value);
    var numVal2 = <?php echo $tax; ?>;
    var totalValue = numVal1 - (numVal1 * numVal2)
    document.getElementById("total_usd").value = totalValue.toFixed(2);
  }
  getPriceINR = function() 
  {
    var numVal1 = Number(document.getElementById("price_inr").value);
    var numVal2 = <?php echo $tax; ?>;
    var totalValue = numVal1 - (numVal1 * numVal2)
    document.getElementById("total_inr").value = totalValue.toFixed(2);
  } 
//description  
  ClassicEditor
    .create( document.querySelector( '#desc' ), {
      toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ,'bulletedList', 'numberedList']
    } )
    .then( editor => {
      window.editor = editor;
    } )
    .catch( err => {
      console.error( err.stack );
    } );
//learnings
    ClassicEditor
    .create( document.querySelector( '#learn' ), {
      toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ,'bulletedList', 'numberedList']
    } )
    .then( editor => {
      window.editor = editor;
    } )
    .catch( err => {
      console.error( err.stack );
    } );

    ClassicEditor
    .create( document.querySelector( '#skills' ), {
      toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ,'bulletedList', 'numberedList']
    } )
    .then( editor => {
      window.editor = editor;
    } )
    .catch( err => {
      console.error( err.stack );
    } );
//Resources
    ClassicEditor
    .create( document.querySelector( '#resources' ), {
      toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ,'bulletedList', 'numberedList']
    } )
    .then( editor => {
      window.editor = editor;
    } )
    .catch( err => {
      console.error( err.stack );
    } );
//Prerequisites
    ClassicEditor
    .create( document.querySelector( '#prerequisites' ), {
      toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ,'bulletedList', 'numberedList']
    } )
    .then( editor => {
      window.editor = editor;
    } )
    .catch( err => {
      console.error( err.stack );
    } );
//faq
    ClassicEditor
    .create( document.querySelector( '#faq' ), {
      toolbar: [ 'heading', '|', 'bold', 'italic', 'link' ,'bulletedList', 'numberedList']
    } )
    .then( editor => {
      window.editor = editor;
    } )
    .catch( err => {
      console.error( err.stack );
    } );         
</script>      
 @endsection
</x-app-layout>