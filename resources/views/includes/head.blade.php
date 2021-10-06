<head>
    <meta charset="utf-8" />
    <meta name="viewport"content="width=device-width, initial-scale=1, shrink-to-fit=no"/>
    <link rel="icon" href="{{ asset('assets/img/favicon.png')}}" type="image/png" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Any Time Study : Online Tutor</title>
    <script src="https://code.jquery.com/jquery-2.2.4.min.js" integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <!-- Bootstrap CSS -->
    <script src="{{ asset('assets/js/jquery-3.2.1.min.js') }}"></script>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js" type="text/javascript"></script>
    <link href="{{ asset('assets/css/bootstrap.css') }}" rel="stylesheet"/>
    <link href="{{ asset('assets/css/flaticon.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/themify-icons.css') }}" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
    <link href="{{ asset('assets/vendors/owl-carousel/owl.carousel.min.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/vendors/nice-select/css/nice-select.css') }}" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/credit.') }}css" />
    <!-- main css -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/TimeCircles.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/css/feedback.css') }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap/Jquery Validation(Bootstrap-4||Jquery Below) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
    <!-- Modal Box -->
    <link rel="stylesheet" href="{{asset('assets/modalbox/css/reset.css')}}"> <!-- CSS reset -->
    <link rel="stylesheet" href="{{asset('assets/modalbox/css/style.css')}}"> <!-- Resource style -->
    <link rel="stylesheet" href="{{asset('assets/modalbox/css/demo.css')}}"> <!-- Demo style -->
    <!-- Sweetalerts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>

    <style type="text/css">
    .fixed-top.navbar{ padding:6px; }
    </style>
</head>

<script type="text/javascript">
// Navbar    
$(document).ready(function() {
    if ($(window).width() > 992) {
        var navbar_height =  $('.navbar').outerHeight();
        $(window).scroll(function(){  
            if ($(this).scrollTop() > 300) {
         $('.navbar-wrap').css('height', navbar_height + 'px');
                 $('#navbar_top').addClass("fixed-top");
            }else{
                $('#navbar_top').removeClass("fixed-top");
                $('.navbar-wrap').css('height', 'auto');
            }   
        });
    } 
});

// dropdown:menu
$(document).ready(function() {
    $(document).on('click', '.dropdown-menu', function (e) {
      e.stopPropagation();
    });
    if ($(window).width() < 992) {
        $('.has-submenu a').click(function(e){
            e.preventDefault();
            $(this).next('.megasubmenu').toggle();

            $('.dropdown').on('hide.bs.dropdown', function () {
               $(this).find('.megasubmenu').hide();
            })
        });
    }
}); 

//Tooltip
$(function () {
$('[data-toggle="tooltip"]').tooltip();
});

//Ajax : Login Form
$(document).ready(function() {
    $("#login_form").on('submit', function(e){
        e.preventDefault();
      let email = $("input[name=email]").val();
      let password = $("input[name=password]").val();
      let _token = $("input[name='_token']").val();

      $.ajax({
       url:$(this).attr('action'),
       method:$(this).attr('method'),
       data:new FormData(this),
       processData:false,
       dataType:'json',
       contentType:false,
        success:function(response) {
          $('#loginSuccess').text("Login Successfull...! Redirecting to dashboard...");
          $("#pageloader").fadeIn();
          $('.hide_error').hide();
          location.href = "/dashboard";
        },
        error:function (response) {
          $('#loginEmailError').text(response.responseJSON.errors.email);
          // $('#loginPasswordError').text(response.responseJSON.errors.password);
         // console.log(response);
        }
       });
      });
   });

//Ajax : Registration Form
$(document).ready(function() {
    $("#registration_form").on('submit', function(e){
        e.preventDefault();

    $.ajax({
       url:$(this).attr('action'),
       method:$(this).attr('method'),
       data:new FormData(this),
       processData:false,
       dataType:'json',
       contentType:false,
    success:function(response) {
          $('#registerSuccess').text("Registration Successfull...!");
          $("#registerLoader").fadeIn();
          $('.hide_error').hide();
          $('#registerButton').hide();
          $('#registerLoadbutton').show();
          location.href = "/email/verify";
        },
    error:function (response) {
          //console.log(response);
          // $('#nameError').text(response.responseJSON.errors.name);
          $('#emailError').text(response.responseJSON.errors.email);
          // $('#user_typeError').text(response.responseJSON.errors.user_type);
          // $('#contactError').text(response.responseJSON.errors.contact);
          // $('#passwordError').text(response.responseJSON.errors.password);
        }
       });
      });
   });

//Ajax: Teacher Registration
   $(document).ready(function() {
    $("#teacher_registration_form").on('submit', function(e){
        e.preventDefault();

    $.ajax({
       url:$(this).attr('action'),
       method:$(this).attr('method'),
       data:new FormData(this),
       processData:false,
       dataType:'json',
       contentType:false,
    success:function(response) {
          $('#TregisterSuccess').text("Registration Successfull...!");
          $("#TregisterLoader").fadeIn();
          $('.hide_error').hide();
          $('#TregisterButton').hide();
          $('#TregisterLoadbutton').show();
          location.href = "/email/verify";
        },
    error:function (response) {
          //console.log(response);
          // $('#TnameError').text(response.responseJSON.errors.name);
          $('#TemailError').text(response.responseJSON.errors.email);
          // $('#Tuser_typeError').text(response.responseJSON.errors.user_type);
          // $('#TcontactError').text(response.responseJSON.errors.contact);
          // $('#TpasswordError').text(response.responseJSON.errors.password);
        }
       });
      });
   });  
</script>
