(function($) {
  "use strict";

  var nav_offset_top = $("header").height() + 50;
  /*-------------------------------------------------------------------------------
    Navbar 
  -------------------------------------------------------------------------------*/

  //* Navbar Fixed
  function navbarFixed() {
    if ($(".header_area").length) {
      $(window).scroll(function() {
        var scroll = $(window).scrollTop();
        if (scroll >= nav_offset_top) {
          $(".header_area").addClass("navbar_fixed");
        } else {
          $(".header_area").removeClass("navbar_fixed");
        }
      });
    }
  }
  navbarFixed();

  // Search Toggle
  $("#search_input_box").hide();
  $("#search").on("click", function() {
    $("#search_input_box").slideToggle("slow");
    $("#search_input").focus();
  });
  $("#close_search").on("click", function() {
    $("#search_input_box").slideUp("slow");
  });

  /*----------------------------------------------------*/
  /*  Course Slider
    /*----------------------------------------------------*/
  function active_course() {
    if ($(".active_course").length) {
      $(".active_course").owlCarousel({
        loop: false,
        margin: 4,
        items: 5,
        autoplay: false,
        smartSpeed: 1500,
        dots: false,
        responsiveClass: true,
        thumbs: true,
        thumbsPrerendered: false,
        navigation:false,
        nav: true,
        autoplay: 2500,
        navText: ["<img src='http://demo.anytimestudy.com/assets/img/left-white.png'>", "<img src='http://demo.anytimestudy.com/assets/img/right-white.png'>"],
        responsive: {
          0: {
            items: 1,
            margin: 0
          },
          768:{
            items: 2,
            margin: 0
          },
          991: {
            items: 3,
            margin: 5
          },
          992: {
            items: 3,
            margin: 0
          },
          1024: {
            items: 4,
            margin: 0
          },
          1280:{
            items: 5,
            margin: 0
          }
        }
      });
      $('.active_course').trigger('owl.play',2000);
    }
  }
  active_course();

  function featured_course() {
    if ($(".featured_course").length) {
      $(".featured_course").owlCarousel({
        loop: false,
        margin: 4,
        items: 5,
        autoplay: false,
        smartSpeed: 1500,
        dots: false,
        responsiveClass: true,
        thumbs: true,
        thumbsPrerendered: false,
        navigation:false,
        nav: true,
        autoplay: 2500,
        navText: ["<img src='http://demo.anytimestudy.com/assets/img/left-purple.png'>", "<img src='http://demo.anytimestudy.com/assets/img/right-purple.png'>"],
        responsive: {
          0: {
            items: 1,
            margin: 0
          },
          768:{
            items: 2,
            margin: 0
          },
          991: {
            items: 3,
            margin: 5
          },
          992: {
            items: 3,
            margin: 0
          },
          1024: {
            items: 4,
            margin: 0
          },
          1280:{
            items: 5,
            margin: 0
          }
        }
      });
      $('.featured_course').trigger('owl.play',2000);
    }
  }
  featured_course();


    function enrolled_course() {
    if ($(".enrolled_course").length) {
      $(".enrolled_course").owlCarousel({
        loop: false,
        margin: 4,
        items: 5,
        autoplay: true,
        smartSpeed: 1500,
        dots: false,
        responsiveClass: true,
        thumbs: true,
        thumbsPrerendered: false,
        //navigation:false,
        //nav: true,
        autoplay: 2500,
       // navText: ["<img src='http://demo.anytimestudy.com/assets/img/left-purple.png'>", "<img src='http://demo.anytimestudy.com/assets/img/right-purple.png'>"],
        responsive: {
          0: {
            items: 1,
            margin: 10
          },
          768:{
            items: 2,
            margin: 10
          },
          991: {
            items: 3,
            margin: 5
          },
          992: {
            items: 2,
            margin: 10  
          },
          1024: {
            items: 3,
            margin: 10
          },
          1280:{
            items: 4,
            margin: 10
          },
          1600:{
            items: 5,
            margin: 10
          }
        }
      });
      $('.enrolled_course').trigger('owl.play',2000);
    }
  }
  enrolled_course();

//Dropdown
  //$("select").niceSelect();

})(jQuery);
