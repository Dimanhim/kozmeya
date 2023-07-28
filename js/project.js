// vertical slider
var galleryTop = new Swiper('.photos-slider', {
  direction: 'vertical',
  mousewheel: true
});

var galleryThumbs = new Swiper('.photos-nav', {
  direction: 'vertical',
  spaceBetween: 20,
  slidesPerView: 4,
  touchRatio: 0.2,
  slideToClickedSlide: true,
  navigation: {
    nextEl: '.photos-next',
    prevEl: '.photos-prev',
  },
});


$(".photos-nav .swiper-slide").click(function(){

  galleryTop.slideTo(galleryThumbs.clickedIndex);

});


$(".photos-nav .swiper-wrapper .swiper-slide").each(function(index){
  if(index == galleryTop.activeIndex){
    $(this).addClass("select")
  } else{
    $(this).removeClass("select")
  }
});

galleryTop.on('slideChange', function () {

  galleryThumbs.slideTo(galleryTop.activeIndex);

  $(".photos-nav .swiper-wrapper .swiper-slide").each(function(index){
    if(index == galleryTop.activeIndex){
      $(this).addClass("select")
    } else{
      $(this).removeClass("select")
    }
  });

});

var sizePhotos = $(".photos-nav .swiper-wrapper .swiper-slide").length;

if(sizePhotos <= 4){
  $(".photos-arrow").addClass("hide");
}
// END vertical slider


$(document).ready(function() {

  $("#kuki-modal").modal("show");

  console.log($("#feedback-form").find("input[name=name]").data("mess"));

  $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    $('.product_slider').slick('setPosition');
  })


  $(".pf-box label input[checked=checked]").each(function(){
    var value = $(this).val();
    $(this).parents(".pf-item").find(".pf-item-val").text(value)
  });

  $("input[name=color]").change(function(){
    var value = $(this).val();
    $(this).parents(".pf-item").find(".pf-item-val").text(value)
  });

  $("input[name=size]").change(function(){
    var value = $(this).val();
    $(this).parents(".pf-item").find(".pf-item-val").text(value)
  });

  $(".setItemSize").click(function(){
    var size = $(this).data("size");

    $(".setItemSizeInput").parent().find(".pf-item-val").text(size)
  });


  $('.search-input input').keyup(function(){
    var $this = $(this);
    var value = $this.val();
    if(value != 0){
      $($this).parent().find(".btn-search").addClass("view");
    } else{
      $($this).parent().find(".btn-search").removeClass("view");
    }
  });




  var headerHeight = $(".header").height();

  $(window).scroll(function(){

    var top = $(window).scrollTop();

    if(top >= headerHeight){
      $(".top-nav").addClass("fix");
    } else{
      $(".top-nav").removeClass("fix");
    }

  });



  $("#feedback-form").validate({
      errorContainer: $(".form-error-mess"),
      rules: {
        name: "required",
        phone: "required",
        email: {
          required: false
        }
      },
      messages: {
        name: $("#feedback-form").find("input[name=name]").data("mess"),
        phone: $("#feedback-form").find("input[name=phone]").data("mess")
      }
    });


  //form styler
  $('select').styler({
    selectSearch: true,
  });

  //form styler end


  function mouseWheel($slider) {
    $($slider).on('wheel', { $slider: $slider }, mouseWheelHandler)
  }

  function mouseWheelHandler(event) {
    event.preventDefault()
    var $slider = event.data.$slider
    var delta = event.originalEvent.deltaY
    if(delta > 0) {
      $slider.slick('slickNext')
    }
    else {
      $slider.slick('slickPrev')
    }
  }





  /*var $slider = $(".slider-product");
  $slider
    .on('init', function() {
      mouseWheel($slider)
    })
    .slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      arrows: false,
      infinite: false,
      asNavFor: '.product-nav',
      vertical: true
    });

  var $sliderNav = $(".product-nav");
  $sliderNav
    .slick({
      slidesToShow: 4,
      slidesToScroll: 1,
      asNavFor: '.slider-product',
      prevArrow: '<div class="slider_prev1"><div class="sprite-slider-20"></div></div>',
      nextArrow: '<div class="slider_next1"><div class="sprite-slider-21"></div></div>',
      infinite: false,
      vertical: true,
      focusOnSelect: true
    });  */



    $('.main_slider').slick({
      infinite: true,
      autoplay: true,
      dots: true,
      pauseOnFocus: false,
      pauseOnHover: false,
      prevArrow: '<div class="slider_prev"><div class="sprite-slider-13"></div></div>',
      nextArrow: '<div class="slider_next"><div class="sprite-slider-11"></div></div>',
      dotsClass: 'dot',
      slidesToScroll: 1,
      responsive: [
        {
          breakpoint: 1470,
          settings: {

          }
        },
        {
          breakpoint: 992,
          settings: {
            arrows: false
          }
        }]
    });

    $('.product_slider').slick({
      dots: false,
      infinite: true,
      speed: 300,
      prevArrow: '<div class="slider_prev"><div class="sprite-slider-10"></div></div>',
      nextArrow: '<div class="slider_next"><div class="sprite-slider-4"></div></div>',
      slidesToShow: 4,
      slidesToScroll: 2,

      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: true,
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        }
      ]
    });

    $('.product_slider_last').slick({
      dots: false,
      infinite: true,
      speed: 300,
      prevArrow: '<div class="slider_prev"><div class="sprite-slider-10"></div></div>',
      nextArrow: '<div class="slider_next"><div class="sprite-slider-4"></div></div>',
      slidesToShow: 4,
      slidesToScroll: 2,

      responsive: [
        {
          breakpoint: 1024,
          settings: {
            slidesToShow: 3,
            slidesToScroll: 3,
            infinite: true,
            dots: true
          }
        },
        {
          breakpoint: 600,
          settings: {
            slidesToShow: 2,
            slidesToScroll: 2
          }
        },
        {
          breakpoint: 480,
          settings: {
            slidesToShow: 1,
            slidesToScroll: 1
          }
        }
      ]
    });
    //slick end


    // header scripts
    var winWidth = $(window).width();

    /*$(".footer_feedback-call").hover(function(){
      $("#feedback-modal").modal("show");
    });*/

    /*$("#size_btn").hover(function(){
      $("#size_modal").modal("show");
    });*/

    $(".fly-backet-list").mCustomScrollbar({theme:"dark-2"});

    if(winWidth <= 768){

      $(".have-submenu").click(function(){
        $(this).toggleClass("open");
        $(this).find(".submenu").stop().slideToggle();
      });

      $(".burger").click(function(){
        $(".mobi-nav").stop().slideToggle();
      });
    }

    $(".pf-item").hover(function(){
      $("body").addClass("overlay-main");
    }, function(){
      $("body").removeClass("overlay-main");
    });

    $(".filter-nav li").hover(function(){
      $("body").addClass("overlay-min");
    },function(){
      $("body").removeClass("overlay-min");
    });


    $(".have-submenu").hover(function(){
      $("body").addClass("overlay-max");
    }, function(){
      $("body").removeClass("overlay-max");
    });

    $(".backet-sm").hover(function(){
      $("body").addClass("overlay-max");
    }, function(){
      $("body").removeClass("overlay-max");
    });

    $(".call-profile").hover(function(){
      $("body").addClass("overlay-max");
    }, function(){
      $("body").removeClass("overlay-max");
    });

    $(".call-search").hover(function(){
      $("body").addClass("overlay-max");
    }, function(){
      $("body").removeClass("overlay-max");
    });

    //mask input
    // $("#tel, input[type=tel]").mask("+7 (999) 999-9999");
    // $("#phone").mask("+7 (999) 999-9999");

    //email validator
    $('#mail').blur(function() {
      if($(this).val() != '»') {
        var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
        if(pattern.test($(this).val())){
          $(this).css({'border' : '1px solid #569b44'});
          $('#valid').text('True');
          $('#valid').css({'color' : '#569b44'});
        } else {
          $(this).css({'border' : '1px solid #ff0000'});
          $('#valid').text('Not true');
          $('#valid').css({'color' : '#ff0000'});
        }
      } else {
        $(this).css({'border' : '1px solid #ff0000'});
        $('#valid').text('Email field must not be empty');
      }
    });

    $('#email').blur(function() {
      if($(this).val() != '') {
        var pattern = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;
        if(pattern.test($(this).val())){
          $(this).css({'border' : '1px solid #569b44'});
          $('#valid').css({'color' : '#009853'});
          $('#valid').text('True');
        } else {
          $(this).css({'border' : '1px solid #ff0000'});
          $('#valid').css({'color' : '#fd0000'});
          $('#valid').text('Not true');
        }
      } else {
          $(this).css({'border' : '1px solid #ff0000'});
          $('#valid').css({'color' : '#fd0000'});
          $('#valid').text('Email field must not be empty');
      }
    });

    $('#email').on('keypress', function() {
        var that = this;
        setTimeout(function() {
            var res = /[^a-z0-9!@#$%^&*():.,-=?/]/g.exec(that.value);
            that.value = that.value.replace(res, '');
        }, 0);
    });

    $('#emaill').on('keypress', function() {
        var that = this;
        setTimeout(function() {
            var res = /[^a-z0-9!@#$%^&*():.,-=?/]/g.exec(that.value);
            that.value = that.value.replace(res, '');
        }, 0);
    });
    //email validator end




    validation_scripts();


    $('.control-group').click(function(e) {
      e.stopPropagation();
      $('#model .dropdown-menu').toggle();
    });

    $('#show_passwd').on('click',function(event){
      document.getElementById("passwd_input").attributes["type"].value = "text";
      $(this).css('display', 'none');
    });

    window.onscroll = function() {
      scrollFunction();
    };

    function scrollFunction() {
      var block1 = $('.catalog_filter'),
          block2 = $('#catalogGrid');
      if($(block1)[0] && $(block2)[0]) {
        if ($('html').scrollTop() > block2.offset().top) {
          block1.addClass('fixed');
        }
        if ($('html').scrollTop() < block2.offset().top) {
          block1.removeClass('fixed');
        }
      }
    }

}); // jquery ready end



$.fn.notify = function(settings_overwrite){

    settings = {
        placement:"top",
        default_class: ".notify",
        // content_class: ".success-message",
        delay:0,
        hideDelay: 3000,
        content: '',
    };
    $.extend(settings, settings_overwrite);

    obj = $(this);

    $(settings.default_class).each(function(){$(this).hide();});

    obj.find('.notify__message').html(settings.content);

    obj.show().css(settings.placement, -obj.outerHeight());

    if(settings.placement == "bottom"){
      setTimeout(function(){obj.animate({bottom:"0"}, 500)},settings.delay);
    }
    else{
      setTimeout(function(){obj.animate({top:"0"}, 500)},settings.delay);
    }

    setTimeout(function(){
      obj.fadeTo('slow', 0, function() {
         obj.slideUp("slow", function() {
             // obj.remove();
             obj.removeAttr('style');
          });
      })},settings.hideDelay);

    obj.on('click', (function () {
       obj.fadeTo('slow', 0, function() {
           obj.slideUp("slow", function() {
                // $(this).remove();
                 obj.removeAttr('style');
            });
        });
    }));
};


/*if(document.getElementById('edit-modal') && document.getElementById("edit_lk-modal")) {
  document.getElementById("edit_lk-modal").onclick = function() {
      document.getElementById('edit-modal').style.display = "block";
  }

  document.getElementById("close2").onclick = function() {
      document.getElementById('edit-modal').style.display = "none";
  }

  window.onclick = function(event) {
      if (event.target == document.getElementById('edit-modal')) {
          document.getElementById('edit-modal').style.display = "none";
      }
  }
}

if(document.getElementById('passwd-modal') && document.getElementById("passwd_lk-modal")) {
  document.getElementById("passwd_lk-modal").onclick = function() {
      document.getElementById('passwd-modal').style.display = "block";
  }
  document.getElementById("close3").onclick = function() {
      document.getElementById('passwd-modal').style.display = "none";
  }
  window.onclick = function(event) {
      if (event.target == document.getElementById('passwd-modal')) {
          document.getElementById('passwd-modal').style.display = "none";
      }
  }
}

if(document.getElementById('address-modal') && document.getElementById("address_lk-modal")) {
  document.getElementById("address_lk-modal").onclick = function() {
      document.getElementById('address-modal').style.display = "block";
  }
  document.getElementById("close4").onclick = function() {
      document.getElementById('address-modal').style.display = "none";
  }
  window.onclick = function(event) {
      if (event.target == document.getElementById('address-modal')) {
          document.getElementById('address-modal').style.display = "none";
      }
  }
}*/


function preventDigits(sender) {
  sender.value = sender.value.replace(/\d/g, "");
  //sender.value = sender.value.replace(/[^а-я А-Я]/g.exec(sender.value), '');
}

function validation_scripts() {
  var body = $('body');

  body.on('click touch', '.js-validate-form :submit', function(e) {
  	var form = $(this).closest('form');
  	return validateForm(form);
  });

  body.on('keyup change', '.input--error, .input--success', function() {
    if ( validInput( $(this) ) )  {
    	$(this).removeClass('input--error');
      $(this).addClass('input--success');
    } else {
	    $(this).removeClass('input--success');
	    $(this).addClass('input--error');
	  };
	});
};

function validateForm(form) {
	var form = form,
    error = false;

    $('.required', form).removeClass('input--error');
    $('.required', form).removeClass('input--success');

    $('.required:not([disabled])', form).each(function(){
      var input = $(this);

      if( validInput( input ) ) {
        input.addClass('input--success');
      } else {
        error = true;
        input.addClass('input--error');
      };

    });

    if ( !error && form.find('.required--reset-password').length ) {
    	if ( form.find('.required--reset-password').eq(0).val() != form.find('.required--reset-password').eq(1).val() ) {
    		alertMessage('Password mismatch', 'error', 'top');
	      return false;
    	}
    };

    if ( error ) {
        if ( $('.input--error.required--rus:not([disabled]):visible', form).length ) {
        	$('.input--error.required--rus:not([disabled]):visible', form).focus();
            alertMessage('Enter a name', 'error', 'top');
        } else {
            alertMessage('Required fields are not filled', 'error', 'top');
            $('.input--error:not([disabled]):visible:first', form).focus();
        }
        return false;
    }
};


function validInput(input) {

  var result = true,
    type = input.attr('type'),
    val = input.val().trim(),
    patternEmail = /^([a-z0-9_\.-])+@[a-z0-9-]+\.([a-z]{2,4}\.)?[a-z]{2,4}$/i;

  if( val == '' ) {
    result = false;
  };

  if( type == 'tel' &&  val.indexOf("_") != -1) {
    result = false;
  };

  if( input.hasClass('required--rus') && ( val.search(/^[А-Яа-яЁё\s]+$/) == -1 ) ) {
      result = false;
  }

  if( type == 'email' &&  !( patternEmail.test( val ) ) ) {
    result = false;
  };

  if( type == 'search' &&  !( patternEmail.test( val ) ) ) {
    result = false;
  };

  return result;
};


function alertMessage(content, type, placement){
  var type = type ? type : 'def',
      placement = placement ? placement : 'top',
      hideDelay = hideDelay ? hideDelay : 4000,
      id = '#notify_w22arning';

  switch (type) {
    case 'success':
      id = '#notify_success';
      break;
    case 'error':
      id = '#notify_error';
      break;
    case 'warning':
      id = '#notify_warning';
      break;
    default:
      id = '#notify_warning'
  }

  if ( ( $(id).length ) && ( content ) )  {
    $(id).notify({
      delay: 100,
      hideDelay: hideDelay,
      content: content,
      placement: placement,
    });
  } else {
    console.log('No find element with this id OR no content');
  }
}
