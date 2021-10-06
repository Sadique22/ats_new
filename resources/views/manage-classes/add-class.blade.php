<x-app-layout>
@section('content')
  <div class="main-panel">
    <div class="content-wrapper">
        <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="card bg-gradient-primary border-0">
                <div class="card-body py-1 px-4 d-flex align-items-center justify-content-between flex-wrap">
                  <p class="mb-0 text-white font-weight-medium">Add New Class</p>
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
             
                <form method="POST" action="{{ route('class.insert') }}" name="Form" onsubmit="return checkCategory()" enctype="multipart/form-data" role="form" data-toggle="validator" id="my_form">
                   @csrf
                    <p class="card-description">
                      Class Information <i class="fa fa-arrow-down" aria-hidden="true"></i> 
                    </p><h6 class="text-primary bold">Please Enter all the Required Details (<span class="required-star">*</span>)</h6>
                    <div class="row">
                      <div class="col-md-4">
                        <div class="form-group">
	                        <label>Class Title <span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Title For the Class"></i></label>
	                        <input type="text" value="{{ old('class_title') }}" maxlength="50" minlength="3" id="name" class="form-control @error('class_title') is-invalid @enderror"  name="class_title" placeholder="Class Title" data-error="Enter Class Title." required>
                          <div class="help-block with-errors"></div>
	                      </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                        <label>Category <span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select Category For the Class"></i></label>
                          <select class="form-control @error('category') is-invalid @enderror" name="category" data-style="select-with-transition" data-error="Please select Category"  id="other_category" onchange="changeFunc();" required>
                            <option value="">Select</option>
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
                          <label>Youtube Demo Class URL <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Youtube Demo Class Video URL"></i></label>
                        <input type="text" value="{{ old('video_path') }}" class="form-control @error('video_path') is-invalid @enderror" name="video_path" placeholder="E.g:http://www.youtube.com/watch?v=-wtIMTCHWuI" pattern="http://www\.youtube\.com\/(.+)|https://www\.youtube\.com\/(.+)">
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Maximun Attendees<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Maximum Attendees for the Class"></i></label>
                        <input type="number" pattern="[0-9]" value="{{ old('max_attendees') }}" class="form-control @error('max_attendees') is-invalid @enderror" name="max_attendees" placeholder="Max Attendees" min="1" data-error="Enter Maximun Attendees for the class" required>
                        <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Class Duration<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Duration of the Class Hours Per Day"></i></label>
                        <input type="number" step="any" min="1" id="c_duration" value="{{ old('class_duration') }}" class="form-control @error('class_duration') is-invalid @enderror" name="class_duration" placeholder="(Hours Per Day)" data-error="Class Duration per day" required>
                        <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Live Date<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select the date on which the Class will get Live"></i></label>
                        <input type="date" min="<?= date('Y-m-d'); ?>" id="l_date" value="{{ old('live_date') }}" class="firstdate form-control @error('live_date') is-invalid @enderror" name="live_date" placeholder="Enter Price" data-error="Live date" required>
                        <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-3">
                        <div class="form-group">
                          <label>Difficulty Level<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select Class Difficulty Level"></i></label>
                          <select class="form-control @error('class_level') is-invalid @enderror" name="class_level" data-style="select-with-transition" id="c_level" data-error="Select Difficulty Level" required>
                            <option value="">Select</option>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Expert">Expert</option>
                          </select>
                        <div class="help-block with-errors"></div>
                        </div>
                      </div>
                        <div class="col-md-12">
  	                    <div class="form-group">
  	                      <label>Class Description <span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Description for the Class"></i></label>
                            <textarea id="desc" class="@error('class_desc') is-invalid @enderror form-control" placeholder="Description for the Class" maxlength="800" minlength="10" name="class_desc" data-error="Please Enter Description for the Class">{{ old('class_desc') }}</textarea>
                            <div class="help-block with-errors"></div>
  	                    </div>
                      </div>

                      <div class="col-md-6">
                        <div class="form-group">
                          <label>What Student will Learn <span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="What Students will Learn Through this Class"></i></label>
                            <textarea id="learn" class="@error('learnings') is-invalid @enderror" placeholder=" Learnings For the Class" maxlength="800" minlength="10" name="learnings" data-error="Please Enter What Student will Learn">{{ old('learnings') }}</textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>What Skills Student will gain <span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="What Skills Student will gain Through this Class"></i></label>
                            <textarea id="skills" placeholder="Skills Student Will Gain" maxlength="500" minlength="10" class="@error('skills_gain') is-invalid @enderror" name="skills_gain" data-error="Please Enter Skills">{{ old('skills_gain') }}</textarea>
                            <div class="help-block with-errors"></div>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>What Resources are Required <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="What Resources are required for this Class"></i></label>
                            <textarea id="resources" maxlength="400" class="@error('resources') is-invalid @enderror" placeholder="Resources Required" name="resources" >{{ old('resources') }}</textarea>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <div class="form-group">
                          <label>Class Prerequisites <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Class Prerequisites"></i></label>
                            <textarea id="prerequisites" placeholder="Prerequisites" maxlength="400" class="@error('prerequisites') is-invalid @enderror" name="prerequisites">{{ old('prerequisites') }}</textarea>
                        </div>
                      </div>
                      <div class="col-md-5">
                        <div class="form-group">
                          <label>FAQ <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter FAQ for the Class"></i></label>
                            <textarea id="faq" placeholder="FAQ" maxlength="400" class="@error('faq') is-invalid @enderror" name="faq">{{ old('faq') }}</textarea>
                        </div>
                      </div>
                      <div class="col-md-4">
                        <div class="form-group">
                          <label>Upload Image For the Class <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Upload Image For the Class"></i></label>
                         <input type='file' name="image_path" id="img" onchange="readURL(this),validateImage()" accept="image/*"/>
                        </div>
                        <div class="image-preview">
                          <img id="blah" src="{{asset('assets/img/upload.png')}}" width="150" height="150" alt="your image"/>
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
                              <h4 class="mt-2 bold" id="classname">
                              </h4>
                              <div class="author purchase">
                                <h6 class="d-inline-block bold">By: {{$name = Auth::user()->name}}</h6>
                              </div>
                              <div class="timings d-flex">
                                <i class="fa fa-calendar mr-1" aria-hidden="true"></i><span class="mr-3 bold" id="live_date"> </span>
                                <i class="fa fa-clock-o mr-1 ml-auto" aria-hidden="true"></i> <span class="mr-3 bold" id="class_duration"></span>
                              </div>
                              <div class="star_ratings">
                                 <span class="fa fa-star checked text-warning"></span>
                                 <span class="fa fa-star checked text-warning"></span>
                                 <span class="fa fa-star checked text-warning"></span>
                                 <span class="fa fa-star checked text-warning"></span>
                                 <span class="fa fa-star checked text-warning"></span>
                                 <h6 class="level" id="class_level"><i class="fa fa-bar-chart" aria-hidden="true"></i> 
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
                            <input type="number" id="price_usd" onkeyup="getPriceUSD()" step="any" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror" name="price_usd" placeholder="Class Fees" data-error="Please Enter Pricing for the Class" min="1" required>
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
                              <strong class="text-dark">Total USD Class Price : </strong>
                              <input id="total_usd" name="final_price_usd" class="form-control" disabled>
                            </li>
                          </ul>
                      </div>
                      <div class="col-lg-3">
                        <div class="form-group">
                            <label>Class Fees in INR<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Indian Fees for the Class"></i></label>
                            <input type="number" id="price_inr" onkeyup="getPriceINR()" step="any" value="{{ old('price') }}" class="form-control @error('price') is-invalid @enderror" name="price_inr" placeholder="Class Fees" data-error="Please Enter Pricing for the Class" min="1" required>
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
                              <input id="total_inr" name="final_price_inr" class="form-control" disabled>
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
                          <label class="bold text-primary">Do Want to Give Progressive Feedback to Students? <i class="fa fa-info-circle text-info mr-2" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="If you want to give Progressive Feedback to the Enrolled Students then select checkbox"></i></label>
                            <input type="checkbox" class="my-checkbox" name="pf_status" value="1">
                        </div>
                      </div>
                      <div class="col-md-12">
                        <div class="form-group">
                          <label class="bold text-primary">Do you provide Assessments to Students? <i class="fa fa-info-circle text-info mr-2" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="If you want to provide Assessments to the Enrolled Students then select checkbox"></i></label>
                            <input type="checkbox" class="my-checkbox" name="assessment_status" value="1">
                        </div>
                      </div>
                      <div class="col-md-12">
                          <div class="form-group">
                            <label class="mt-1 mr-2">Please Enter Comma separated Keywords <i class="fa fa-info-circle text-info" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Enter Comma separated Keywords, this will help your Designed class to appear in search results easily"></i></label>
                              <input type="text" maxlength="180" value="{{ old('keywords') }}" class="form-control @error('keywords') is-invalid @enderror" name="keywords" placeholder="Enter Keywords" data-role="tagsinput" >
                          </div>
                      </div>
                    </div>

{{--Class Schedule--}}

                  <p class="card-description">
                    Class Schedule <span class="required-star">*</span></label> <i class="fa fa-info-circle text-info" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Enter Schedule for the Class,i.e.What Day you are Going to teach what? (You can edit/add schedule later also)"></i> <i class="fa fa-arrow-down" aria-hidden="true"></i> 
                  </p>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <div class="multi-field-wrapper">
                          <div class="multi-fields">
                            <div class="multi-field d-flex">
                              <input type="text" maxlength="150" minlength="3" value="{{ old('schedule') }}" class="form-control @error('schedule') is-invalid @enderror mr-1 mt-2" name="schedule[]" placeholder="What you are going to Teach?" data-error="Please Enter Schedule Details (You can Edit/Add Schedule Later Also)" required >
                               <input type="date" min=""  value="{{ old('schedule_date') }}" class="seconddate form-control @error('schedule_date') is-invalid @enderror mt-2" name="schedule_date[]" required >
                               <input type="time" value="{{ old('schedule_time') }}" class="form-control @error('schedule_time') is-invalid @enderror mt-2" name="schedule_time[]" required >
                              <button type="button" class="remove-field btn btn-danger">Remove</button>
                            </div>
                          </div>
                          <div class="help-block with-errors"></div>
                          <button type="button" class="add-field btn btn-primary">Add field</button>
                        </div>
                      </div>
                    </div>
                  </div>
                  
<hr class="schedule-end">

                    <button type="submit" class="btn btn-primary mt-4" name="action" value="submit">Send for the Approval <i class="fa fa-check" aria-hidden="true"></i></button>
                    <button type="submit" data-toggle="tooltip" data-placement="bottom" title="Click to Draft your class and send for approval Later" class="btn btn-warning mt-4" name="action" value="save">Save as Draft <i class="fa fa-bookmark" aria-hidden="true"></i></button>
                    <button class="btn btn-light mt-4" onclick="history.go(-1);">Cancel <i class="fa fa-times" aria-hidden="true"></i></button>
                    
                  </form>
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