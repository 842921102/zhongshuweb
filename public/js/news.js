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

  /* Nav active state */
  document.querySelectorAll('.site-header__nav-link[data-route]').forEach(function (link) {
    var routes = (link.getAttribute('data-route') || '').split(',');
    var path = window.location.pathname.replace(/\/$/, '') || '/';
    if (routes.some(function (key) {
      return key === 'news' && (path === '/news' || path.indexOf('/news/') === 0);
    })) {
      link.classList.add('is-active');
      link.setAttribute('aria-current', 'page');
    }
  });
})();
