$(window).scroll(function() {
    if ($(this).scrollTop()) {
        $('#top').fadeIn();
    } else {
        $('#top').fadeOut();
    }
});

$(window).on('orientationchange resize load', function() {
    ww = $(window).width();
    if (ww < 1024) {

        $("#menu-desk").mmenu({
            "extensions": [
                "effect-menu-zoom",
                "pagedim-black"
            ],
            "offCanvas": {
                "position": "right"
            }
        });

        //$(".main-links").appendTo(".main-links-resp");
    }
});

$(function() {

    $("#top").click(function() {
        $("html, body").animate({ scrollTop: 0 }, 500);
    });

    $('.news-slider').owlCarousel({
        loop: true,
        margin: 15,
        dots: false,
        nav: true,
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1000: {
                items: 3
            }
        }
    })

});

$(document).ready(function() {
    $('#menu-desk').find('li').hover(
        function() {
            $(this).children('ul').fadeIn();
        },
        function() {
            $(this).children('ul').stop(true, true).fadeOut();
        }
    )
});

$(document).ready(function() {
    $('.main-links').find('li').hover(
        function() {
            $(this).children('ul').fadeIn();
        },
        function() {
            $(this).children('ul').stop(true, true).fadeOut();
        }
    )
});

// product quantity
$('.btn-number').click(function(e){
    e.preventDefault();

    fieldName = $(this).attr('data-field');
    type      = $(this).attr('data-type');
    var input = $("input[name='"+fieldName+"']");
    var currentVal = parseInt(input.val());
    if (!isNaN(currentVal)) {
        if(type == 'minus') {

            if(currentVal > input.attr('min')) {
                input.val(currentVal - 1).change();
            }
            if(parseInt(input.val()) == input.attr('min')) {
                $(this).attr('disabled', true);
            }

        } else if(type == 'plus') {

            if(currentVal < input.attr('max')) {
                input.val(currentVal + 1).change();
            }
            if(parseInt(input.val()) == input.attr('max')) {
                $(this).attr('disabled', true);
            }

        }
    } else {
        input.val(0);
    }
});
$('.input-number').focusin(function(){
    $(this).data('oldValue', $(this).val());
});
$('.input-number').change(function() {

    minValue =  parseInt($(this).attr('min'));
    maxValue =  parseInt($(this).attr('max'));
    valueCurrent = parseInt($(this).val());

    name = $(this).attr('name');
    if(valueCurrent >= minValue) {
        $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the minimum value was reached');
        $(this).val($(this).data('oldValue'));
    }
    if(valueCurrent <= maxValue) {
        $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
    } else {
        alert('Sorry, the maximum value was reached');
        $(this).val($(this).data('oldValue'));
    }


});

// checkout process
$('.btnNext').click(function(){
    $('.nav-tabs > .active').next('li').find('a').trigger('click');
});

$('.btnPrevious').click(function(){
    $('.nav-tabs > .active').prev('li').find('a').trigger('click');
});

// onload page ads
// $(document).ready(function(){
//     $("#ns-box").modal('show');
// });

function ns_hide() {
    var date = new Date()
    document.cookie = "cookie_promo=true; Max-Age=50400; path=/";
}
function ns_show(){
    var isClicked = document.cookie.replace(/(?:(?:^|.*;\s*)cookie_promo\s*\=\s*([^;]*).*$)|^.*$/, "$1");
    if(isClicked==""){ isClicked = false; }else{ isClicked = true; }
    return isClicked;
}
$(document).ready(function(){
    if(ns_show()){
        $("#ns-box").modal('hide');
    } else {
        $("#ns-box").modal('show');
    }
});

$(".user-menu a").click(function(){
  $(".user-menu-list").fadeToggle(300);
});
$(window).click(function() {
  $(".user-menu-list").fadeOut(300);
});
$('.user-menu a').click(function(event){
    event.stopPropagation();
});

$('#callHotline').click(function() {
    $('.call-hotline').addClass("active");
});

$('.call-hotline-close-btn').click(function () {
    $('.call-hotline').removeClass("active");
});

$(".main-links li").hover(function() {
	$(this).siblings("ul").stop(true, true).slideToggle();
});

$(document).ready(function(){
	$("#myModal").modal('show');
});


// menu page side nav
function openNav() {
  document.getElementById("mySidenav").style.left = "0";
  $(".dark-curtain").fadeIn();
}
/* Set the width of the side navigation to 0 */
function closeNav() {
  document.getElementById("mySidenav").style.left = "-300px";
	$(".dark-curtain").fadeOut();
} 
function myFunction(x) {
  if (x.matches) { // If media query matches
    $(".paynow a").css("height", pay + "px");
    $(".sidenav").append($(".menu-category"));
    $(".nav-tabs.payment .nav-link").click(function(){
		$(this).animate({height:"10rem"},200);
		$(this).siblings().animate({height:"2.5rem"},200);
	});
  } else {
    $(".desk-cat").append($(".sidenav .menu-category"));
  }
}
var pay = $(".table-history td").height() - 3;
var x = window.matchMedia("(max-width: 900px)")
myFunction(x) 
x.addListener(myFunction)


