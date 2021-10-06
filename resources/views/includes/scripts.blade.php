<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="{{ asset('assets/js/TimeCircles.js') }}"></script>
<script src="{{ asset('assets/js/popper.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('assets/vendors/nice-select/js/jquery.nice-select.min.js') }}"></script>
<script src="{{ asset('assets/vendors/owl-carousel/owl.carousel.min.js') }}"></script>
<script src="{{ asset('assets/js/owl-carousel-thumb.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.ajaxchimp.min.js') }}"></script>
<script src="{{ asset('assets/js/mail-script.js') }}"></script>
<!--gmaps Js-->
<script src="{{ asset('assets/js/jquery.sharrre.js') }}" type="text/javascript"></script>
<script src="{{ asset('assets/js/theme.js') }}"></script>
<script src="{{asset('assets/modalbox/js/placeholders.min.js')}}"></script> <!-- polyfill for the HTML5 placeholder attribute -->
<script src="{{asset('assets/modalbox/js/main.js')}}"></script> <!-- Resource JavaScript -->
<script src="{{asset('assets/js/btnloadmore.js')}}"></script>
<!-- Search Dropdown -->
<link href="{{ asset('assets/css/select2.min.css')}}" rel="stylesheet" />
<script src="{{ asset('assets/js/select2.min.js')}}"></script>
<script>
  $(document).ready( function() {
    $('.contents').btnLoadmore({
      showItem : 9,
      whenClickBtn : 3,
      textBtn : 'Load more...',
      classBtn : 'btn btn-danger'
    });
  });
</script>

 <script type="text/javascript">
    $(document).ready(function() {
   // Open active tab based on button clicked
    $('.btn-modal').on('click', function() {
      var switchTab = $(this).data('tab');   
      activaTab(switchTab);
      function activaTab(switchTab) {
          $('.nav-tabs a[href="#' + switchTab + '"]').tab('show');
      };
    });
   
   // Toggle New/Existing Customer
    var custType = $('#customer-type'),
        newCust = $('.new-customer'),
        existCust = $('.existing-customer'),
        createAccBtn = $('.create-account'),
        verifyAccBtn = $('.verify-account');
   
    custType.val($(this).is(':checked'))
            .change(function() {
    if ($(this).is(':checked')) {
          newCust.fadeToggle(400, function() { // Hide Full form when checked
            existCust.fadeToggle(500); //Display Small form when checked
            createAccBtn.toggleClass('hide');
            verifyAccBtn.toggleClass('hide');
          });
          
        } else {
          existCust.fadeToggle(400, function() { //Hide Small form when unchecked
            newCust.fadeToggle(500); //Display Full form when unchecked
            createAccBtn.toggleClass('hide');
            verifyAccBtn.toggleClass('hide');
          });
          
        }
   });
  });
//Toggle Password : Register
const togglePassword_register = document.querySelector('#r_togglePassword');
const password_register = document.querySelector('#r_password');
togglePassword_register.addEventListener('click', function (e) {
    const type = password_register.getAttribute('type') === 'password' ? 'text' : 'password';
    password_register.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
});
//Toggle Password : Login
const togglePassword_login = document.querySelector('#l_togglePassword');
const password_login = document.querySelector('#l-password');
togglePassword_login.addEventListener('click', function (e) {
    const type = password_login.getAttribute('type') === 'password' ? 'text' : 'password';
    password_login.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
});
//Toggle Password : Start your own
const togglePassword = document.querySelector('#togglePassword');
const password = document.querySelector('#password');
togglePassword.addEventListener('click', function (e) {
    const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
    password.setAttribute('type', type);
    this.classList.toggle('fa-eye-slash');
});

//Form Reset
  $(document).ready(function(){
    $('#req_button').click(function() {
       $('#req_form').trigger("reset");
    });
  });

//Div fadeout after 1 second  
  $(document).ready(function(){
    $('#show_message').delay(1000).fadeOut('slow');
  });

//Dropdown with search
$(document).ready(function() {
    $('.search_dropdown').select2();
});

//Check Data
function checkData() {
  var selectBox = document.getElementById("ncr_attend_as");
    var selectedValue = selectBox.options[selectBox.selectedIndex].value;
      if (selectedValue == 1) {
        var a = document.getElementById("total_member").value;
          if (a == null || a == "") {
              Swal.fire({
                text: "Please Enter Group Size",
                icon: 'info',
                confirmButtonText: 'Okay, got it!'
              });
              return false;
            }
          }else {
            return true;
          }
} 

//Check Search Input
function checkSearchInput() {
  var a = document.getElementById("search_text").value;
    if (a == null || a == "") {
      Swal.fire({
        text: "Please Enter Something to Search",
        icon: 'info',
        confirmButtonText: 'Okay, got it!'
      });
      return false;
    }else{
      return true;
    }
}

//Show More/Less
$('.ticket-text').each(function(){
    var text = $(this).text().trim();
    var word = "";
    var count = 0;
    var index = 0;
    for(var i=0;i<text.length;i++){ 
      if(text[i] == ' ' || text[i] == ',' || text[i] == '.'){
            if(word.trim() != ""){ 
                count++;
                word = "";
                index = i;
                if(count == 25) break; 
            }
        }else{
          word+=text[i];
        }
    }
    if(count == 25 && index+1 != text.length){ 
         html = '<span>' + text.substring(0,index)+'</span>' +'<span class="more_text" style="display:none;"> '+text.substring(index, text.length)+'</span>' + '<a href="#" class="read_more">...[Read More]</a>'
      $(this).html(html)
      $(this).find('a.read_more').click(function(event){
            $(this).toggleClass("less");
            event.preventDefault();
            if($(this).hasClass("less")){
              $(this).html("<br/>[Read Less]")
                $(this).parent().find(".more_text").show();
            }else{
              $(this).html("...[Read More]")
                $(this).parent().find(".more_text").hide();
            }
        })
    }
});

//Registration Form Validation
$(function(){
  $('form[id="registration_form"]').validate({
    rules: {
      name: {
        required: true,
        minlength: 3,
      },
      // email:{
      //   required: true,
      //   email:true,
      //   minlength:3
      // },
      contact:{
        minlength: 8,
      },
      user_type:{
        required:true,
      },
      password:{
        required:true,
        minlength:8
      }
    },
    messages: {
      name: {
        required: 'Please enter your name',
        minlength: 'Your name must consist of at least 3 characters'
      },
      // email: {
      //   required: 'Please enter your valid email address',
      //   minlength: 'Your email must consist of at least 3 characters'
      // },
      contact: {
        minlength: 'Your contact number must consist of at least 8 numbers'
      },
      user_type: 'Please select(register as?)',
      password: {
        required: 'Please enter password',
        minlength: 'Your password must consist of at least 8 characters'
      },
    }
  });
}); 

//Start your own class Form Validation
$(function(){
  $('form[id="teacher_registration_form"]').validate({
    rules: {
      name: {
        required: true,
        minlength: 3,
      },
      contact:{
        minlength: 8,
      },
      password:{
        required:true,
        minlength:8
      }
    },
    messages: {
      name: {
        required: 'Please enter your name',
        minlength: 'Your name must consist of at least 3 characters'
      },
      contact: {
        minlength: 'Your contact number must consist of at least 8 numbers'
      },
      password: {
        required: 'Please enter password',
        minlength: 'Your password must consist of at least 8 characters'
      },
    }
  });
}); 

</script> 

