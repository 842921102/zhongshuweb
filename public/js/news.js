(function () {
  /* Banner responsive background */
  var bannerBg = document.querySelector('[data-banner-bg]');
  if (bannerBg) {
    function swapBanner() {
      var mobile = bannerBg.getAttribute('data-banner-mobile');
      var pc = bannerBg.getAttribute('data-banner-pc') || '';
      var url = window.innerWidth <= 640 && mobile ? mobile : pc;
      if (url) {
        bannerBg.style.backgroundImage = "url('" + url + "')";
      }
    }
    swapBanner();
    window.addEventListener('resize', swapBanner);
  }

})();
