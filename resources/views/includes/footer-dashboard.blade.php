 <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright &copy; <?php $year = date("Y");echo $year; ?> Any Time Study. All Rights Reserved</span>
          </div>
</footer>
<script type="text/javascript">
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
              reader.onload = function (e) {
                $('#blah')
                  .attr('src', e.target.result);
              };
              reader.readAsDataURL(input.files[0]);
            }
          }

        function validateImage() {
            var formData = new FormData();

            var file = document.getElementById("img").files[0];

              formData.append("Filedata", file);
              var t = file.type.split('/').pop().toLowerCase();
              if (t != "png") {
                  Swal.fire({
                    icon: 'info',
                    confirmButtonText: 'Okay, got it!',
                    text: 'Please select a PNG image file!',
                  });
                  document.getElementById("img").value = '';
                  return false;
              }
              if (file.size > 1024000) {
                  Swal.fire({
                    icon: 'info',
                    confirmButtonText: 'Okay, got it!',
                    text: 'Max Upload size is 10MB only!',
                  });
                  document.getElementById("img").value = '';
                  return false;
              }
              return true;
          }

 //Check Document File
          function validateDocFile() {
            myfile= $("#doc_file").val();
            var t = myfile.split('.').pop();
             
              if (t != "xlsx" && t != "xls" && t != "doc" && t != "docx" && t != "ppt" && t != "txt" && t != "pdf") {
                  Swal.fire({
                    icon: 'info',
                    confirmButtonText: 'Okay, got it!',
                    text: 'Please select a Valid Document File.(XLSX,XLS,DOC,DOCX,PPT,TXT,PDF)!',
                  });
                  document.getElementById("doc_file").value = '';
                  return false;
              }
              if (myfile.size > 1024000) {
                  Swal.fire({
                    icon: 'info',
                    confirmButtonText: 'Okay, got it!',
                    text: 'Max Upload size is 10MB only!',
                  });
                  document.getElementById("doc_file").value = '';
                  return false;
              }
              return true;
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

 //Check Search Input
function checkSearchInputExpertise() {
  var a = document.getElementById("search_expertise").value;
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

 //Check Search Input
function checkTaxInputs() {
  var a = document.getElementById("ats_tax_input").value;
  var b = document.getElementById("service_fees_input").value;
    if (a == null || a == "") {
      Swal.fire({
        text: "Please Enter ATS Tax",
        icon: 'info',
        confirmButtonText: 'Okay, got it!'
      });
      return false;
    }else if(b == null || b == ""){
      Swal.fire({
        text: "Please Enter Service Fees",
        icon: 'info',
        confirmButtonText: 'Okay, got it!'
      });
      return false;
    }else{
      return true;
    }
}       
</script>

<script type="text/javascript">
//Words Counter
  var maxLength = 0;
  $('textarea').keyup(function() {
    var textlen = maxLength + $(this).val().length;
    $('#rchars').text(textlen);
  });

  var Length = 0;
  $('#feedback_text').keyup(function() {
    var textlength = Length + $(this).val().length;
    $('#tchars').text(textlength);
  });

 // Clear Inputs
  $('#clear_text').click(function() {
    document.getElementById("textarea").value = '';
    $('#rchars').text(0);
  });

  $('#clear_texts').click(function() {
    document.getElementById("feedback_text").value = '';
    $('#tchars').text(0);
  });

//Dynamic Fields :Add Class Schedule
  $('.multi-field-wrapper').each(function() {
    var $wrapper = $('.multi-fields', this);
    $(".add-field", $(this)).click(function(e) {
    $('.multi-field:first-child', $wrapper).clone(true).appendTo($wrapper).find('input').val('').focus();
    });
    $('.multi-field .remove-field', $wrapper).click(function() {
      if ($('.multi-field', $wrapper).length > 1)
         $(this).parent('.multi-field').remove();
    });
});
</script>

{{--<span id="mycountry"></span>--}}
<script type="text/javascript">
  function shortString() {
  var shorts = document.querySelectorAll('.short');
  if (shorts) {
    Array.prototype.forEach.call(shorts, function(ele) {
      var str = ele.innerText,
        indt = '...';

      if (ele.hasAttribute('data-limit')) {
        if (str.length > ele.dataset.limit) {
          var result = `${str.substring(0, ele.dataset.limit - indt.length).trim()}${indt}`;
          ele.innerText = result;
          str = null;
          result = null;
        }
      } else {
        throw Error('Cannot find attribute \'data-limit\'');
      }
    });
  }
}

$(function() {
  shortString();
  
  $('#my_form').change(function(){
    // var str = "<em>First name:</em><strong> " + $( "#name" ).val() + "</strong><br><em>Last name:</em><strong> " + $( "#surname" ).val() + "</strong><br><em>My car:</em><strong> " + $( "select#cars option:selected" ).text() + "</strong><br><em>My country:</em><strong> " + $( "select#countries option:selected" ).text() + "</strong>";

    var class_name = $( "#name" ).val();
    var class_date = $( "#l_date" ).val();
    var class_level = $( "#c_level" ).val();
    var class_duration = $( "#c_duration" ).val()+ "" +"Hours/Day";
    var countries = "<strong> " + $( "select#countries option:selected" ).text() + "</strong>";

    $('#classname').html( class_name);
    $('#live_date').html( class_date);
    $('#class_duration').html( class_duration);
    $('#class_level').html( class_level);
    $('#mycountry').html( countries);
  });
  
});
</script>
 <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.min.js" integrity="sha512-9UR1ynHntZdqHnwXKTaOm1s6V9fExqejKvg5XMawEMToW4sSw+3jtLrYfZPijvnwnnE8Uol1O9BcAskoxgec+g==" crossorigin="anonymous"></script>
  <script src="{{asset('assets/dashboard/vendors/base/vendor.bundle.base.js')}}"></script>
  <script src="{{asset('assets/dashboard/vendors/chart.js/Chart.min.js')}}"></script>
  <script src="{{asset('assets/dashboard/vendors/datatables.net/jquery.dataTables.js')}}"></script>
  <script src="{{asset('assets/dashboard/vendors/datatables.net-bs4/dataTables.bootstrap4.js')}}"></script>
  <script src="{{asset('assets/dashboard/js/off-canvas.js')}}"></script>
  <script src="{{asset('assets/dashboard/js/hoverable-collapse.js')}}"></script>
  <script src="{{asset('assets/dashboard/js/template.js')}}"></script>
  <script src="{{asset('assets/dashboard/js/dashboard.js')}}"></script>
  <script src="{{asset('assets/dashboard/js/data-table.js')}}"></script>
  <script src="{{asset('assets/dashboard/js/jquery.dataTables.js')}}"></script>
  <script src="{{asset('assets/dashboard/js/dataTables.bootstrap4.js')}}"></script>
  <script src="{{asset('assets/dashboard/js/jquery.cookie.js')}}" type="text/javascript"></script>
  <script src="{{ asset('assets/vendors/owl-carousel/owl.carousel.min.js') }}"></script>
  <script src="{{ asset('assets/js/owl-carousel-thumb.min.js') }}"></script>
  <script src="{{ asset('assets/js/theme.js') }}"></script>
