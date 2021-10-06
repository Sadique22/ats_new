<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Dashboard : ATS</title>
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link href="{{ asset('assets/css/feedback.css') }}" rel="stylesheet" />
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.7.0/dist/alpine.js" defer></script>
        <link rel="stylesheet" href="{{asset('assets/dashboard/vendors/mdi/css/materialdesignicons.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/dashboard/vendors/base/vendor.bundle.base.css')}}">
        <link rel="stylesheet" href="{{asset('assets/dashboard/vendors/datatables.net-bs4/dataTables.bootstrap4.css')}}">
        <link rel="stylesheet" href="{{asset('assets/dashboard/css/style.css')}}">
        <link rel="shortcut icon" href="{{asset('assets/dashboard/images/favicon.png')}}" />
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
        <link rel="stylesheet" href="http://bootstrap-tagsinput.github.io/bootstrap-tagsinput/dist/bootstrap-tagsinput.css">
        <link href="{{ asset('assets/css/TimeCircles.css') }}" rel="stylesheet" />
        <!--Data Tables -->
        <link rel="stylesheet" type="text/css" href="{{asset('assets/datatables/css/jquery.dataTables.css')}}">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">
        <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
        <script type="text/javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
        <script type="text/javascript" src="{{asset('assets/datatables/js/jquery.dataTables.js')}}"></script>
        <script type="text/javascript" src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
         <!--Owl Corousel-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.carousel.js"></script>
        <link href="{{ asset('assets/vendors/owl-carousel/owl.carousel.min.css')}}" rel="stylesheet" />
        <!-- Countdown Timer -->
        <script src="{{ asset('assets/js/TimeCircles.js') }}"></script>
        <!--Editor-->
        <script type="text/javascript" src="{{asset('assets/dashboard/js/ckeditor.js')}}"></script>
        <!--Validator -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.9/validator.min.js"></script>
        <!--MD BOOTSTRAP -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.19.1/css/mdb.min.css" rel="stylesheet">
        <!--Sweetalerts -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10.15.5/dist/sweetalert2.all.min.js"></script>
        <script type="text/javascript" language="javascript" class="init">
            // document.onreadystatechange = function () {
            //   var state = document.readyState
            //   if (state == 'complete') {
            //       setTimeout(function(){
            //           document.getElementById('interactive');
            //          document.getElementById('load').style.visibility="hidden";
            //       },1000);
            //   }
            // }
        $.noConflict();
        jQuery( document ).ready(function( $ ) {
        $('#example').DataTable( {
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "targets": 'no-sort',
            "bSort": false,
            "bPaginate":false,
            "bInfo" : false,    
            "pagingType": "full_numbers",
            "order": [],
            "searching" : false,
            "oLanguage": {
                "sEmptyTable": "No Data Found!"
            }
            });
        });

        jQuery( document ).ready(function( $ ) {
        $('#users').DataTable( {
            "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]],
            "targets": 'no-sort',
            "bSort": false,
            "bPaginate":false,
            "bInfo" : false,    
            "pagingType": "full_numbers",
            "order": [],
            "searching" : false,
            "oLanguage": {
                "sEmptyTable": "No Data Found!"
            }
            });
        });
        </script>
  <!--New Category -->        
        <script type="text/javascript">
            function changeFunc() {
                var selectBox = document.getElementById("other_category");
                var selectedValue = selectBox.options[selectBox.selectedIndex].value;
                    if (selectedValue == "not_listed") {
                        $('#textboxes').show();
                          var a = document.forms["Form"]["other_category_name"].value;
                        if (a == null || a == "") {
                          return false;
                        }
                      }else {
                        $('#textboxes').hide();
                       }
                  }

            function checkCategory() {
                var selectBox = document.getElementById("other_category");
                var selectedValue = selectBox.options[selectBox.selectedIndex].value;
                    if (selectedValue == "not_listed") {
                        $('#textboxes').show();
                          var a = document.forms["Form"]["other_category_name"].value;
                        if (a == null || a == "") {
                            Swal.fire({
                              text: "While selecting other category option, You have to provide name for the Category",
                              icon: 'info',
                              confirmButtonText: 'Okay, got it!'
                            });
                          return false;
                        }
                      }else {
                        $('#textboxes').hide();
                       }
                  }
        </script>
        <!--tooltip -->
        <script type="text/javascript" language="javascript">
            $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            });
           // Validation Error Timeout 
            $(document).ready(function(){
              $('#show_message').delay(1000).fadeOut('slow');
            });
           //Schedule Date on the basis of live date
             $(document).ready(function () {
             $('.firstdate').on('change', function() { 
                var datearray = $('.firstdate').val();
                $('.seconddate').attr('min',datearray); 
              });
            });
            //Print Div  
            function printDiv() 
            {
              var divToPrint=document.getElementById('PrintData');
              var newWin=window.open('','Print-Window');
              newWin.document.open();
              newWin.document.write('<html><body onload="window.print()">'+divToPrint.innerHTML+'</body></html>');
              newWin.document.close();
              setTimeout(function(){newWin.close();},10);
            } 
        </script>
       
</head>
<!-- <div id="load"></div> -->
<body class="font-sans antialiased">
    @include('includes.navbar-dashboard')
    @include('includes.sidebar-dashboard')

        @yield('content')

        <main>
            {{ $slot }}
        </main>
       
    @stack('modals')
    @livewireScripts

    @include('includes.footer-dashboard')
</body>
</html>
