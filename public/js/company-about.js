(function () {
  document.querySelectorAll('.site-header__nav-link[data-route]').forEach(function (link) {
    var routes = (link.getAttribute('data-route') || '').split(',');
    var path = window.location.pathname.replace(/\/$/, '') || '/';
    var match = routes.some(function (key) {
      if (key === 'about') return path === '/about';
      if (key === 'culture') return path === '/about';
      return false;
    });
    if (match) link.classList.add('is-active');
  });

  function initTabSwitch(root) {
    var tabs = Array.from(root.querySelectorAll('[data-cp-tab]'));
    var panels = Array.from(root.querySelectorAll('[data-cp-panel]'));
    if (!tabs.length) return;

    function setActive(index) {
      if (index < 0 || index >= tabs.length) return;
      tabs.forEach(function (tab, i) {
        var on = i === index;
        tab.classList.toggle('is-active', on);
        tab.setAttribute('aria-selected', on ? 'true' : 'false');
      });
      panels.forEach(function (panel, i) {
        var on = i === index;
        panel.classList.toggle('is-active', on);
        if (on) {
          panel.removeAttribute('hidden');
        } else {
          panel.setAttribute('hidden', '');
        }
      });
    }

    tabs.forEach(function (tab, i) {
      tab.addEventListener('click', function () {
        setActive(i);
      });
    });
  }

  document.querySelectorAll('[data-cp-tabs]').forEach(initTabSwitch);
})();
