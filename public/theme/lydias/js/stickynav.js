function sticky_relocate() {
      var window_top = $(window).scrollTop();
      var div_top = $('#content-anchor').offset().top;
      if (window_top > div_top) {
          
        $('#sticky').addClass('stick');
          
        // Get the height of #sticky
        // outerHeight() gets height including padding and borders
        var phantomHeight = $('#sticky').outerHeight();
          
        // Set the height of $sticky-phantom
        $('#sticky-phantom').height(phantomHeight).show().fadeIn();
          
      } else {
        $('#sticky').removeClass('stick');
        $('#sticky-phantom').hide();
      }
    }

    $(function () {
      $(window).scroll(sticky_relocate);
      sticky_relocate();
    });