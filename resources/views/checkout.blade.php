@extends('layouts.main')
@section('content')

@if($flag == "promo")

	<div class="container">
	  <div class="py-3 text-center">
	    <p class="lead"></p>
	    @if(isset($message))
			<div class="alert alert-success" id="show_message">
			    <div class="container">
			      {{ $message }}
			    </div>
			</div>
		@endif
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
	  </div>
	  <a class="text-primary" href="{{$class_url}}"><i class="fa fa-arrow-circle-o-left fa-2x" aria-hidden="true"></i></a>	
	  <div class="row">
	    <div class="col-md-4 order-md-2 mb-4">
	      <h4 class="text-center mb-3">
	        <span class="text-muted text-center">Checkout</span>
	        {{--<span class="badge badge-secondary badge-pill">3</span>--}}
	      </h4>
	      <ul class="list-group mb-3">
	        <li class="list-group-item d-flex justify-content-between lh-condensed">
	           <div>
	            <h6 class="my-0">Class name</h6>
	            <?php $c_id = base64_encode($class_id);
                      $t_id = base64_encode($teacher_id);
                ?>
                <a href="class-detail/{{$c_id}}/{{$t_id}}" target="_blank">
	                <h6 class="text-primary">{{$class_name}}</h6>
	            </a>
	          </div>
	          <div>
	          <h6 class="my-0">Price</h6>
	          <span class="text-muted">
	          	@if($access_location->countryName == "India" && $access_location->countryCode == "IN")
                   ₹{{$price}}
                @else
                   ${{$price}}
                @endif
	          </span>
	          </div>
	        </li>
	        <li class="list-group-item d-flex justify-content-between bg-light">
	          <div class="text-success">
	            <h6 class="my-0">Promo code</h6>
	            <small>{{$promocode}}</small>
	          </div>
	          <span class="text-success">
	          	@if($type == "Flat discount")
	              -{{$offer}}$ {{$type}}
	            @elseif($type == "% discount")
                   -{{$offer}}%
                @elseif($type == "Free")
                   {{$type}}
	            @endif
	          </span>
	        </li>
	        <li class="list-group-item d-flex justify-content-between">
	          <strong class="text-dark">
	          	@if($access_location->countryName == "India" && $access_location->countryCode == "IN")
	              Total (INR)
	            @else
	              Total (USD)
	            @endif  
	          </strong>
	          <strong class="text-dark">
	          	@if($access_location->countryName == "India" && $access_location->countryCode == "IN")
                   ₹{{$calculate}}
                @else
                    ${{$calculate}}
                @endif
	          </strong>
	        </li>
	      </ul>
    @if($applied == "yes")
    <a href="{{$class_url}}"><span class="badge badge-warning">Remove Promocode <i class="fa fa-times-circle text" aria-hidden="true"></i></span></a>
    @else
	      <a class="btn btn-primary btn-block w-100 " data-toggle="collapse" href="#applyPromo" role="button" aria-expanded="false" aria-controls="applyPromo"> Apply Promocode <i class="fa fa-gift" aria-hidden="true"></i></a>
          <div class="form-group collapse" id="applyPromo"> 
            <form method="get" action="{{route('check.promo')}}">
              <input type="text" class="form-control mt-2" name="promo">
              <input type="hidden" name="class_id" value="{{$class_id}}">
              <input type="hidden" name="teacher_id" value="{{$teacher_id}}">
              <input type="hidden" name="price" value="{{$calculate}}">
              <input type="hidden" name="class_url" value="{{$class_url}}">
              <input type="submit" value="Check" class="btn btn-primary btn-sm mt-2 mx-auto d-block">
            </form>
          </div>
    @endif

{{--Class Payment--}}
@if($calculate != 0)
	@if($access_location->countryName != "India" && $access_location->countryCode != "IN")
	     <form class="mt-3" action="{{ route('make.payment') }}" data-toggle="validator">
	          <div class="form-group">
	          	<input type="hidden" name="class_id" value="{{$class_id}}">
	          	<input type="hidden" name="teacher_id" value="{{$teacher_id}}">
	          	<input type="hidden" name="price" value="{{$calculate}}">
	        @if($UserRole == '4')
		        <div class="form-group">
		            <label>Select Children<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select children name for whom you are enrolling this class"></i></label>
		            <div class="form-group">
		                <select class="form-control @error('child_id') is-invalid @enderror" name="child_id" data-style="select-with-transition" data-error="Select Children" required>
		                    <option value="">Select</option>
		                    @foreach($childrens as $children)
		                    <option value="{{$children->child_id}}">{{$children->child_name}}</option>
		                    @endforeach
		                 </select>
		                <div class="help-block with-errors"></div>
		            </div>
		        </div>  
		    @endif        	
	            <button type="submit" class="btn btn-primary btn-block" id="purple_bg_corner">Checkout with PayPal <i class="fa fa-paypal" aria-hidden="true"></i></button>
	          </div>
	      </form>
	@endif	      

{{-- Paytm Payment --}}
  @if($access_location->countryName == "India" && $access_location->countryCode == "IN")
	    <form class="mt-3" action="{{ route('pay.amount') }}" method="POST" role="form" data-toggle="validator">
	      	{!! csrf_field() !!}

                @if($message = Session::get('message'))
                    <p>{!! $message !!}</p>
                    <?php Session::forget('success'); ?>
                @endif
	          <div class="form-group">
	          	<input type="hidden" name="class_id" value="{{$class_id}}">
	          	<input type="hidden" name="teacher_id" value="{{$teacher_id}}">
	          	<input type="hidden" name="price" value="{{$calculate}}">
	          	<input type="hidden" name="user_name" value="{{$user_name}}">
	          	<input type="hidden" name="user_email" value="{{$user_email}}">
	          	<input type="hidden" name="user_contact" value="{{$user_contact}}">
	        @if($UserRole == '4')
		        <div class="form-group">
		            <label>Select Children<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select children name for whom you are enrolling this class"></i></label>
		            <div class="form-group">
		                <select class="form-control @error('child_id') is-invalid @enderror" name="child_id" data-style="select-with-transition" data-error="Select Children" required>
		                    <option value="">Select</option>
		                    @foreach($childrens as $children)
		                    <option value="{{$children->child_id}}">{{$children->child_name}}</option>
		                    @endforeach
		                 </select>
		                <div class="help-block with-errors"></div>
		            </div>
		        </div>  
		    @endif        	
	            <button type="submit" class="btn btn-primary btn-block" id="purple_bg_corner">Checkout with Paytm <i class="fa fa-money" aria-hidden="true"></i></button>
	          </div>
	    </form>
	@endif    
	    <button type="button" class="btn btn-primary btn-block" id="purple_bg_corner" data-toggle="modal" data-target=".bd-example-modal-lg">Checkout with Card <i class="fa fa-credit-card" aria-hidden="true"></i></button>
@else
	  <form action="/enroll/{{$class_id}}/{{$teacher_id}}"  method="get" class="mt-3" data-toggle="validator">
	      	@if($UserRole == '4')
		      	<div class="form-group">
	              <label>Select Children<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select children name for whom you are enrolling this class"></i></label>
	                <div class="form-group">
	                    <select class="form-control @error('child_id') is-invalid @enderror" name="child_id" data-style="select-with-transition" data-error="Select Children" required>
	                        <option value="">Select</option>
	                      @foreach($childrens as $children)
	                        <option value="{{$children->child_id}}">{{$children->child_name}}</option>
	                      @endforeach
	                    </select>
	                    <div class="help-block with-errors"></div>
	                </div>
	          </div>
	        @endif
          <button class="btn btn-primary btn-block w-100" id="purple_bg_corner">Proceed to Checkout<i class="fa fa-check-square-o" aria-hidden="true"></i></button>
    </form>
@endif
	</div>	    
	    <div class="col-md-8 order-md-1">
	      <h4 class="mb-3">Class Details</h4>
	        <div class="row card pt-4 pb-4">
	         @if($UserRole == '3')
	          <div class="col-md-12 mb-3">
	            <label for="firstName">User Name</label>
	            <input type="text" class="form-control" value="{{$user_name}}" disabled="disabled" required>
	          </div>
	         @endif
	          <div class="col-md-12 mb-3">
	            <label for="firstName">Teacher Name</label>
	            <input type="text" class="form-control" value="{{$teacher_name}}" disabled="disabled" required>
	          </div>
	          <div class="col-lg-12 mb-3">
		         <h3 class="text-dark">Summary of the Course</h3>
		          <span class="my-class ticket-text"> {!! $class_desc !!} </span>
		        </div>
	          <div class="schedule-details">
	          	<div class="col-md-12 mb-3">
	          	 <label for="firstName">Schedule Details of 
	          	 	<span class="text-primary">
	          	      <?php $c_id = base64_encode($class_id);
                            $t_id = base64_encode($teacher_id);
                      ?>
                      <a href="class-detail/{{$c_id}}/{{$t_id}}" target="_blank"> {{$class_name}} </a>
	          	    </span>
	             </label>
	             <h6 class="float-right mr-auto">Class Live Date :
	             	<?php
                       $originalDate = $live_date ;
                       $newDate = date("F d, Y", strtotime($originalDate));
                    ?>
	                {{$newDate}}
	             </h6>
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
	         </div>
	        </div>
	        <hr class="mb-4">
	    </div>
	  </div>
	</div>

@elseif($flag == "checkout")

	<div class="container mt-4">
		<div class="py-3 text-center">
	    <p class="lead"></p>
	    @if(isset($message))
			<div class="alert alert-success" id="show_message">
			    <div class="container">
			      {{ $message }}
			    </div>
			</div>
		@endif
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
	  </div>
        <a class="text-primary" href="{{$class_url}}"><i class="fa fa-arrow-circle-o-left fa-2x" aria-hidden="true"></i></a>
		<div class="py-2 text-center">
		    <h2>Checkout</h2>
		 </div>
	  <div class="row">
	   <div class="col-md-4 order-md-2 mb-4 mx-auto">
	      <h4 class="text-center mb-3">
	        <span class="text-muted text-center"> Payment Details</span>
	        {{--<span class="badge badge-secondary badge-pill">3</span>--}}
	      </h4>
	       
	      <ul class="list-group mb-3">
	        <li class="list-group-item d-flex justify-content-between lh-condensed">
	          <div>
	            <h6 class="my-0">Class name</h6>
	            <?php $c_id = base64_encode($class_id);
                      $t_id = base64_encode($teacher_id);
                ?>
                <a href="class-detail/{{$c_id}}/{{$t_id}}" target="_blank">
	                <h6 class="text-primary">{{$class_name}}</h6>
	            </a>
	          </div>
	          <div>
	          <h6 class="my-0">Price</h6>
	          <span class="text-muted float-right">
                @if($access_location->countryName == "India" && $access_location->countryCode == "IN")
                    ₹{{$calculate}}
                @else
                    ${{$calculate}}
                @endif
	          </span>
	          </div>
	        </li>
	        <li class="list-group-item d-flex justify-content-between">
	          <strong class="text-dark">Total 
	          	@if($access_location->countryName == "India" && $access_location->countryCode == "IN")
	              (INR)
	            @else
                  (USD)
                @endif
	          </strong>
	          <strong class="text-dark">
	          	@if($access_location->countryName == "India" && $access_location->countryCode == "IN")
	             ₹{{$calculate}}
	            @else
	             ${{$calculate}}
	            @endif
	          </strong>
	        </li>
	      </ul>
	      <a class="btn btn-primary btn-block w-100 " data-toggle="collapse" href="#applyPromo" role="button" aria-expanded="false" aria-controls="applyPromo"> Apply Promocode <i class="fa fa-gift" aria-hidden="true"></i></a>
          <div class="form-group collapse" id="applyPromo"> 
            <form method="get" action="{{route('check.promo')}}">
              <input type="text" class="form-control mt-2" name="promo">
              <input type="hidden" name="class_id" value="{{$class_id}}">
              <input type="hidden" name="teacher_id" value="{{$teacher_id}}">
              <?php $url =  basename($_SERVER['REQUEST_URI']);?>
              <input type="hidden" name="class_url" value="{{$url}}">
              <input type="submit" value="Check" class="btn btn-primary btn-sm mt-2 mx-auto d-block">
            </form>
          </div>

{{--Class Payment--}}
    @if($access_location->countryName != "India" && $access_location->countryCode != "IN")
	    <form class="mt-3" action="{{ route('make.payment') }}" role="form" data-toggle="validator">
	          <div class="form-group">
	          	<input type="hidden" name="class_id" value="{{$class_id}}">
	          	<input type="hidden" name="teacher_id" value="{{$teacher_id}}">
	          	<input type="hidden" name="price" value="{{$calculate}}">
	        @if($UserRole == '4')
		        <div class="form-group">
		            <label>Select Children<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select children name for whom you are enrolling this class"></i></label>
		            <div class="form-group">
		                <select class="form-control @error('child_id') is-invalid @enderror" name="child_id" data-style="select-with-transition" data-error="Select Children" required>
		                    <option value="">Select</option>
		                    @foreach($childrens as $children)
		                    <option value="{{$children->child_id}}">{{$children->child_name}}</option>
		                    @endforeach
		                 </select>
		                <div class="help-block with-errors"></div>
		            </div>
		        </div>  
		    @endif        	
	            <button type="submit" class="btn btn-primary btn-block" id="purple_bg_corner">Checkout With Paypal <i class="fa fa-paypal" aria-hidden="true"></i></button>
	          </div>
	    </form>
    @endif
{{-- Paytm Payment --}}
	@if($access_location->countryName == "India" && $access_location->countryCode == "IN")
	    <form class="mt-3" action="{{ route('pay.amount') }}" method="POST" role="form" data-toggle="validator">
	      	{!! csrf_field() !!}

                @if($message = Session::get('message'))
                    <p>{!! $message !!}</p>
                    <?php Session::forget('success'); ?>
                @endif
	          <div class="form-group">
	          	<input type="hidden" name="class_id" value="{{$class_id}}">
	          	<input type="hidden" name="teacher_id" value="{{$teacher_id}}">
	          	<input type="hidden" name="price" value="{{$calculate}}">
	          	<input type="hidden" name="user_name" value="{{$user_name}}">
	          	<input type="hidden" name="user_email" value="{{$user_email}}">
	          	<input type="hidden" name="user_contact" value="{{$user_contact}}">
	        @if($UserRole == '4')
		        <div class="form-group">
		            <label>Select Children<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select children name for whom you are enrolling this class"></i></label>
		            <div class="form-group">
		                <select class="form-control @error('child_id') is-invalid @enderror" name="child_id" data-style="select-with-transition" data-error="Select Children" required>
		                    <option value="">Select</option>
		                    @foreach($childrens as $children)
		                    <option value="{{$children->child_id}}">{{$children->child_name}}</option>
		                    @endforeach
		                 </select>
		                <div class="help-block with-errors"></div>
		            </div>
		        </div>  
		    @endif        	
	            <button type="submit" class="btn btn-primary btn-block" id="purple_bg_corner">Checkout With Paytm <i class="fa fa-money" aria-hidden="true"></i></button>
	          </div>
	    </form>
	@endif    

{{-- Card Payment (Stripe Payment Gateway) --}}	

          <button type="button" class="btn btn-primary btn-block form_reset" id="purple_bg_corner" data-toggle="modal" data-target=".bd-example-modal-lg">Checkout with Card <i class="fa fa-credit-card" aria-hidden="true"></i></button>

{{--Test Enrollment--}}
	    {{--  <form action="/enroll/{{$class_id}}/{{$teacher_id}}" class="mt-3" data-toggle="validator">
	      	@if($UserRole == '4')
		      	<div class="form-group">
	                <label>Select Children<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select children name for whom you are enrolling this class"></i></label>
	                <div class="form-group">
	                    <select class="form-control @error('child_id') is-invalid @enderror" name="child_id" data-style="select-with-transition" data-error="Select Children" required>
	                        <option value="">Select</option>
	                      @foreach($childrens as $children)
	                        <option value="{{$children->child_id}}">{{$children->child_name}}</option>
	                      @endforeach
	                    </select>
	                    <div class="help-block with-errors"></div>
	                </div>
	            </div>
	        @endif
                <button class="btn btn-primary btn-block" id="purple_bg_corner">Test Enroll <i class="fa fa-check-square-o" aria-hidden="true"></i></button>
          </form> --}}

	    </div>
	    <div class="col-md-8 order-md-1">
	      <h4 class="mb-3">Class Details</h4>
	        <div class="row card pt-4 pb-4">
	          @if($UserRole == '3')
	          <div class="col-md-12 mb-3">
	            <label for="firstName">User Name</label>
	            <input type="text" class="form-control" value="{{$user_name}}" disabled="disabled" required>
	          </div>
	          @endif
	          <div class="col-md-12 mb-3">
	            <label for="firstName">Teacher Name</label>
	            <input type="text" class="form-control" value="{{$teacher_name}}" disabled="disabled" required>
	          </div>
	          <div class="col-lg-12 mb-3">
		         <h3 class="text-dark">Summary of the Course</h3>
		          <span class="my-class ticket-text"> {!! $class_desc !!} </span>
		        </div>
	          <div class="schedule-details">
	          	<div class="col-md-12 mb-3">
	          	 <label for="firstName">Schedule Details of 
	          	 	<span class="text-primary">
	          	      <?php $c_id = base64_encode($class_id);
                            $t_id = base64_encode($teacher_id);
                      ?>
                      <a href="class-detail/{{$c_id}}/{{$t_id}}" target="_blank"> {{$class_name}} </a>
	          	    </span>
	             </label>
	             <h6 class="float-right mr-auto">Class Live Date :
	             	<?php
                       $originalDate = $live_date ;
                       $newDate = date("F d, Y", strtotime($originalDate));
                    ?>
	                {{$newDate}}
	             </h6>
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
	         </div>
	        </div>
	        <hr class="mb-4">
	    </div>
	  </div>
    </div>
   @endif

{{-- Card Payment Modal --}}
  <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border payment_card_image">
          <div class="modal-header">
            <h5 class="modal-title text-light bold" id="exampleModalLongTitle">Enter your Card Details</h5>
              <button type="button" class="close text-light" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
          </div>
          <div class="modal-body">
            @if (Session::has('success'))
                <div class="alert alert-success text-center">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                    <p>{{ Session::get('success') }}</p>
                </div>
            @endif
  
            <form role="form" action="{{ route('stripe.payment') }}" method="post" class="validation" data-cc-on-file="false" data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">
            @csrf
	            @if($UserRole == '4')
			      	<div class="form-group">
		                <label>Select Children<span class="required-star">*</span> <i class="fa fa-info-circle text-primary" aria-hidden="true" data-toggle="tooltip" data-placement="top" title="Select children name for whom you are enrolling this class"></i></label>
		                <div class="form-group">
		                    <select class="form-control @error('child_id') is-invalid @enderror" name="child_id" data-style="select-with-transition" data-error="Select Children" required>
		                        <option value="">Select</option>
		                      @foreach($childrens as $children)
		                        <option value="{{$children->child_id}}">{{$children->child_name}}</option>
		                      @endforeach
		                    </select>
		                    <div class="help-block with-errors"></div>
		                </div>
		            </div>
		        @endif
		        <span class="text-danger bold" id="error_payment"></span>
		        <span class="text-success bold" id="success_payment"></span>
                <div class='form-row row'>
                    <div class='col-12 form-group required'>
                        <label class='control-label text-light'>Name on Card</label> 
                        <input class='form-control' size='4' type='text'>
                    </div>
                </div>
                <div class='form-row row'>
                    <div class='col-12 form-group required'>
                        <label class='control-label text-light'>Card Number</label> 
                        <input autocomplete='off' class='form-control card-num credit' size='20' type='text'>
                    </div>
                </div>
                <div class='form-row row'>
                    <div class='col-12 col-md-4 form-group cvc required'>
                        <label class='control-label text-light'>CVV</label> 
                        <input autocomplete='off' maxlength="3" class='form-control card-cvc' placeholder='e.g 415' size='4' type='password'>
                    </div>
                    <div class='col-12 col-md-4 form-group expiration required'>
                        <label class='control-label text-light'>Expiration Month</label> <input class='form-control card-expiry-month' placeholder='MM' size='2' maxlength="2" type='text'>
                    </div>
                    <div class='col-12 col-md-4 form-group expiration required'>
                        <label class='control-label text-light'>Expiration Year</label> <input class='form-control card-expiry-year' placeholder='YYYY' size='4' maxlength="4" type='text'>
                    </div>
                </div>
                <div class='form-row row'>
                    <div class='col-md-12 hide error form-group'>
                        <div class='alert-danger alert'>Fix the errors before you begin.</div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button class="btn btn-primary btn-block" type="submit">Pay Now <i class="fa fa-credit-card" aria-hidden="true"></i></button>
                    </div>
                </div>
                <input type="hidden" name="class_id" value="{{$class_id}}">
	          	<input type="hidden" name="teacher_id" value="{{$teacher_id}}">
	          	<input type="hidden" name="price" value="{{$calculate}}">
            </form>
          </div>
          {{--<div class="modal-footer">
            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
          </div>--}}
        </div>
      </div>
  </div> 
<style type="text/css">
.my-class ul li{
  list-style-type: disc !important;
}
.my-class ol li{
  list-style-type: decimal !important;
} 
.credit-input{
	height: 45px !important;
}
</style>  
<script type="text/javascript">
    $(document).ready(function(){
        $('#show_message').delay(2000).fadeOut('slow');
    });

    $(document).ready(function(){
    $('.form_reset').click(function() {
       $('#payment-form').trigger("reset");
    });
  });
</script>  

{{--Stripe Payment Gateway--}}
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
$(function() {
	$(document).ready(function() {
    $('div.hide').hide();
    });
    var $form  = $(".validation");
  $('form.validation').bind('submit', function(e) {
    var $form  = $(".validation"),
        inputVal = ['input[type=email]', 'input[type=password]',
                    'input[type=text]', 'input[type=file]',
                    'textarea'].join(', '),
        $inputs = $form.find('.required').find(inputVal),
        $errorStatus = $form.find('div.error'),
        valid  = true;
        $errorStatus.addClass('hide');
     
        $('.has-error').removeClass('has-error');
        $inputs.each(function(i, el) {
        var $input = $(el);
        if ($input.val() === '') {
        $input.parent().addClass('has-error');
        $errorStatus.removeClass('hide');
        e.preventDefault();
        }
      });
  
    if (!$form.data('cc-on-file')) {
      e.preventDefault();
      Stripe.setPublishableKey($form.data('stripe-publishable-key'));
      Stripe.createToken({
        number: $('.card-num').val(),
        cvc: $('.card-cvc').val(),
        exp_month: $('.card-expiry-month').val(),
        exp_year: $('.card-expiry-year').val()
      }, stripeHandleResponse);
    }
  });
  
    function stripeHandleResponse(status, response) {
        if (response.error) {
            $('.error')
                .removeClass('hide')
                .find('.alert')
                .text(response.error.message);
                $('#error_payment').text("Please Enter Valid Card Details...!");
        } else {
            var token = response['id'];
            $form.find('input[type=text]').empty();
            $form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");
            $('#error_payment').hide();
            $('#success_payment').text("Payment Procceed...!");
            $form.get(0).submit();
        }
    }
  
});
</script>
<script type="text/javascript" src="{{ asset('assets/js/credit.js') }}"></script>
	<script type="text/javascript">
		jQuery(function ( $ ){
			$(".credit").credit();
		});
	</script> 
@endsection
