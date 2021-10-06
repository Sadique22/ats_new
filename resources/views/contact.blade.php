@extends('layouts.main')
@section('content') 
<!--================Home Banner Area =================-->
<section class="banner_area">
   <div class="banner_inner d-flex align-items-center">
      <div class="overlay"></div>
      <div class="container">
         <div class="row justify-content-center">
            <div class="col-lg-6">
               <div class="banner_content text-center">
                  <h2>Contact Us</h2>
                  <div class="page_link">
                     <a href="{{ url('/') }}">Home</a>
                     <a href="{{ route('contact') }}">Contact</a>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<!--================End Home Banner Area =================-->
<section class="container">
    <div class="row section_gap">
        <div class="col-md-4 contact-inner">
            <div class="">
                <i class="fa fa-phone"></i>
                <p>123-456-7890</p>
            </div>
        </div>
        <div class="col-md-4 contact-inner">
            <i class="fa fa-map-marker"></i>
            <p>Your Address Lorum Ipsum</p>
        </div>
        <div class="col-md-4 contact-inner border-0">
            <i class="fa fa-envelope"></i>
            <p>anytimestudy@gmail.com</p>
        </div>
    </div>
            
            
<!--
            <hr class="mt-4 hr-class">
            <div class="col-md-5 mt-4 ">
                <a class="bg-warning px-3 py-2 rounded text-white mb-2 d-inline-block"><i class="fa fa-phone"></i></a>
                <p>+91- 90000000</p>
            </div>
            <hr class="mt-4 hr-class">
            <div class="col-md-5 mt-4 ">
                <a class="bg-warning px-3 py-2 rounded text-white mb-2 d-inline-block"><i class="fa fa-envelope"></i></a>
                <p>Onlinetutoringbusiness@gmail.com</p>
            </div>
            <hr class="mt-4 hr-class">
-->
<div class="row section_gap pt-0">
    <div class="col-md-12">
        <div class="card border-warning rounded-0" style="border-color: #353976 !important;">
            <div class="card-header p-0">
                <div class=" text-white text-center py-2" style="background:#353976;">
                    <h3 class="text-white">Write to us:</h3>
                    <p class="m-0">We would Love to hear you.</p>
                </div>
            </div>
            
            <div class="card-body py-5 px-3">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter your name'" required="" />
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter email address" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter email address'" required="" />
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <input type="text" class="form-control" id="subject" name="subject" placeholder="Enter Subject" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Subject'" required="" />
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="form-group">
                            <textarea class="form-control" name="message" id="message" rows="4" placeholder="Enter Your Message" onfocus="this.placeholder = ''" onblur="this.placeholder = 'Enter Message'" required=""></textarea>
                        </div>
                    </div>
                    
                    <div class="col-md-12">
                        <div class="text-center mt-4">
                            <button type="submit" value="submit" name="submit" value="submit" class="btn primary-btn">SEND A MESSAGE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>
   <div class="col-sm-12 col-md-12">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d235527.45446938823!2d75.72376397472755!3d22.72391173166939!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3962fcad1b410ddb%3A0x96ec4da356240f4!2sIndore%2C%20Madhya%20Pradesh!5e0!3m2!1sen!2sin!4v1603183431680!5m2!1sen!2sin" width="100%" height="450" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
   </div>
@endsection