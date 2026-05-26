(function () {
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

  function initGlobalLayoutReveal() {
    var section = document.querySelector('[data-about-global]');
    if (!section) {
      return;
    }

    var items = Array.prototype.slice.call(section.querySelectorAll('[data-global-reveal]'));
    items.sort(function (a, b) {
      return Number(a.getAttribute('data-global-order') || 0) - Number(b.getAttribute('data-global-order') || 0);
    });

    if (!items.length) {
      return;
    }

    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
      items.forEach(function (el) {
        el.classList.add('is-revealed');
      });
      return;
    }

    var revealed = false;

    function revealAll() {
      if (revealed) {
        return;
      }
      revealed = true;
      items.forEach(function (el, index) {
        window.setTimeout(function () {
          el.classList.add('is-revealed');
        }, index * 140);
      });
    }

    if (typeof IntersectionObserver === 'undefined') {
      revealAll();
      return;
    }

    var observer = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            revealAll();
            observer.disconnect();
          }
        });
      },
      { threshold: 0.12, rootMargin: '0px 0px -6% 0px' }
    );

    observer.observe(section);
  }

  initGlobalLayoutReveal();
})();
