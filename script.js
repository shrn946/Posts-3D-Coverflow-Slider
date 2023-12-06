document.addEventListener('DOMContentLoaded', function () {
  var sliderSelector = '.swiper-container',
    options = {
      init: false,
      loop: true,
      speed: 800,
      slidesPerView: 2, // or 'auto'
      // spaceBetween: 10,
      centeredSlides: true,
      effect: 'coverflow', // 'cube', 'fade', 'coverflow',
      coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows: true,
      },
      grabCursor: true,
      parallax: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev',
      },
      breakpoints: {
        1023: {
          slidesPerView: 1,
          spaceBetween: 0,
        },
        767: {
          slidesPerView: 1,
          spaceBetween: 0,
        },
      },
      // Events
      on: {
        imagesReady: function () {
          this.el.classList.remove('loading');
        },
      },
    };

  // Add handleElementorBreakpoints
  function handleElementorBreakpoints() {
    // Your custom breakpoint handling logic here
    // You can use Elementor's API or any other method to handle breakpoints
  }

  // Call handleElementorBreakpoints when the window is resized
  window.addEventListener('resize', handleElementorBreakpoints);

  var mySwiper = new Swiper(sliderSelector, options);

  // Initialize slider
  mySwiper.init();
});
