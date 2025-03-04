$(document).ready(function() {
  var bannerSlick = "#" + bannerID;
  $(bannerSlick)
    .parent("div")
    .prepend('<div id="preloader"><div id="status">&nbsp;</div></div>');

  setInterval(function() {
    $(bannerSlick + " div.hero-slide").attr("data-animation-in", bannerFxIn);
    $(bannerSlick + " div.hero-slide").attr("data-animation-out", bannerFxOut);
    $(bannerSlick + " div.hero-slide").attr("data-delay", "0s");
  }, 3000);

  $(bannerSlick).on("beforeChange", function(
    e,
    slick,
    currentSlide,
    nextSlide
  ) {
    var $animatingElements = $(
      'div.slick-slide[data-slick-index="' + currentSlide + '"]'
    ).find("[data-animation-out]");
    doAnimationOut($animatingElements);
    var $animatingElements = $(
      'div.slick-slide[data-slick-index="' + nextSlide + '"]'
    ).find("[data-animation-in]");
    doAnimationIn($animatingElements);
  });

  $(bannerSlick).slick({
    autoplay: true,
    autoplaySpeed: autoPlayTimeout,
    dots: true,
    fade: true,
    arrows: true,
    pauseOnHover: false
  });

  function doAnimationIn(elements) {
    var animationEndEvents =
      "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";
    elements.each(function() {
      var $this = $(this);
      var $animationDelay = $this.data("delay");
      var $animationType = "animated " + $this.data("animation-in");
      $this.addClass(bannerFxIn);
      $this.css({
        "animation-delay": $animationDelay,
        "-webkit-animation-delay": $animationDelay
      });
      $this.addClass($animationType).one(animationEndEvents, function() {
        $this.removeClass($animationType);
      });
    });
  }
  function doAnimationOut(elements) {
    var animationEndEvents =
      "webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend";
    elements.each(function() {
      var $this = $(this);
      var $animationDelay = $this.data("delay");
      var $animationType = "animated " + $this.data("animation-out");
      $this.css({
        "animation-delay": $animationDelay,
        "-webkit-animation-delay": $animationDelay
      });
      $this.addClass($animationType).one(animationEndEvents, function() {
        $this.removeClass($animationType);
      });
    });
  }

  var latestSlick = "#slick-latest";
  $(latestSlick)
    .parent("div")
    .prepend('<div id="pre-loader"></div>');

  $(latestSlick).slick({
    infinite: true,
    slidesToShow: 3,
    slidesToScroll: 1,
    arrows: true,
    responsive: [
      {
        breakpoint: 1200,
        settings: {
          slidesToShow: 3
        }
      },
      {
        breakpoint: 992,
        settings: {
          slidesToShow: 2
        }
      },
      {
        breakpoint: 640,
        settings: {
          slidesToShow: 1
        }
      }
      // You can unslick at a given breakpoint now by adding:
      // settings: "unslick"
      // instead of a settings object
    ]
  });

  var productGallerySlick = "#product-gallery-slider";
  $(productGallerySlick)
    .parent("div")
    .prepend('<div id="pre-loader"></div>');

  $(productGallerySlick).slick({
    infinite: false,
    slidesToShow: 4,
    slidesToScroll: 1,
    arrows: true
  });
});
